<div class="lqd-cs-templates py-9" x-data="{ activeCategory: 'all', showCount: 10 }">
    <div class="mb-6 flex items-center justify-between gap-3">
        <h2 class="mb-0 flex items-center gap-1">
            @lang('Find a template')

            <span x-show="!templatesList.length && !loadingTemplatesFailed">
                <x-tabler-refresh class="size-5 animate-spin" />
            </span>
        </h2>

        <x-button
            class="text-2xs font-medium opacity-80 hover:opacity-100"
            variant="link"
            href="#"
            @click.prevent="switchView('editor'); $nextTick(() => {activeTool = 'templates';})"
        >
            @lang('View All')
            <x-tabler-chevron-right class="size-4" />
        </x-button>
    </div>
    
    <!-- Category tabs -->
    <div class="mb-5">
        <div class="flex flex-wrap gap-2 mb-6">
            <!-- All button -->
            <button 
                class="rounded-md px-3 py-1 text-sm transition-colors" 
                :class="activeCategory === 'all' ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200'"
                @click="activeCategory = 'all'; showCount = 10; loadTemplatesList('api', 'all')"
            >
                All
            </button>
            
            <!-- Category buttons -->
            <template x-for="category in (templatesCategories || [...new Set(templatesList.filter(t => t.category).map(t => t.category))])" :key="category.key || category">
                <button 
                    class="rounded-md px-3 py-1 text-sm transition-colors" 
                    :class="activeCategory === (category.key || category) ? 'bg-primary text-white' : 'bg-gray-100 hover:bg-gray-200'"
                    @click="activeCategory = (category.key || category); showCount = 10; loadTemplatesList('api', (category.key || category))"
                    x-text="category.name || category"
                ></button>
            </template>
        </div>
    </div>

    <div
        class="lqd-cs-templates-grid grid grid-cols-1 place-items-start gap-5 sm:grid-cols-2 md:grid-cols-3 md:gap-x-6 lg:grid-cols-5 lg:gap-x-11 [&_.lqd-cs-template:nth-child(n+9)]:hidden"
        x-intersect.once="loadTemplatesList('api')"
    >
        <div
            class="col-span-full w-full"
            x-cloak
            x-show="loadingTemplatesFailed"
        >
            <x-button @click.prevent="loadTemplatesList()">
                <x-tabler-download size="4" />
                {{ __('Fetch Templates') }}
            </x-button>
        </div>

        <div
            class="col-span-full w-full"
            x-cloak
            x-show="!loadingTemplates && !loadingTemplatesFailed && !templatesList.length"
        >
            <h4 class="m-0">
                {{ __('No templates being added yet.') }}
            </h4>
        </div>
        
        <!-- Templates (server-side filtered by category) -->
        <template x-for="(template, index) in templatesList.slice(0, showCount)" :key="template.id">
            <div
                x-transition
            >
                <a
                    class="lqd-cs-template group relative w-full overflow-hidden rounded-md shadow-lg shadow-black/5 transition-transform hover/item:-translate-y-1"
                    href="#"
                    @click.prevent="loadTemplate(template.id)"
                >
                    <img
                        class="group-hover/item:scale-105"
                        src="data:image/svg+xml;charset=utf-8,%3Csvg xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg' viewBox%3D'0 0 100 100'%2F%3E"
                        alt="{{ __('Creative Suite Preview') }}"
                        x-intersect.once="$el.src = $el.getAttribute('data-src')"
                        :data-src="template.preview"
                    >
                    <span class="absolute inset-0 inline-grid place-items-center bg-black/15 text-white opacity-0 transition-opacity group-hover:opacity-100">
                        <x-tabler-plus class="size-5" />
                    </span>
                    <span class="absolute bottom-0 left-0 right-0 bg-black/50 px-2 py-1 text-xs text-white" x-text="template.category"></span>
                </a>
            </div>
        </template>
        
        <!-- Show More Button -->
        <div 
            class="col-span-full flex justify-center mt-6"
            x-show="templatesList.filter(t => activeCategory === 'all' || (t.category_key && t.category_key === activeCategory) || (t.category && t.category === activeCategory)).length > showCount"
        >
            <x-button
                class="px-6 py-3"
                variant="outline"
                @click="showCount += 10"
            >
                @lang('Show More Templates')
                <x-tabler-chevron-down class="size-4 ml-1" />
            </x-button>
        </div>
    </div>
</div>
