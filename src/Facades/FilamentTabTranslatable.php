<?php

namespace KaanTanis\FilamentTabTranslatable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KaanTanis\FilamentTabTranslatable\FilamentTabTranslatable
 */
class FilamentTabTranslatable extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \KaanTanis\FilamentTabTranslatable\FilamentTabTranslatable::class;
    }
}
