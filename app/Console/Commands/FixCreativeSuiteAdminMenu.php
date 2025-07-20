<?php

namespace App\Console\Commands;

use App\Models\Common\Menu;
use Illuminate\Console\Command;

class FixCreativeSuiteAdminMenu extends Command
{
    protected $signature = 'menu:fix-creative-suite-admin';
    protected $description = 'Fix Creative Suite admin menu URL';

    public function handle()
    {
        // Find and update the Creative Suite Templates menu item
        $menu = Menu::where('key', 'admin_creative_suite_templates')->first();
        
        if ($menu) {
            $menu->update([
                'route' => 'admin.creative-suite.templates.index',
                'route_slug' => '/admin/creative-suite/templates',
            ]);
            
            $this->info('Creative Suite admin menu fixed successfully!');
            $this->info('URL should now be: http://127.0.0.1:8000/admin/creative-suite/templates');
        } else {
            $this->error('Creative Suite admin menu item not found. Please run the seeder first.');
        }
        
        // Clear menu cache
        cache()->forget('dynamic_menu_key');
        $this->info('Menu cache cleared.');
        
        return 0;
    }
}
