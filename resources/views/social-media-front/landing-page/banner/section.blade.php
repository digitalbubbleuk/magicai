<section class="site-section relative flex min-h-[80vh] items-center justify-center py-32 text-center text-white" id="banner">
    <!-- Simple gradient background -->
    <div class="absolute start-0 top-0 h-full w-full bg-gradient-to-b from-[#2d1f6d] to-[#1a1333]"></div>
    
    <div class="container relative z-10">
        <div class="mx-auto flex w-full max-w-3xl flex-col items-center">
            <!-- Site name and subtitle -->
            <div class="mb-6 rounded-lg bg-white bg-opacity-15 px-4 py-2">
                <span class="font-medium">{!! __($setting->site_name) !!}</span>
                <span class="mx-2">•</span>
                <span class="opacity-80">{!! __($fSetting->hero_subtitle) !!}</span>
            </div>
            
            <!-- Main title -->
            <h1 class="mb-6 font-body text-5xl font-bold tracking-tight text-white max-sm:text-4xl">
                {!! __($fSetting->hero_title) !!}
                @if ($fSetting->hero_title_text_rotator != null)
                    <span class="inline-block">
                        @foreach (explode(',', __($fSetting->hero_title_text_rotator)) as $keyword)
                            <span class="text-rotator-item {{ $loop->first ? 'active' : 'hidden' }}" data-text-rotator>
                                {!! $keyword !!}
                            </span>
                        @endforeach
                    </span>
                @endif
            </h1>
            
            <!-- Description -->
            <p class="mb-8 max-w-2xl text-xl leading-relaxed text-white text-opacity-90 max-sm:text-lg">
                {!! __($fSetting->hero_description) !!}
            </p>
            
            <!-- Call to action button -->
            <div class="mb-10">
                @if ($fSetting->hero_button_type == 1)
                    <a class="rounded-lg bg-white px-8 py-3 font-medium text-[#2d1f6d] transition-all hover:bg-opacity-90 hover:shadow-lg"
                       href="{{ !empty($fSetting->hero_button_url) ? $fSetting->hero_button_url : '#' }}">
                        {!! __($fSetting->hero_button) !!}
                    </a>
                @else
                    <a class="inline-flex items-center rounded-full bg-white bg-opacity-20 px-6 py-3 font-medium text-white transition-all hover:bg-opacity-30"
                       data-fslightbox="video-gallery"
                       href="{{ !empty($fSetting->hero_button_url) ? $fSetting->hero_button_url : '#' }}">
                        <span class="mr-3 flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#2d1f6d]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </span>
                        {!! __($fSetting->hero_button) !!}
                    </a>
                @endif
            </div>
            
            <!-- Scroll indicator -->
            <a class="opacity-70 transition-opacity hover:opacity-100" href="#features">
                {!! __($fSetting->hero_scroll_text) !!}
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mt-2 inline-block">
                    <path d="M12 5v14M5 12l7 7 7-7"/>
                </svg>
            </a>
        </div>
    </div>
    
    <!-- Simple wave divider -->
    <div class="absolute inset-x-0 -bottom-1">
        <svg class="h-auto w-full fill-background" viewBox="0 0 1440 100" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 0C240 60 480 90 720 90C960 90 1200 60 1440 0V100H0V0Z" />
        </svg>
    </div>
</section>
