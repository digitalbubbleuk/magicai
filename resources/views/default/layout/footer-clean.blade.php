<footer class="bg-[#1a1333] py-16 text-white">
    <div class="container mx-auto px-6">
        <!-- Footer Top Section -->
        <div class="mb-16 text-center">
            <h2 class="mb-6 font-heading text-4xl font-bold tracking-tight">{{ __($fSetting->footer_header) }}</h2>
            <p class="mx-auto mb-8 max-w-2xl text-lg text-white text-opacity-70">{{ __($fSetting->footer_text) }}</p>
            <a class="inline-block rounded-lg bg-white px-6 py-3 font-medium text-[#2d1f6d] transition-all hover:bg-opacity-90"
               href="{{ !empty($fSetting->footer_button_url) ? $fSetting->footer_button_url : '#' }}"
               target="_blank">
                {!! __($fSetting->footer_button_text) !!}
            </a>
        </div>
        
        <hr class="border-white border-opacity-10">
        
        <!-- Footer Middle Section -->
        <div class="my-8 flex flex-wrap items-center justify-between gap-y-8">
            <!-- Logo -->
            <div class="w-full md:w-auto">
                <a href="{{ route('index') }}">
                    <img class="h-8" src="{{ custom_theme_url($setting->logo_path, true) }}" alt="{{ $setting->site_name }} logo">
                </a>
            </div>
            
            <!-- Social Links -->
            <div class="w-full md:w-auto">
                <ul class="flex flex-wrap justify-center gap-6">
                    @foreach (\App\Models\SocialMediaAccounts::where('is_active', true)->get() as $social)
                        <li>
                            <a class="flex items-center gap-2 text-sm text-white text-opacity-70 transition-colors hover:text-opacity-100"
                               href="{{ $social['link'] }}" target="_blank" rel="noopener">
                                <span class="w-4">{!! $social['icon'] !!}</span>
                                <span class="hidden sm:inline">{{ $social['title'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Footer Links -->
            <div class="w-full md:w-auto">
                <ul class="flex flex-wrap justify-center gap-6">
                    @foreach (\App\Models\Page::where(['status' => 1, 'show_on_footer' => 1])->get() ?? [] as $page)
                        <li>
                            <a class="text-sm text-white text-opacity-70 transition-colors hover:text-opacity-100"
                               href="/page/{{ $page->slug }}">
                                {{ $page->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        <hr class="border-white border-opacity-10">
        
        <!-- Footer Bottom Section -->
        <div class="mt-8 text-center">
            <p class="text-sm text-white text-opacity-50">
                {{ date('Y') . ' ' . $setting->site_name . '. ' . __($fSetting->footer_copyright) }}
            </p>
        </div>
    </div>
</footer>
