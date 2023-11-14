<?php

namespace KaanTanis\FilamentTabTranslatable;

use Filament\Forms\Components\Tabs;
use Illuminate\Support\Str;

class FilamentTabTranslatable
{
    protected ?string $column = null;

    protected ?array $translatableTabs = [];

    protected ?array $untranslatableTabs = [];

    public static function make()
    {
        return new self;
    }

    public function translatable(array $components, string $column = null)
    {
        $this->column = $column ? $column . '.' : $column;
        $this->translatableTabs = $this->getTranslatableTabs($components);

        return $this;
    }

    protected function getTranslatableTabs($components): array
    {
        $languages = Helpers\Helper::getLangCodes();
        $tabs = [];

        foreach ($languages as $language) {
            $tabs[] = $this->makeTranslatableTab($language, $components);
        }

        return $tabs;
    }

    protected function makeTranslatableTab($language, $components): Tabs\Tab
    {
        $manipulatedComponents = [];

        foreach ($components as $item) {
            $manipulatedItem = clone $item;
            $manipulatedItem->name($this->column . $item->getName() . '.' . $language)
                ->label($item->getLabel() . ' (' . Str::upper($language) . ')')
                ->statePath($this->column . $item->getName() . '.' . $language);

            $manipulatedComponents[] = $manipulatedItem;
        }

        return Tabs\Tab::make($this->getTabName($language))
            ->schema($manipulatedComponents);
    }

    protected function getTabName($language): string
    {
        return match (config('filament-tab-translatable.tab-type')) {
            'name' => Helpers\Helper::getLangName($language),
            default => Str::upper($language),
        };
    }

    public function untranslatable(array $components)
    {
        $this->untranslatableTabs = $this->getUntranslatableTabs($components);

        return $this;
    }

    protected function getUntranslatableTabs($components): array
    {
        $tabs[] = $this->makeUntranslatableTab($components);

        return $tabs;
    }

    protected function makeUntranslatableTab($components)
    {
        $manipulatedComponents = [];

        foreach ($components as $item) {
            $manipulatedItem = clone $item;
            $manipulatedItem->name($item->getName())
                ->label($item->getLabel())
                ->statePath($item->getName());

            $manipulatedComponents[] = $manipulatedItem;
        }

        $tab_name = __('tab_translatable.untranslatable');

        return Tabs\Tab::make($tab_name)
            ->schema($manipulatedComponents);
    }

    public function render(): Tabs
    {
        return Tabs::make('tabs')
            ->schema(array_merge($this->untranslatableTabs, $this->translatableTabs));
    }
}
