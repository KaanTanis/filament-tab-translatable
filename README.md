# filament-tab-translatable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kaantanis/filament-tab-translatable.svg?style=flat-square)](https://packagist.org/packages/kaantanis/filament-tab-translatable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/kaantanis/filament-tab-translatable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kaantanis/filament-tab-translatable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/kaantanis/filament-tab-translatable/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/kaantanis/filament-tab-translatable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kaantanis/filament-tab-translatable.svg?style=flat-square)](https://packagist.org/packages/kaantanis/filament-tab-translatable)


This package may not be as comprehensive and useful as a Spatie package. 
The purpose of this package is to store array data here if certain database 
columns are in JSON format. While doing this, the Tab component 
of Filament is used. A given key automatically gets separated 
according to languages. So, if the "title" key is sent, it 
returns with the existing languages as follows via tabs: "title.en, title.tr."

![Screenshot](https://raw.githubusercontent.com/KaanTanis/filament-tab-translatable/main/art/screen.png)

## Installation

You can install the package via composer:

```bash
composer require kaantanis/filament-tab-translatable
```

You can publish the config file with: (If not automatically published)

```bash
php artisan vendor:publish --tag="filament-tab-translatable-config"
```

You can add new languages to the config file

This is the contents of the published config file:

```php
return [
    'default' => 'en', // for first tab

    'laravellocalization' => false, // if you use mcamara/laravel-localization package

    'tab-type' => 'code', // code or name (code: tr, en, name: Türkçe, English) default: code

    'list' => [
        'tr' => [
            'name' => 'Turkish',
            'native_name' => 'Türkçe',
            'code_upper' => 'TR',
            'code_lower' => 'tr',
            'flag' => 'tr',
        ],

        'en' => [
            'name' => 'English',
            'native_name' => 'English',
            'code_upper' => 'EN',
            'code_lower' => 'en',
            'flag' => 'gb',
        ],
    ],
];
```

## Usage

```php
FilamentTabTranslatable::make()
    ->untranslatable([
        TextInput::make('category')
            ->required(),
        FileUpload::make('image')
            ->image()
            ->directory('posts')
            ->required(),
    ])
    ->translatable([
        TextInput::make('title')
            ->required(),
        TextInput::make('slug')
            ->required(),
        Textarea::make('body')
            ->required(),
    ], 'columnName')
    ->render() // columnName for nested, not required if you want to use same key (columnName->anotherColumnName)

// IMPORTANT: render() method is required for render the component
// untranslatable() method is for non-translated columns
// IMPORTANT: Make sure field type is json in database 
// The second parameter 'columnName' is only for translated columns. Not required if the column name is the same as the key.
```
### Render type
```php
    ->render(:contained: false) // default true
// More details: https://filamentphp.com/docs/3.x/infolists/layout/tabs#removing-the-styled-container
````

## Example

```php
// Open Your Model and add trait
use KaanTanis\FilamentTabTranslatable\Traits\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public $translatable = ['title', 'body', 'slug']; // for $model->title etc. access
}

// You ready to go
```

```php

// You can call directly
$post->title; // returns title of app()->getLocale() language
// IMPORTANT: Not working with nested json fields. Don't forget to define columns for public $translatable = [...]

// if you want to get specific language
$post->translate('columnName.title', 'en'); // returns title of en language
$post->translate('columnName.title', 'tr'); // returns title of tr language
$post->translate('columnName.title'); // returns title of app()->getLocale() language

// if second parameter is not given, it returns directly by key
$post->translate('description'); // not nested

// or shortly
$post->tt('title');
```

# Developer notes
This package, as I mentioned before, is not as comprehensive as Spatie and 
most likely has some issues. I will continue to develop this package and make 
it better, and during this process, my friends who would like to support 
the development of this package can contribute without hesitation. 
Filament needs a package like this. Thank you.

# What need?
- [ ] Tests
- [ ] Better documentation
- [ ] Access to nested json fields with attribute. Eg. $post->fields->title or $post->fields['title]

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [KaanTanis](https://github.com/KaanTanis)
- [afsakar](https://github.com/afsakar)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
