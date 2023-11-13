<?php

namespace KaanTanis\FilamentTabTranslatable;

use Filament\Forms\Components\Tabs;
use Illuminate\Support\Str;

class FilamentTabTranslatable
{
    public ?string $column = null;

    public ?array $translatableTabs = [];

    public ?array $untranslatableTabs = [];

    public static function make()
    {
        return new static;
    }

    /** Translatable fields */
    public function translatable(array $components, string $column = null)
    {
        $this->column = $column ? $column . '.' : $column;

        $this->translatableTabs = $this->getTranslatableTabs($components);

        return $this;
    }

    public function getTranslatableTabs($components): array
    {
        $languages = Helpers\Helper::getLangCodes();

        $tabs = [];

        foreach ($languages as $language) {
            $tabs[] = $this->makeTranslatableTab($language, $components);
        }

        return $tabs;
    }

    public function makeTranslatableTab($language, $components): Tabs\Tab
    {
        $manipulatedComponents = [];

        foreach ($components as $item) {
            $manipulatedItem = clone $item;
            $manipulatedItem->name($this->column . $item->getName() . '.' . $language)
                ->label($item->getLabel() . ' (' . Str::upper($language) . ')')
                ->statePath($this->column . $item->getName() . '.' . $language);

            $manipulatedComponents[] = $manipulatedItem;

            $tab_name = '';

            if (config('filament-tab-translatable.tabs-type') == 'name') {
                $tab_name = Helpers\Helper::getLangName($language);
            } else {
                $tab_name = Str::upper($language);
            }
        }

        return Tabs\Tab::make($tab_name)
            ->schema($manipulatedComponents);
    }

    /** Untranslatable fields */
    public function untranslatable(array $components)
    {
        $this->untranslatableTabs = $this->getUntranslatableTabs($components);

        return $this;
    }

    public function getUntranslatableTabs($components): array
    {
        $tabs[] = $this->makeUntranslatableTab($components);

        return $tabs;
    }

    public static function makeUntranslatableTab($components)
    {
        $manipulatedComponents = [];

        foreach ($components as $item) {
            $manipulatedItem = clone $item;
            $manipulatedItem->name($item->getName())
                ->label($item->getLabel())
                ->statePath($item->getName());

            $manipulatedComponents[] = $manipulatedItem;

            $tab_name = __('tab_translatable.untranslatable');
        }

        return Tabs\Tab::make($tab_name)
            ->schema($manipulatedComponents);
    }

    public function render(): Tabs
    {
        return Tabs::make('tabs')
            ->schema(array_merge($this->untranslatableTabs, $this->translatableTabs));
    }
}
