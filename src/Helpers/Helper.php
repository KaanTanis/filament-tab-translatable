<?php

namespace KaanTanis\FilamentTabTranslatable\Helpers;

use Illuminate\Support\Str;

class Helper
{
    public static function getLangs(): array
    {
        $langs = [];

        if (config('filament-tab-translatable.laravellocalization')) {
            $langs = collect(config('laravellocalization.supportedLocales'))->mapWithKeys(function ($item, $key) {
                return [$key => [
                    'name' => $item['name'],
                    'native_name' => $item['native'],
                    'code_upper' => Str::upper($key),
                    'code_lower' => $key,
                    'flag' => $key == 'en' ? 'gb' : $key,
                ]];
            })->toArray();
        } else {
            $langs = config('filament-tab-translatable.list');
        }

        return $langs;
    }

    public static function getLangCodes(): array
    {
        $langs = self::getLangs();

        $array = [];

        foreach ($langs as $lang) {
            $array[] = $lang['code_lower'];
        }

        if (config('filament-tab-translatable.laravellocalization') && config('laravellocalization.localesOrder') != null) {
            $array = config('laravellocalization.localesOrder');
        }

        // reorder array by default lang
        $defaultLang = self::defaultLang();
        $defaultLangIndex = array_search($defaultLang, $array);
        unset($array[$defaultLangIndex]);
        array_unshift($array, $defaultLang);

        return $array;
    }

    public static function defaultLang()
    {
        return config('filament-tab-translatable.default');
    }

    public static function getLangName($lang)
    {
        $langs = self::getLangs();

        return $langs[$lang]['native_name'];
    }
}
