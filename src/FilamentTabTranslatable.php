<?php

namespace KaanTanis\FilamentTabTranslatable;

use Filament\Forms\Components\Tabs;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FilamentTabTranslatable
{
    public static string|null $column = null;

    /**
     * @param string|null $column
     * @param array $components
     * @return Tabs
     */
    public static function components(array $components, string $column = null): Tabs
    {
        self::$column = $column ? $column . '.' : $column;

        return Tabs::make('translations')
            ->tabs(self::getTabs($components));
    }

    /**
     * @param $components
     * @return array
     */
    public static function getTabs($components): array
    {
        $languages = Helpers\Helper::getLangCodes();

        $tabs = [];

        foreach ($languages as $language) {
            $tabs[] = self::makeTab($language, $components);
        };

        return $tabs;
    }

    /**
     * @param $language
     * @param $component
     * @return Tabs\Tab
     */
    public static function makeTab($language, $component): Tabs\Tab
    {
        $manipulatedComponents = [];

        foreach ($component as $item) {
            $manipulatedItem = clone $item;
            $manipulatedItem->name(self::$column . $item->getName() . '.' . $language)
                ->label($item->getLabel() . ' (' . Str::upper($language) . ')')
                ->statePath(self::$column . $item->getName() . '.' . $language);

            $manipulatedComponents[] = $manipulatedItem;
        }

        return Tabs\Tab::make(Str::upper($language))
            ->schema($manipulatedComponents);
    }
}
