<div x-intersect.once="loadTemplatesList('{{ $templates_list_url }}')">
    <h4 class="mb-4 flex w-full items-center gap-2 border-b pb-2 text-sm">
        {{ __('Template Library') }}
    </h4>

    <div x-show="loadingTemplatesFailed">
        <x-button @click.prevent="loadTemplatesList('{{ $templates_list_url }}')">
            <x-tabler-download size="4" />
            {{ __('Fetch Templates') }}
        </x-button>
    </div>

    <div x-show="!templatesList.length && !loadingTemplatesFailed">
        <p class="flex items-center gap-1 font-medium">
            <x-tabler-refresh class="size-4 animate-spin" />
            {{ __('Loading Templates') }}
        </p>
    </div>

    <div
        class="grid w-full grid-cols-2 place-content-start place-items-start gap-3"
        x-show="templatesList.length"
        x-cloak
    >
        <template x-for="template in templatesList">
            <a
                class="group relative w-full overflow-hidden rounded-md shadow-lg shadow-black/5 transition-transform hover:scale-105"
                href="#"
                @click.prevent="loadTemplate(template.id); activeTool = null;"
            >
                <img
                    src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg' viewBox%3D'0 0 100 100'%2F%3E"
                    alt="{{ __('Creative Suite Preview') }}"
                    x-intersect.once="$el.src = $el.getAttribute('data-src')"
                    :data-src="template.preview"
                >
                <span class="absolute inset-0 inline-grid place-items-center bg-black/15 text-white opacity-0 transition-opacity group-hover:opacity-100">
                    <x-tabler-plus class="size-5" />
                </span>
            </a>
        </template>
    </div>
</div>
