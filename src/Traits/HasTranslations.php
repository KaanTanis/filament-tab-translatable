<?php

namespace KaanTanis\FilamentTabTranslatable\Traits;

use Exception;

trait HasTranslations
{
    public function __construct()
    {
        $this->casts = array_merge($this->casts, collect($this->translatable)
            ->mapWithKeys(fn ($item) => [$item => 'json'])
            ->toArray());

        parent::__construct(...func_get_args());
    }

    /**
     * @throws Exception
     */
    public function getAttributeValue($key): mixed
    {
        if (! $this->isTranslatableAttribute($key)) {
            return parent::getAttributeValue($key);
        }

        return $this->translate($key);
    }

    public function isTranslatableAttribute(string $key): bool
    {
        return in_array($key, $this->getTranslatableAttributes());
    }

    public function getTranslatableAttributes(): array
    {
        return $this->translatable ?? [];
    }

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

    /**
     * @throws Exception
     */
    public function tt()
    {
        return $this->translate(...func_get_args());
    }

    private function getValueByKey($key, $lang)
    {
        $lang = $lang ?? app()->getLocale();

        if (str_contains($key, '.')) {
            $keys = explode('.', $key);

            $value = json_decode($this->attributes[$keys[0]], true);

            foreach (array_slice($keys, 1) as $nestedKey) {
                if (is_array($value) && array_key_exists($nestedKey, $value)) {
                    $value = $value[$nestedKey];
                } else {
                    return null;
                }
            }

            return $this->getValueForLanguage($value, $lang);
        }

        $value = json_decode($this->attributes[$key], true);

        return $this->getValueForLanguage($value, $lang);
    }

    private function getValueForLanguage($value, $lang)
    {
        // @fixme
        return $value[$lang]
            ?? $value[config('app.locale')]
            ?? $value[config('app.fallback_locale')]
            ?? null;
    }
}
