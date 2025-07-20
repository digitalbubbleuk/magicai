@extends('default.layout.app')

@section('title', $setting->site_name)

@section('content')
<head>
    <style>
        /* Simple text rotator animation */
        .text-rotator-item {
            display: none;
        }
        .text-rotator-item.active {
            display: inline-block;
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="group/body">
    <!-- Header -->
    @include('default.layout.header-clean')

    <!-- Banner Section -->
    @include('default.landing-page.banner.section-clean')

    <!-- Features Section -->
    @if(isset($tools) && count($tools) > 0)
    <section class="site-section py-20" id="features">
        <div class="container">
            <div class="mx-auto mb-16 max-w-xl text-center">
                <h2 class="mb-4 text-4xl font-bold">{{ __('Powerful Features') }}</h2>
                <p class="text-lg text-gray-600">{{ __('Explore the tools that will transform your workflow') }}</p>
            </div>
            
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($tools as $tool)
                <div class="rounded-xl bg-white p-6 shadow-sm transition-all hover:shadow-md">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        {!! $tool->image !!}
                    </div>
                    <h3 class="mb-3 text-xl font-semibold">{{ __($tool->title) }}</h3>
                    <p class="text-gray-600">{{ __($tool->description) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- How It Works -->
    @if(isset($howitWorks) && count($howitWorks) > 0)
    <section class="site-section bg-gray-50 py-20" id="how-it-works">
        <div class="container">
            <div class="mx-auto mb-16 max-w-xl text-center">
                <h2 class="mb-4 text-4xl font-bold">{{ __('How It Works') }}</h2>
                <p class="text-lg text-gray-600">{{ __('Simple steps to get started') }}</p>
            </div>
            
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                @foreach($howitWorks as $work)
                <div class="flex flex-col items-center text-center">
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-primary text-2xl font-bold text-white">
                        {{ $loop->iteration }}
                    </div>
                    <h3 class="mb-3 text-xl font-semibold">{{ __($work->title) }}</h3>
                    <p class="text-gray-600">{{ __($work->description) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Testimonials -->
    @if(isset($testimonials) && count($testimonials) > 0)
    <section class="site-section py-20" id="testimonials">
        <div class="container">
            <div class="mx-auto mb-16 max-w-xl text-center">
                <h2 class="mb-4 text-4xl font-bold">{{ __('What Our Users Say') }}</h2>
                <p class="text-lg text-gray-600">{{ __('Trusted by thousands of users worldwide') }}</p>
            </div>
            
            <div class="testimonial-slider">
                @foreach($testimonials as $testimonial)
                <div class="testimonial-slide px-4">
                    <div class="rounded-xl bg-white p-8 shadow-sm">
                        <div class="mb-4 flex items-center">
                            <div class="mr-4 h-12 w-12 overflow-hidden rounded-full">
                                <img src="{{ isset($testimonial->avatar) ? asset($testimonial->avatar) : asset('assets/img/auth/default-avatar.png') }}" alt="{{ $testimonial->name }}" class="h-full w-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-semibold">{{ $testimonial->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $testimonial->title }}</p>
                            </div>
                            <div class="ml-auto flex text-yellow-400">
                                @for($i = 0; $i < 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-600">{{ $testimonial->content }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Pricing -->
    @if(isset($plansSubscriptionMonthly) && count($plansSubscriptionMonthly) > 0)
    <section class="site-section bg-gray-50 py-20" id="pricing">
        <div class="container">
            <div class="mx-auto mb-16 max-w-xl text-center">
                <h2 class="mb-4 text-4xl font-bold">{{ __('Simple Pricing') }}</h2>
                <p class="text-lg text-gray-600">{{ __('Choose the plan that fits your needs') }}</p>
            </div>
            
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                @foreach($plansSubscriptionMonthly as $plan)
                <div class="rounded-xl bg-white p-8 shadow-sm transition-all hover:shadow-md">
                    <div class="mb-6">
                        <h3 class="mb-2 text-2xl font-bold">{{ __($plan->name) }}</h3>
                        <div class="mb-4">
                            <span class="text-3xl font-bold">{{ $currency }}{{ $plan->price }}</span>
                            <span class="text-gray-500">/{{ __('month') }}</span>
                        </div>
                        <p class="text-gray-600">{{ __($plan->description) }}</p>
                    </div>
                    <ul class="mb-8 space-y-3">
                        <li class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ __($plan->features) }}
                        </li>
                    </ul>
                    <a href="{{ route('login') }}" class="block w-full rounded-lg bg-primary py-3 text-center font-medium text-white transition-all hover:bg-primary-dark">
                        {{ __('Get Started') }}
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    @include('default.layout.footer-clean')

    <script>
        // Initialize testimonial slider
        document.addEventListener('DOMContentLoaded', function() {
            // Text rotator
            const rotatorItems = document.querySelectorAll('[data-text-rotator]');
            if (rotatorItems.length > 1) {
                let currentIndex = 0;
                setInterval(() => {
                    rotatorItems[currentIndex].classList.remove('active');
                    rotatorItems[currentIndex].classList.add('hidden');
                    
                    currentIndex = (currentIndex + 1) % rotatorItems.length;
                    
                    rotatorItems[currentIndex].classList.add('active');
                    rotatorItems[currentIndex].classList.remove('hidden');
                }, 3000);
            }
            
            // Initialize testimonial slider if Flickity is available
            if (typeof Flickity !== 'undefined') {
                new Flickity('.testimonial-slider', {
                    cellAlign: 'center',
                    contain: true,
                    wrapAround: true,
                    autoPlay: 5000,
                    prevNextButtons: true,
                    pageDots: true
                });
            }
        });
    </script>

    @livewireScriptConfig()

    @stack('script')

    @if ($app_is_demo ?? false)
        <x-demo-switcher themes-type="Frontend" />
    @endif
</body>
@endsection
