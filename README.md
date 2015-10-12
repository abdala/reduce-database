[![Build Status](https://travis-ci.org/ReducePHP/Database.svg?branch=master)](https://travis-ci.org/ReducePHP/Database)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ReducePHP/Database/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ReducePHP/Database/?branch=master)

This library aims to reduce the number of method calls and promote a fast way of building query using Doctrine DBAL wrapper class, without breaking any DBAL implementation.


INSTALATION
=========

The recommended way to install is through Composer:

```bash
composer require reduce\db
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

SELECT
=========

```php
$db->tableName(); //SELECT * FROM tableName

```

WHERE
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

TESTS
=========

To run the test suite, you need Composer and PHPUnit:

```bash
./vendor/bin/phpunit
```

CONTRIBUTING
=========

Contributions are always welcome, please have a look at our issues to see if there's something you could help with.

LICENSE
=========

Reduce Database is licensed under MIT license.