# Datatables for Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A package for Laravel that integrates Eloquent queries and Illuminate Collections into Datatables for JQuery.

## Structure

```
src/
tests/
```


## Install

Via Composer

``` bash
$ composer require markese/datatables
```

## Usage

``` php
// Laravel:
public function datatablesExample (Request $request)
{
    $users = Users::with('groups.roles');
    $response = \Markese\Datatables::response($users, $request);
    return $response->toJson();
}
```
``` js
// Datatables:
$('#example').DataTable( {
    "serverSide": true,
    "processing": true,
    "ajax": "datatablesexample",
    columns : [
        { "data": "id", "title": "Id", "name": "id" },
        { "data": "name", "title": "Name", "name": "name" },
        { "data": "email", "title": "Number", "name": "email" },
        { "data": "groups[].name", "title": "Groups", "name": "groups.*.name" },
        { "data": "groups[].roles[].title", "title": "Roles", "name": "groups.*.roles.*.title" }
    ]
} );
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email john.markese@gmail.com instead of using the issue tracker.

## Credits

- [John Markese][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/jmarkese/datatables.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jmarkese/datatables/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jmarkese/datatables.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jmarkese/datatables.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jmarkese/datatables.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jmarkese/datatables
[link-travis]: https://travis-ci.org/jmarkese/datatables
[link-scrutinizer]: https://scrutinizer-ci.com/g/jmarkese/datatables/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/jmarkese/datatables
[link-downloads]: https://packagist.org/packages/jmarkese/datatables
[link-author]: https://github.com/jmarkese
[link-contributors]: ../../contributors
