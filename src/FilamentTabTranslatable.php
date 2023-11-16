<?php

namespace KaanTanis\FilamentTabTranslatable;

use Filament\Forms\Components\Tabs;
use Illuminate\Support\Str;

class FilamentTabTranslatable
{
    protected ?string $column = null;

    protected ?array $translatableTabs = [];

    protected ?array $untranslatableTabs = [];

    public static function make(): FilamentTabTranslatable
    {
        return new self;
    }

    public function translatable(array $components, string $column = null): static
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

    public function untranslatable(array $components, string $column = null): static
    {
        $this->column = $column ? $column . '.' : $column;
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
            $manipulatedItem->name($this->column . $item->getName())
                ->label($item->getLabel())
                ->statePath($this->column . $item->getName());

            $manipulatedComponents[] = $manipulatedItem;
        }

        $tab_name = __('tab_translatable.untranslatable');

        return Tabs\Tab::make($tab_name)
            ->schema($manipulatedComponents);
    }

    public function render($contained = true): Tabs
    {
        return Tabs::make('tabs')
            ->contained($contained)
            ->schema(array_merge($this->untranslatableTabs, $this->translatableTabs));
    }

    // todo: this methods will be used in future. Create another class for this methods
    public function setLocale($locale = null)
    {
        $locale = request()->segment(1);

        return $this->isSupported($locale);
    }

    protected function getForcedLocale()
    {
        return config('filament-tab-translatable.default');
    }

    protected function isSupported($locale)
    {
        return in_array($locale, Helpers\Helper::getLangCodes(reorder: false))
            ? $locale
            : null;
    }
}
