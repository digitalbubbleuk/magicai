<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CreativeSuiteTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CreativeSuiteTemplateController extends Controller
{
    /**
     * Display a listing of templates with pagination, filtering, and search.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'category' => 'sometimes|string|max:100',
            'search' => 'sometimes|string|max:255',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:50',
            'sort' => 'sometimes|string|in:name,category,usage_count,created_at',
            'order' => 'sometimes|string|in:asc,desc'
        ]);

        $query = CreativeSuiteTemplate::active();

        // Apply category filter by category key or ID
        if ($request->filled('category') && $request->category !== 'all') {
            // Check if it's a numeric ID or a category key
            if (is_numeric($request->category)) {
                $query->where('category_id', $request->category);
            } else {
                // Filter by category key
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('key', $request->category);
                });
            }
        }

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Apply sorting - default to ID ascending for consistent ordering
        $sortBy = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Get pagination parameters - default to all templates for Creative Suite
        $limit = $request->get('limit', 100); // Increased default to show all templates
        $page = $request->get('page', 1);

        // Get paginated results with category relationship
        $templates = $query->with('category')->paginate($limit, ['*'], 'page', $page);

        // Transform data for frontend compatibility
        $transformedData = $templates->getCollection()->map(function ($template) {
            return $template->formatted_data;
        });

        // Get all active categories for the response
        $categories = \App\Models\CreativeSuiteCategory::active()
            ->ordered()
            ->get()
            ->map(function ($category) {
                return $category->formatted_data;
            });

        return response()->json([
            'success' => true,
            'data' => $transformedData,
            'pagination' => [
                'current_page' => $templates->currentPage(),
                'last_page' => $templates->lastPage(),
                'per_page' => $templates->perPage(),
                'total' => $templates->total(),
                'has_more' => $templates->hasMorePages()
            ],
            'categories' => $categories
        ]);
    }

    /**
     * Display the specified template.
     */
    public function show(string $id): JsonResponse
    {
        $template = CreativeSuiteTemplate::active()->findOrFail($id);
        
        // Increment usage count
        $template->incrementUsage();
        
        return response()->json([
            'data' => $template->formatted_data
        ]);
    }

    /**
     * Get available categories.
     */
    public function categories(): JsonResponse
    {
        return response()->json([
            'data' => $this->getAvailableCategories()
        ]);
    }

    /**
     * Get popular templates.
     */
    public function popular(Request $request): JsonResponse
    {
        $request->validate([
            'limit' => 'sometimes|integer|min:1|max:20'
        ]);

        $limit = $request->get('limit', 10);
        
        $templates = CreativeSuiteTemplate::active()
            ->popular()
            ->limit($limit)
            ->get()
            ->map(function ($template) {
                return $template->formatted_data;
            });

        return response()->json([
            'data' => $templates
        ]);
    }

    /**
     * Search templates.
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|max:255',
            'limit' => 'sometimes|integer|min:1|max:50'
        ]);

        $limit = $request->get('limit', 20);
        
        $templates = CreativeSuiteTemplate::active()
            ->search($request->q)
            ->limit($limit)
            ->get()
            ->map(function ($template) {
                return $template->formatted_data;
            });

        return response()->json([
            'data' => $templates,
            'query' => $request->q,
            'count' => $templates->count()
        ]);
    }

    /**
     * Get available categories from database.
     */
    private function getAvailableCategories(): array
    {
        return CreativeSuiteTemplate::active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values()
            ->toArray();
    }
}
