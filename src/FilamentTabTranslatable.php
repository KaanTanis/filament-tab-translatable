<?php

namespace KaanTanis\FilamentTabTranslatable;

use Filament\Forms\Components\Tabs;
use Illuminate\Support\Str;

class FilamentTabTranslatable
{
    public static ?string $column = null;

    public static ?array $translatedTabs = [];

    public static ?array $generalTabs = [];

    public static function make(string|null $name = null)
    {
        return new static($name);
    }

    public function general(array $components)
    {
        self::$generalTabs = self::getGeneralTabs($components);

        return $this;
    }

    public static function getGeneralTabs($components): array
    {
        $tabs[] = self::makeGeneralTab($components);

        return $tabs;
    }

    public static function makeGeneralTab($components)
    {
        $manipulatedComponents = [];

        foreach ($components as $item) {
            $manipulatedItem = clone $item;
            $manipulatedItem->name($item->getName())
                ->label($item->getLabel())
                ->statePath($item->getName());

            $manipulatedComponents[] = $manipulatedItem;

            $tab_name = 'General';
        }

        return Tabs\Tab::make($tab_name)
            ->schema($manipulatedComponents);
    }

    public function translations(array $components, string $column = null)
    {
        self::$column = $column ? $column . '.' : $column;

        self::$translatedTabs = self::getTranslationsTabs($components);

        return $this;
    }

    public static function getTranslationsTabs($components): array
    {
        $languages = Helpers\Helper::getLangCodes();

        $tabs = [];

        foreach ($languages as $language) {
            $tabs[] = self::makeTranslationsTab($language, $components);
        }

        return $tabs;
    }

    public static function makeTranslationsTab($language, $component): Tabs\Tab
    {
        $manipulatedComponents = [];

        foreach ($component as $item) {
            $manipulatedItem = clone $item;
            $manipulatedItem->name(self::$column . $item->getName() . '.' . $language)
                ->label($item->getLabel() . ' (' . Str::upper($language) . ')')
                ->statePath(self::$column . $item->getName() . '.' . $language);

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

    public function render(): Tabs
    {
        return Tabs::make('tabs')
        ->schema(array_merge(self::$generalTabs, self::$translatedTabs));
    }
}
