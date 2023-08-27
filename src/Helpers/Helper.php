<?php

namespace KaanTanis\FilamentTabTranslatable\Helpers;

class Helper
{
    /**
     * @return array
     */
    public static function getLangCodes(): array
    {
        $langs = config('filament-tab-translatable.list');

        $array = [];

        foreach ($langs as $lang) {
            $array[] = $lang['code_lower'];
        }

        // reorder array by default lang
        $defaultLang = config('filament-tab-translatable.default');
        $defaultLangIndex = array_search($defaultLang, $array);
        unset($array[$defaultLangIndex]);
        array_unshift($array, $defaultLang);

        return $array;
    }

    public static function defaultLang()
    {
        return config('filament-tab-translatable.default');
    }
}
