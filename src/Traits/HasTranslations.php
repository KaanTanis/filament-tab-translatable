<?php

namespace KaanTanis\FilamentTabTranslatable\Traits;

use Exception;

trait HasTranslations
{
    public function __construct()
    {
        $this->casts = array_merge($this->casts, collect($this->translatable)->mapWithKeys(fn ($item) => [$item => 'json'])->toArray());

        parent::__construct(...func_get_args());
    }

    public function getAttributeValue($key): mixed
    {
        if (!$this->isTranslatableAttribute($key)) {
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
        return is_array($this->translatable)
            ? $this->translatable
            : [];
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

    private function getValueByKey($key, $lang)
    {
        $lang = $lang ?? app()->getLocale();

        $value = json_decode($this->attributes[$key], true);

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
