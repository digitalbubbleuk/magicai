<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CreativeSuiteTemplate;
use App\Models\CreativeSuiteCategory;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create mapping of old category names to new category keys
        $categoryMapping = [
            'Beauty' => 'beauty',
            'Business' => 'business',
            'Events' => 'events',
            'Fashion' => 'fashion',
            'Fitness' => 'fitness',
            'Food' => 'food',
            'Marketing' => 'marketing',
            'Shopping' => 'shopping',
            'Social Media' => 'social_media',
            'Technology' => 'technology'
        ];

        // Get all categories for lookup
        $categories = CreativeSuiteCategory::all()->keyBy('key');

        // Update each template with the correct category_id
        $templates = CreativeSuiteTemplate::whereNotNull('category')->get();
        
        foreach ($templates as $template) {
            $categoryKey = $categoryMapping[$template->category] ?? null;
            
            if ($categoryKey && isset($categories[$categoryKey])) {
                $template->category_id = $categories[$categoryKey]->id;
                $template->save();
                echo "Updated template '{$template->name}' from category '{$template->category}' to category_id {$template->category_id}\n";
            } else {
                echo "Warning: Could not find category mapping for '{$template->category}' in template '{$template->name}'\n";
            }
        }

        echo "Migration completed. Updated " . $templates->count() . " templates.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset all category_id values to null
        CreativeSuiteTemplate::query()->update(['category_id' => null]);
        echo "Reversed migration: Set all category_id values to null.\n";
    }
};
