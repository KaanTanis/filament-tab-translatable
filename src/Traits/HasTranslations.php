<?php

namespace KaanTanis\FilamentTabTranslatable\Traits;

use Exception;

trait HasTranslations
{
    /**
     * @throws Exception
     */
    public function translate($key, $lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        try {
            return $this->getValueByKey($key, $lang);
        } catch (Exception $e) {
            throw new Exception('FilamentTranslatable: ' . $e->getMessage());
        }
    }

    private function getValueByKey($key, $lang)
    {
        $keys = explode('.', $key);
        $value = $this;

        if (count($keys) === 1) {
            return $this[$keys[0]] ?? null;
        }

        foreach ($keys as $subKey) {
            if (! isset($value[$subKey])) {
                return null;
            }

            $value = $value[$subKey];
        }

        return $this->getValueForLanguage($value, $lang);
    }

    private function getValueForLanguage($value, $lang)
    {
        $fallbackLang = config('app.locale');
        $valueForLang = $value[$lang] ?? null;
        $valueForFallbackLang = $value[$fallbackLang] ?? null;

        return $valueForLang ?? $valueForFallbackLang;
    }
}
