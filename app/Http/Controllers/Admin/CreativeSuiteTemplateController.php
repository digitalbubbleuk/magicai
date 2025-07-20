<?php

namespace App\Http\Controllers\Admin;

use App\Models\CreativeSuiteCategory;
use App\Models\CreativeSuiteTemplate;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class CreativeSuiteTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware(\Fahlisaputra\Minify\Middleware\MinifyHtml::class);
    }

    /**
     * Display a listing of templates
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $category = $request->input('category');
        
        $query = CreativeSuiteTemplate::with('category');
        
        if ($search) {
            $query->where('name', 'like', "%$search%");
        }
        
        if ($category) {
            $query->where('category_id', $category);
        }
        
        $templates = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = CreativeSuiteCategory::orderBy('name')->get();
        
        return view('panel.admin.creative-suite.templates.index', compact('templates', 'categories', 'search', 'category'));
    }

    /**
     * Show the form for creating a new template
     */
    public function create(): View
    {
        $categories = CreativeSuiteCategory::orderBy('name')->get();
        return view('panel.admin.creative-suite.templates.create', compact('categories'));
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request): RedirectResponse
    {
        if (Helper::appIsDemo()) {
            return back()->with(['message' => __('This feature is disabled in demo mode.'), 'type' => 'error']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:creative_suite_categories,id',
            'preview' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'template_data' => 'required|json',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Store preview image
            $previewPath = $request->file('preview')->store('templates/previews', 'uploads');
            
            // Parse the input template data (could be Creative Suite export or simple nodes)
            $inputData = json_decode($request->template_data, true);
            
            // Extract stage dimensions for database storage
            $stageWidth = 1080;
            $stageHeight = 1080;
            if (isset($inputData['stage'])) {
                $stageWidth = $inputData['stage']['width'] ?? 1080;
                $stageHeight = $inputData['stage']['height'] ?? 1080;
            }
            
            // Extract nodes array - keep as stringified JSON strings for frontend compatibility
            $nodes = [];
            if (isset($inputData['nodes']) && is_array($inputData['nodes'])) {
                $nodes = array_map(function($node) {
                    // If node is already a stringified JSON string, keep it as is
                    if (is_string($node)) {
                        return $node;
                    }
                    // If node is an object/array, stringify it
                    return json_encode($node);
                }, $inputData['nodes']);
            }
            
            // Handle additional image uploads
            $imageMapping = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('templates/images', 'uploads');
                    $imageMapping[$index] = '/uploads/' . $imagePath;
                }
            }
            
            // Update nodes with new image paths if needed
            if (!empty($imageMapping)) {
                $nodes = $this->updateNodesImagePaths($nodes, $imageMapping);
            }
            
            // Store template data as PHP array - let Laravel's array cast handle JSON encoding
            $templateDataArray = ['nodes' => $nodes];

            $template = CreativeSuiteTemplate::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'preview_image' => '/uploads/' . $previewPath,
                'stage_width' => $stageWidth,
                'stage_height' => $stageHeight,
                'template_data' => $templateDataArray,
            ]);

            return redirect()->route('dashboard.admin.creative-suite.templates.index')
                ->with(['message' => __('Template created successfully.'), 'type' => 'success']);
                
        } catch (\Exception $e) {
            return back()->with(['message' => __('Error creating template: ') . $e->getMessage(), 'type' => 'error'])->withInput();
        }
    }

    /**
     * Show the form for editing a template
     */
    public function edit(CreativeSuiteTemplate $template): View
    {
        $categories = CreativeSuiteCategory::orderBy('name')->get();
        return view('panel.admin.creative-suite.templates.edit', compact('template', 'categories'));
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, CreativeSuiteTemplate $template): RedirectResponse
    {
        if (Helper::appIsDemo()) {
            return back()->with(['message' => __('This feature is disabled in demo mode.'), 'type' => 'error']);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:creative_suite_categories,id',
            'preview' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'template_data' => 'required|json',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $updateData = [
                'name' => $request->name,
                'category_id' => $request->category_id,
            ];

            // Handle preview image update
            if ($request->hasFile('preview')) {
                // Delete old preview
                if ($template->preview) {
                    $oldPreviewPath = str_replace('/uploads/', '', $template->preview);
                    Storage::disk('uploads')->delete($oldPreviewPath);
                }
                
                $previewPath = $request->file('preview')->store('templates/previews', 'uploads');
                $updateData['preview'] = '/uploads/' . $previewPath;
            }

            // Process template data and handle image uploads
            $templateData = json_decode($request->template_data, true);
            
            // Ensure template data has the correct structure to match Creative Suite exports
            if (!isset($templateData['name'])) {
                $templateData['name'] = $request->name;
            }
            if (!isset($templateData['stage'])) {
                $templateData['stage'] = ['width' => 1080, 'height' => 1080];
            }
            if (!isset($templateData['nodes'])) {
                $templateData['nodes'] = [];
            }
            
            // Fix nodes array if it contains stringified JSON (from Creative Suite exports)
            if (isset($templateData['nodes']) && is_array($templateData['nodes'])) {
                $templateData['nodes'] = array_map(function($node) {
                    // If node is a string (stringified JSON), parse it
                    if (is_string($node)) {
                        $parsed = json_decode($node, true);
                        return $parsed ?: $node;
                    }
                    return $node;
                }, $templateData['nodes']);
            }
            $imageMapping = [];
            
            // Handle additional image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('templates/images', 'uploads');
                    $imageMapping[$index] = '/uploads/' . $imagePath;
                }
            }
            
            // Update template data with new image paths
            $templateData = $this->updateTemplateImagePaths($templateData, $imageMapping);
            $updateData['template_data'] = json_encode($templateData);

            $template->update($updateData);

            return redirect()->route('dashboard.admin.creative-suite.templates.index')
                ->with(['message' => __('Template updated successfully.'), 'type' => 'success']);
                
        } catch (\Exception $e) {
            return back()->with(['message' => __('Error updating template: ') . $e->getMessage(), 'type' => 'error'])->withInput();
        }
    }

    /**
     * Remove the specified template
     */
    public function destroy(CreativeSuiteTemplate $template): JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'status' => 'error',
                'message' => __('This feature is disabled in demo mode.')
            ]);
        }

        try {
            // Delete associated files
            if ($template->preview) {
                $previewPath = str_replace('/uploads/', '', $template->preview);
                Storage::disk('uploads')->delete($previewPath);
            }

            // Delete template images from data
            $templateData = json_decode($template->data, true);
            $this->deleteTemplateImages($templateData);

            $template->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('Template deleted successfully.')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('Error deleting template: ') . $e->getMessage()
            ]);
        }
    }

    /**
     * Duplicate a template
     */
    public function duplicate(CreativeSuiteTemplate $template): RedirectResponse
    {
        if (Helper::appIsDemo()) {
            return back()->with(['message' => __('This feature is disabled in demo mode.'), 'type' => 'error']);
        }

        try {
            $newTemplate = $template->replicate();
            $newTemplate->name = $template->name . ' (Copy)';
            $newTemplate->save();

            return back()->with(['message' => __('Template duplicated successfully.'), 'type' => 'success']);
            
        } catch (\Exception $e) {
            return back()->with(['message' => __('Error duplicating template: ') . $e->getMessage(), 'type' => 'error']);
        }
    }

    /**
     * Update template data with new image paths
     */
    private function updateTemplateImagePaths(array $templateData, array $imageMapping): array
    {
        // This method would recursively update image paths in the template data
        // Implementation depends on the specific structure of template data
        // For now, returning the data as-is
        return $templateData;
    }

    /**
     * Update nodes array with new image paths
     */
    private function updateNodesImagePaths(array $nodes, array $imageMapping): array
    {
        // This method would recursively update image paths in the nodes array
        // For now, returning the nodes as-is since the structure depends on the specific template format
        // TODO: Implement proper image path updating based on node structure
        return $nodes;
    }

    /**
     * Delete images referenced in template data
     */
    private function deleteTemplateImages(array $templateData): void
    {
        // This method would recursively find and delete image files referenced in template data
        // Implementation depends on the specific structure of template data
        if (isset($templateData['objects'])) {
            foreach ($templateData['objects'] as $object) {
                if (isset($object['src']) && str_starts_with($object['src'], '/uploads/')) {
                    $imagePath = str_replace('/uploads/', '', $object['src']);
                    Storage::disk('uploads')->delete($imagePath);
                }
            }
        }
    }
}
