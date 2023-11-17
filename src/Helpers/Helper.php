<?php

namespace KaanTanis\FilamentTabTranslatable\Helpers;

use Exception;
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
            // if config is not published, maybe error @fixme
            $langs = config('filament-tab-translatable.languages');
        }

        return $langs;
    }

    /**
     * @throws Exception
     */
    public static function getLangCodes($reorder = true): array
    {
        $langs = self::getLangs();

        $array = [];

        foreach ($langs as $lang) {
            $array[] = $lang['code_lower'];
        }

        if (config('filament-tab-translatable.laravellocalization') && config('laravellocalization.localesOrder') != null) {
            $array = config('laravellocalization.localesOrder');
        }

        if (! $reorder) {
            return $array;
        }
        // reorder array by default lang
        $defaultLang = self::defaultLang();
        $defaultLangIndex = array_search($defaultLang, $array);
        unset($array[$defaultLangIndex]);
        array_unshift($array, $defaultLang);

        return $array;
    }

    /**
     * @throws Exception
     */
    public static function defaultLang()
    {
        return in_array(config('filament-tab-translatable.default'), self::getLangCodes(reorder: false))
            ? config('filament-tab-translatable.default')
            : throw new Exception('Default language not found in config/filament-tab-translatable.php');
    }

    public static function getLangName($lang)
    {
        $langs = self::getLangs();

        return $langs[$lang]['native_name'];
    }
}
