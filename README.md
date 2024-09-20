# Manage the laravel eloquent model relationships, table columns and column types

[![Main](https://img.shields.io/github/actions/workflow/status/oobook/manage-eloquent/tests.yml?label=tests&logo=github-actions)](https://github.com/oobook/manage-eloquent/actions?workflow=tests)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/oobook/manage-eloquent.svg?style=flat-square&logo=packagist)](https://packagist.org/packages/oobook/manage-eloquent)
[![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/oobook/manage-eloquent?label=release&logo=GitHub)](https://github.com/oobook/manage-eloquent/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/oobook/manage-eloquent.svg?style=flat-square)](https://packagist.org/packages/oobook/manage-eloquent)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/oobook/manage-eloquent/tests?label=tests)

<!-- <p align="center">
![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/oobook/manage-eloquent/run-tests)
<a href="https://github.com/oobook/automated-tag/actions"><img src="https://github.com/oobook/automated-tag/workflows/main/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/oobook/post-redirector"><img src="https://img.shields.io/packagist/dt/oobook/post-redirector" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/oobook/post-redirector"><img src="https://img.shields.io/packagist/v/oobook/post-redirector" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/oobook/post-redirector"><img src="https://img.shields.io/packagist/l/oobook/post-redirector" alt="License"></a> 
</p> -->


This package will help out you to manage the laravel eloquent model relationships, table columns and column types.

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
        return $this->belongsTo(Category::class);
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
