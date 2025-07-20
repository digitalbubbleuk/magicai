@php
    // Define frontend settings from available variables
    $fSetting = (object)[
        'hero_subtitle' => $setting->site_description ?? 'AI-Powered Content Generation',
        'hero_title' => $setting->site_name ?? 'Digital Bubble',
        'hero_title_text_rotator' => 'Content,Images,Code,Ideas',
        'hero_description' => $setting->site_description ?? 'Create amazing content with the power of AI',
        'hero_button' => 'Get Started',
        'hero_button_url' => route('login'),
        'hero_button_type' => 1,
        'hero_scroll_text' => 'Scroll down to learn more'
    ];
@endphp

<section class="relative min-h-[70vh] flex items-center py-20 pt-32" id="banner" style="background-color: #1a1333; background-image: linear-gradient(135deg, #1a1333, #2d1f6d, #3a2a8e);">
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Logo badge -->
            <div class="inline-flex items-center mb-8 bg-white bg-opacity-10 backdrop-blur-sm px-5 py-2 rounded-full border border-white border-opacity-20">
                <span class="font-medium text-white">{!! __($setting->site_name) !!}</span>
                <span class="mx-2 text-white text-opacity-50">•</span>
                <span class="text-white text-opacity-80">{!! __($fSetting->hero_subtitle) !!}</span>
            </div>
            
            <!-- Main title -->
           
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight">
                {!! __($fSetting->hero_title) !!}
                @if ($fSetting->hero_title_text_rotator != null)
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-300 to-blue-300 inline-block">
                    {{ explode(',', __($fSetting->hero_title_text_rotator))[0] }}
                </span>
                @endif
            </h1>
            
            <!-- Description -->
            <p class="text-xl text-white text-opacity-90 mb-10 max-w-2xl mx-auto leading-relaxed">
                {!! __($fSetting->hero_description) !!}
            </p>
            
            <!-- Call to action buttons -->
            <div class="flex flex-wrap justify-center gap-4 mb-16">
                @if ($fSetting->hero_button_type == 1)
                    <a class="px-8 py-3 bg-white text-[#2d1f6d] font-medium rounded-lg hover:shadow-lg transition-all transform hover:-translate-y-1"
                       href="{{ !empty($fSetting->hero_button_url) ? $fSetting->hero_button_url : '#' }}">
                        {!! __($fSetting->hero_button) !!}
                    </a>
                @else
                    <a class="inline-flex items-center rounded-full bg-white bg-opacity-20 px-6 py-3 font-medium text-white transition-all hover:bg-opacity-30"
                       data-fslightbox="video-gallery"
                       href="{{ !empty($fSetting->hero_button_url) ? $fSetting->hero_button_url : '#' }}">
                        {!! __($fSetting->hero_button) !!}
                    </a>
                @endif
                
                @if ($fSetting->hero_button_type == 2)
                    <button class="px-8 py-3 bg-white text-[#2d1f6d] font-medium rounded-lg hover:shadow-lg transition-all transform hover:-translate-y-1" data-button="{{ $fSetting->hero_button_url }}">
                        {!! __($fSetting->hero_button) !!}
                    </button>
                @endif
                
                <!-- Secondary action -->
                <a href="#features" class="px-6 py-3 bg-transparent border border-white border-opacity-30 text-white rounded-lg hover:bg-white hover:bg-opacity-10 transition-all">
                    Learn More
                </a>
            </div>
            
            <!-- Scroll indicator -->
            <div class="text-sm text-white text-opacity-70 flex flex-col items-center">
                <span class="mb-2">{!! __($fSetting->hero_scroll_text) !!}</span>
                <div class="h-10 w-6 border-2 border-white border-opacity-30 rounded-full flex justify-center pt-1">
                    <div class="w-1.5 h-3 bg-white rounded-full opacity-75"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom wave separator -->
    <div class="absolute bottom-0 left-0 right-0 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" fill="currentColor" preserveAspectRatio="none">
            <path d="M0,32L60,42.7C120,53,240,75,360,74.7C480,75,600,53,720,48C840,43,960,53,1080,58.7C1200,64,1320,64,1380,64L1440,64L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"></path>
        </svg>
    </div>
</section>
