<?php

namespace Database\Seeders;

use App\Models\Common\Menu;
use Illuminate\Database\Seeder;

class CreativeSuiteAdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the admin parent menu item (if it exists)
        $adminParent = Menu::where('key', 'admin')->first();
        
        // If no admin parent exists, create one
        if (!$adminParent) {
            $adminParent = Menu::create([
                'key' => 'admin',
                'route' => 'admin.index',
                'label' => 'Admin',
                'icon' => 'tabler-settings',
                'order' => 100,
                'is_active' => true,
                'type' => 'item-dropdown',
                'extension' => false,
                'custom_menu' => true,
            ]);
        }

        // Check if Creative Suite Templates menu already exists
        $existingMenu = Menu::where('key', 'admin_creative_suite_templates')->first();
        
        if (!$existingMenu) {
            // Create the Creative Suite Templates admin menu item
            Menu::create([
                'parent_id' => $adminParent->id,
                'key' => 'admin_creative_suite_templates',
                'route' => 'admin.creative-suite.templates.index',
                'label' => 'Creative Suite Templates',
                'icon' => 'tabler-template',
                'order' => 50,
                'is_active' => true,
                'type' => 'item',
                'extension' => false,
                'custom_menu' => true,
            ]);
            
            $this->command->info('Creative Suite Templates admin menu item created successfully.');
        } else {
            $this->command->info('Creative Suite Templates admin menu item already exists.');
        }
    }
}
