@php
    $menu_items = app(App\Services\Common\FrontMenuService::class)->generate();
@endphp

<header class="site-header fixed inset-x-0 top-0 z-[100] bg-transparent transition-all duration-300" id="header">
    <nav class="container flex items-center justify-between px-6 py-4 relative z-10">
        <!-- Logo -->
        <a class="site-logo relative z-10" href="{{ route('index') }}">
            <img class="h-10 transition-opacity" src="{{ custom_theme_url($setting->logo_path, true) }}" 
                 alt="{{ $setting->site_name }} logo">
            @if (isset($setting->logo_sticky))
                <img class="absolute left-0 top-0 h-10 opacity-0 transition-opacity" 
                     id="sticky-logo" src="{{ custom_theme_url($setting->logo_sticky_path, true) }}" 
                     alt="{{ $setting->site_name }} logo">
            @endif
        </a>

        <!-- Main Navigation -->
        <div class="hidden lg:block">
            <ul class="flex items-center space-x-8">
                @foreach ($menu_items as $menu_item)
                    <li class="relative">
                        <a class="text-sm font-medium text-white transition-colors hover:text-opacity-80" 
                           href="{{ $menu_item['url'] }}" 
                           @if ($menu_item['target']) target="_blank" @endif>
                            {{ $menu_item['title'] }}
                        </a>
                        @if (!empty($menu_item['mega_menu_id']))
                            @includeFirst(['mega-menu::partials.frontend-megamenu', 'vendor.empty'], ['menu_item' => $menu_item])
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center space-x-4">
            <!-- Language Selector (Simplified) -->
            @if (count(explode(',', $settings_two->languages)) > 1)
                <div class="relative hidden md:block">
                    <button class="flex h-8 w-8 items-center justify-center rounded-full border border-white border-opacity-30 text-white transition-colors hover:bg-white hover:bg-opacity-10">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                    </button>
                    <div class="absolute right-0 top-full mt-2 hidden w-40 rounded-md bg-white py-1 shadow-lg group-hover:block">
                        @foreach (\App\Helpers\Classes\Localization::getSupportedLocales() as $localeCode => $properties)
                            @if (in_array($localeCode, explode(',', $settings_two->languages)))
                                <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                   href="{{ route('language.change', $localeCode) }}" 
                                   rel="alternate" hreflang="{{ $localeCode }}">
                                    {{ country2flag(substr($properties['regional'], strrpos($properties['regional'], '_') + 1)) }}
                                    {{ $properties['native'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Auth Buttons -->
            @auth
                <a class="rounded-lg bg-white bg-opacity-10 px-4 py-2 text-sm font-medium text-white transition-all hover:bg-opacity-20" 
                   href="{{ route('dashboard.index') }}">
                    {!! __('Dashboard') !!}
                </a>
            @else
                <a class="rounded-lg border border-white border-opacity-30 px-4 py-2 text-sm font-medium text-white transition-all hover:bg-white hover:bg-opacity-10" 
                   href="{{ route('login') }}">
                    {!! __($fSetting->sign_in) !!}
                </a>
                <a class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-[#2d1f6d] transition-all hover:bg-opacity-90" 
                   href="{{ route('register') }}">
                    {!! __($fSetting->join_hub) !!}
                </a>
            @endauth

            <!-- Mobile Menu Toggle -->
            <button class="flex h-10 w-10 items-center justify-center rounded-full bg-white bg-opacity-10 lg:hidden" id="mobile-menu-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu (Hidden by default) -->
    <div class="fixed inset-0 z-[100] hidden bg-[#1a1333] pt-20" id="mobile-menu">
        <div class="container px-6 py-8">
            <ul class="space-y-6">
                @foreach ($menu_items as $menu_item)
                    <li>
                        <a class="block text-lg font-medium text-white" 
                           href="{{ $menu_item['url'] }}" 
                           @if ($menu_item['target']) target="_blank" @endif>
                            {{ $menu_item['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            @if (count(explode(',', $settings_two->languages)) > 1)
                <div class="mt-8 border-t border-white border-opacity-10 pt-6">
                    <p class="mb-4 text-sm font-medium text-white">{{ __('Languages') }}</p>
                    <div class="space-y-2">
                        @foreach (\App\Helpers\Classes\Localization::getSupportedLocales() as $localeCode => $properties)
                            @if (in_array($localeCode, explode(',', $settings_two->languages)))
                                <a class="block text-sm text-white text-opacity-70 hover:text-opacity-100" 
                                   href="{{ route('language.change', $localeCode) }}" 
                                   rel="alternate" hreflang="{{ $localeCode }}">
                                    {{ country2flag(substr($properties['regional'], strrpos($properties['regional'], '_') + 1)) }}
                                    {{ $properties['native'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.getElementById('header');
        const stickyLogo = document.getElementById('sticky-logo');
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        // Handle header scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('bg-white', 'shadow-sm');
                header.classList.remove('bg-transparent');
                
                // Change text color on scroll
                const navLinks = header.querySelectorAll('a:not(.site-logo)');
                navLinks.forEach(link => {
                    link.classList.remove('text-white');
                    link.classList.add('text-gray-800');
                });
                
                // Show sticky logo if exists
                if (stickyLogo) {
                    stickyLogo.classList.remove('opacity-0');
                    stickyLogo.previousElementSibling.classList.add('opacity-0');
                }
            } else {
                header.classList.remove('bg-white', 'shadow-sm');
                header.classList.add('bg-transparent');
                
                // Restore text color
                const navLinks = header.querySelectorAll('a:not(.site-logo)');
                navLinks.forEach(link => {
                    link.classList.add('text-white');
                    link.classList.remove('text-gray-800');
                });
                
                // Hide sticky logo if exists
                if (stickyLogo) {
                    stickyLogo.classList.add('opacity-0');
                    stickyLogo.previousElementSibling.classList.remove('opacity-0');
                }
            }
        });
        
        // Mobile menu toggle
        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>
