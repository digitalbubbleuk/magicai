<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CreativeSuiteTemplate;
use Illuminate\Support\Facades\File;

class CreativeSuiteTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing templates
        CreativeSuiteTemplate::truncate();
        
        // Load JSON data
        $jsonPath = public_path('vendor/creative-suite/templates/templates.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error('Templates JSON file not found at: ' . $jsonPath);
            return;
        }
        
        $jsonContent = File::get($jsonPath);
        $templates = json_decode($jsonContent, true);
        
        if (!$templates || !is_array($templates)) {
            $this->command->error('Invalid JSON format in templates file');
            return;
        }
        
        $this->command->info('Importing ' . count($templates) . ' templates...');
        
        foreach ($templates as $template) {
            // Extract template name from preview image filename
            $previewPath = $template['preview'] ?? '';
            $templateName = $this->extractTemplateNameFromPath($previewPath);
            
            // Extract stage dimensions
            $stageWidth = $template['data']['stage']['width'] ?? 1080;
            $stageHeight = $template['data']['stage']['height'] ?? 1080;
            
            // Convert string dimensions to integers
            if (is_string($stageWidth)) {
                $stageWidth = (int) $stageWidth;
            }
            if (is_string($stageHeight)) {
                $stageHeight = (int) $stageHeight;
            }
            
            // Create template record
            CreativeSuiteTemplate::create([
                'name' => $templateName,
                'category' => $template['category'] ?? 'Uncategorized',
                'preview_image' => $template['preview'] ?? '',
                'stage_width' => $stageWidth,
                'stage_height' => $stageHeight,
                'template_data' => [
                    'nodes' => $template['data']['nodes'] ?? []
                ],
                'tags' => $this->generateTagsFromTemplate($template),
                'is_active' => true,
                'usage_count' => 0
            ]);
        }
        
        $this->command->info('Successfully imported ' . count($templates) . ' templates!');
    }
    
    /**
     * Extract template name from preview image path
     */
    private function extractTemplateNameFromPath(string $path): string
    {
        $filename = basename($path, '.png');
        $filename = basename($filename, '.jpg');
        
        // Convert kebab-case to title case
        return ucwords(str_replace(['-', '_'], ' ', $filename));
    }
    
    /**
     * Generate tags from template data
     */
    private function generateTagsFromTemplate(array $template): array
    {
        $tags = [];
        
        // Add category as tag
        if (!empty($template['category'])) {
            $tags[] = strtolower($template['category']);
        }
        
        // Add dimension-based tags
        $width = $template['data']['stage']['width'] ?? 0;
        $height = $template['data']['stage']['height'] ?? 0;
        
        if ($width == $height) {
            $tags[] = 'square';
        } elseif ($width > $height) {
            $tags[] = 'landscape';
        } else {
            $tags[] = 'portrait';
        }
        
        // Add size-based tags
        if ($width == 1080 && $height == 1080) {
            $tags[] = 'instagram-post';
        } elseif ($width == 1080 && $height == 1920) {
            $tags[] = 'instagram-story';
        } elseif ($width == 1200 && $height == 675) {
            $tags[] = 'facebook-post';
        } elseif ($width == 1280 && $height == 720) {
            $tags[] = 'youtube-thumbnail';
        }
        
        return array_unique($tags);
    }
}
