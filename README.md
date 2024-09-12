# Manage the laravel eloquent model relationships, table columns and column types

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oobook/manage-eloquent.svg?style=flat-square)](https://packagist.org/packages/oobook/manage-eloquent)
[![Total Downloads](https://img.shields.io/packagist/dt/oobook/manage-eloquent.svg?style=flat-square)](https://packagist.org/packages/oobook/manage-eloquent)
![GitHub Actions](https://github.com/oobook/manage-eloquent/actions/workflows/main.yml/badge.svg)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/oobook/manage-eloquent/run-php-tests?label=Tests)


This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require oobook/manage-eloquent
```

## Usage

You must define return types of relationships in order to use **definedRelationships()** method, \Illuminate\Database\Eloquent\Relations\BelongsTo i.e. as following.

```php
<?php

namespace App\Models;

use Oobook\Database\Eloquent\Concerns\ManageEloquent;

class Product extends Model
{
    use ManageEloquent;

    protected $fillable = [
		'name'
	];


    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(
            Tag::class,
            'taggable'
        );
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(
            Category::class,
        );
    }

}
```

#### Examples

```php
<?php

$product = new Product();

// RELATIONSHIPS
// get all defined Relationships
$product->definedRelationships(); // ['tags', 'category']
// get relationships by relationship type 
$product->definedRelations('BelongsTo'), // ['category'];
$product->definedRelations(['BelongsTo', 'MorphToMany']), // ['tags', 'category'];

// get relation types
$product->definedRelationsTypes(), 
// [
//     "category" => "BelongsTo"
//     "tags" => "MorphToMany"
// ]

$product->hasRelation('tags') // true;
$product->getRelationType('tags') // \Illuminate\Database\Eloquent\Relations\MorphToMany;

// COLUMNS
$product->hasColumn('name') // true if exists on db table;
$product->getColumns() // get the list of columns of the table;

// get the list of timestamp columns of the table;
$product->getTimestampColumns() 

```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email oguz.bukcuoglu@gmail.com instead of using the issue tracker.

## Credits

-   [Oğuzhan Bükçüoğlu](https://github.com/oobook)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
