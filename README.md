[![Build Status](https://travis-ci.org/abdala/reduce-database.svg?branch=master)](https://travis-ci.org/abdala/reduce-database)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/abdala/reduce-database/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/abdala/reduce-database/?branch=master)

This library aims to reduce the number of method calls and promote a fast way of building query using Doctrine DBAL wrapper class, without breaking any DBAL implementation. It was inspired by NotORM.


Instalation
=========

The recommended way to install is through Composer:

```bash
composer require abdala/reduce-database
```

Usage
==========

Only set **Reduce\Db\Connection** as **wrapperClass** and that's it, you can start use it.

```php
$db = Doctrine\DBAL\DriverManager::getConnection([
    'driver'       => 'pdo_sqlite',
    'global'       => array('memory' => true),
    'wrapperClass' => 'Reduce\Db\Connection'
]);
```

Select
=========

```php
$db->tableName(); //SELECT * FROM tableName

```

Where
=========

```php
$db->tableName[3]; //SELECT * FROM tableName WHERE id = ?

```

```php
$db->tableName('name', 'Jose'); //SELECT * FROM tableName WHERE name = ?

```

```php
$db->tableName('id > ?', 3); //SELECT * FROM tableName WHERE id > ?

```

```php
$db->tableName([
    'name' => 'Jose', 
    'id > ?' => 3
]); //SELECT * FROM tableName WHERE name = ? AND id > ?

```

Tests
=========

To run the test suite, you need Composer and PHPUnit:

```bash
./vendor/bin/phpunit
```

Contributing
=========

Contributions are always welcome, please have a look at our issues to see if there's something you could help with.

License
=========

Reduce Database is licensed under MIT license.
