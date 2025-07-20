@php
    $meta_titles = [
        'company_id' => __('Company'),
        'product_id' => __('Product'),
        'campaign_id' => __('Campaign'),
        'tone' => __('Tone'),
        'link' => __('URL'),
    ];
@endphp

<div
    class="flex w-full flex-wrap justify-between gap-x-4 gap-y-5"
    x-init="prevPostUrl = '{{ $prev_post_id ? route('dashboard.user.social-media.post.show', $prev_post_id) : null }}';
    nextPostUrl = '{{ $next_post_id ? route('dashboard.user.social-media.post.show', $next_post_id) : null }}';"
>
    <div class="w-full md:w-6/12">
        <p @class([
            'lqd-post-content-type inline-grid w-auto mb-4 place-items-center justify-self-start whitespace-nowrap text-[12px] font-medium leading-none',
            'text-green-500' => $post['status'] === 'published',
            'text-yellow-700' => $post['status'] === 'scheduled',
        ])>
            <span
                class="col-start-1 col-end-1 row-start-1 row-end-1 inline-flex items-center gap-1.5 rounded-full border px-2 py-1"
                x-show="loadingState === 'loaded'"
                x-transition.opacity
            >
                @if ($post['status'] === \App\Extensions\SocialMedia\System\Enums\StatusEnum::published)
                    <x-tabler-check class="size-4" />
                @elseif ($post['status'] === \App\Extensions\SocialMedia\System\Enums\StatusEnum::scheduled)
                    <x-tabler-clock class="size-4" />
                @else
                    <x-tabler-circle-dashed class="size-4" />
                @endif
                @lang(str()->title($post->status->value))
            </span>
            <span
                class="col-start-1 col-end-1 row-start-1 row-end-1 inline-block h-[26px] w-24 animate-pulse rounded-full bg-heading-foreground/5"
                x-show="loadingState === 'loading'"
                x-transition.opacity
            ></span>
        </p>

        <p class="lqd-post-content-date mb-4 grid place-items-center text-xs">
            <span
                class="col-start-1 col-end-1 row-start-1 row-end-1 inline-block justify-self-start opacity-50"
                x-show="loadingState === 'loaded'"
                x-transition.opacity
            >
                {{ date('M j Y', strtotime($post->created_at)) }}, {{ date('H:i', strtotime($post->created_at)) }}
            </span>
            <span
                class="col-start-1 col-end-1 row-start-1 row-end-1 inline-block h-[1lh] w-1/2 animate-pulse justify-self-start rounded bg-heading-foreground/5"
                x-show="loadingState === 'loading'"
                x-transition.opacity
            ></span>
        </p>

        <p class="lqd-post-content-content mb-7 grid place-items-start text-2xs/4 font-medium text-heading-foreground">
            <span
                class="col-start-1 col-end-1 row-start-1 row-end-1 inline-block"
                x-show="loadingState === 'loaded'"
                x-transition.opacity
                x-html='lqdFormatString("{{ $post['content'] }}")'
            >
                {{ $post['content'] }}
            </span>
            <span
                class="col-start-1 col-end-1 row-start-1 row-end-1 inline-block h-[3lh] w-full animate-pulse rounded bg-heading-foreground/5"
                x-show="loadingState === 'loading'"
                x-transition.opacity
            ></span>
        </p>

        <div class="lqd-post-content-meta grid grid-cols-2 gap-x-3 gap-y-6">
            @foreach (['company_id', 'product_id', 'campaign_id', 'tone', 'link'] as $meta)
                <div class="contents text-2xs/4 font-medium text-heading-foreground">
                    <p class="m-0 flex flex-wrap items-center gap-1">
                        {{ $meta_titles[$meta] ?? __($meta) }}

                        @if ($meta === 'link' && isset($post[$meta]))
                            <span
                                class="inline-flex shrink-0"
                                x-show="loadingState === 'loaded'"
                                x-transition
                            >
                                <x-button
                                    class="size-6 shrink-0"
                                    size="none"
                                    title="{{ __('Copy Link') }}"
                                    @click.prevent="navigator.clipboard.writeText('{{ $post[$meta] }}'); toastr.success('{{ __('Link copied to clipboard') }}');"
                                >
                                    <x-tabler-link class="size-4" />
                                </x-button>
                            </span>
                        @endif
                    </p>
                    <p class="m-0 grid place-items-center">
                        <span
                            class="col-start-1 col-end-1 row-start-1 row-end-1 justify-self-start opacity-60"
                            x-show="loadingState === 'loaded'"
                            x-transition.opacity
                        >
                            @if (isset($post[$meta]))
                                {{ $meta === 'link' ? $post[$meta] : str()->title($post[$meta]) }}
                            @else
                                ---
                            @endif
                        </span>
                        <span
                            class="col-start-1 col-end-1 row-start-1 row-end-1 inline-block h-[1lh] w-full animate-pulse rounded bg-heading-foreground/5"
                            x-show="loadingState === 'loading'"
                            x-transition.opacity
                        ></span>
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="w-full md:w-4/12">
        <div class="flex flex-col gap-5">
            <div class="grid place-items-center">
                <div
                    class="col-start-1 col-end-1 row-start-1 row-end-1 min-h-80 w-full animate-pulse rounded bg-heading-foreground/5"
                    x-show="loadingState === 'loading'"
                    x-transition.opacity
                ></div>
                <div
                    class="col-start-1 col-end-1 row-start-1 row-end-1"
                    x-show="loadingState === 'loaded'"
                    x-transition.opacity
                >
                    <x-card class:head="border-none pb-0">
                        @php
                            $image = 'vendor/social-media/icons/' . $post->platform?->platform . '.svg';
                            $image_dark_version = 'vendor/social-media/icons/' . $post->platform?->platform . '-light.svg';
                        @endphp
                        <x-slot:head>
                            <figure>
                                <img
                                    @class([
                                        'w-7 h-auto',
                                        'dark:hidden' => file_exists($image_dark_version),
                                    ])
                                    src="{{ asset($image) }}"
                                    alt="{{ $post->platform?->platform }}"
                                />
                                @if (file_exists($image_dark_version))
                                    <img
                                        class="hidden h-auto w-7 dark:block"
                                        src="{{ asset($image_dark_version) }}"
                                        alt="{{ $post->platform?->platform }}"
                                    />
                                @endif
                            </figure>
                        </x-slot:head>

                        <figure class="mb-4 w-full">
                            @if ($post['image'] || $post['video'])
                                @if ($post['image'])
                                    <img
                                        class="w-full rounded"
                                        src="{{ $post['image'] }}"
                                        alt="{{ __('Social Media Post Image') }}"
                                    >
                                @elseif($post['video'])
                                    <video
                                        class="w-full rounded"
                                        src="{{ $post['video'] }}"
                                        controls
                                    ></video>
                                @endif
                            @endif
                        </figure>

                        <p
                            class="mb-0 max-h-24 overflow-hidden text-ellipsis text-2xs/6 font-medium text-heading-foreground"
                            style="mask-image: linear-gradient(to bottom, black 50%, transparent)"
                        >
                            {{ $post['content'] }}
                        </p>
                    </x-card>
                </div>
            </div>

            @if ($post['link'])
                <x-button
                    variant="outline"
                    target="_blank"
                    href="{{ $post['link'] }}"
                    size="lg"
                    x-show="loadingState === 'loaded'"
                >
                    @lang('View Post on ')
                    {{ str()->title($post->platform?->platform) }}
                </x-button>
            @endif

            <x-button
                variant="outline"
                href="#"
                size="lg"
                hover-variant="danger"
                x-show="loadingState === 'loaded'"
                href="{{ route('dashboard.user.social-media.post.delete', $post['id']) }}"
                onclick="return confirm('Are you sure?')"
            >
                @lang('Delete')
            </x-button>
        </div>
    </div>
</div>
