<x-filament-panels::page
    @class([
        'fi-resource-list-records-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])
>
    <div class="flex flex-col gap-y-6">
        <x-filament-panels::resources.tabs/>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes()) }}

        @foreach ($this->oss as $os)
            <x-filament::section collapsible collapsed>
                <x-slot name="heading">
                    <div class="flex items-center justify-start flex-row gap-2">
                        <span>{{$os->position}}. {{$os->name}}</span>
                        <x-filament::badge icon="tabler-target">
                            {{count($os->oos)}} Oos
                        </x-filament::badge>
                        <x-filament::button outlined
                                            href="{{ route('filament.admin.resources.strategic-objectives.view', $os->id) }}"
                                            style="z-index:1000"
                                            size="sm"
                                            color="info"
                                            icon="tabler-eye"
                                            tag="a">
                            Détails
                        </x-filament::button>
                    </div>
                </x-slot>
                <div class="flex flex-col gap-y-3">
                    @foreach ($os->oos as $oo)
                        <div class="flex items-center justify-start flex-row gap-2">
                            <a href="{{ route('filament.admin.resources.operational-objectives.view', $oo->id) }}"
                               title="Voir" class="flex items-center justify-start flex-row gap-2">
                                <span>{{$os->position}}.{{$oo->position}}</span>
                                <span>{{$oo->name}}</span>
                            </a>
                            <x-filament::badge icon="tabler-bolt">
                                {{count($oo->actions)}} actions
                            </x-filament::badge>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        @endforeach

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes()) }}
    </div>
</x-filament-panels::page>
