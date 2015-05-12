<?php

require "../vendor/autoload.php";

$db = Doctrine\DBAL\DriverManager::getConnection([
    'driver'       => 'pdo_sqlite',
    'global'       => array('memory' => true),
    'wrapperClass' => 'Reduce\Db\Connection'
]);

// $a = $db->application()
//         ->where('id = 4')
//         ->where('title = ?', 'Dibi');
//         ->where([
//             'slogan = ?' => 'massa',
//             'author_id = ?' => 10
//         ]);


// $a = $db->application[4];

// var_dump($db->application[3]);

// $a = $db->application();

// echo $a;
// var_dump($a);

// foreach ($a as $key => $value) {
//     var_dump($key, $value->id);
// }

// $b = $db->author[11]->application();

// $b = $db->author[11]->application('title = ?', 'Adminer');
// $b = $db->author[11]->application()->where('title = ?', 'Adminer');

// echo $b;
// var_dump($b->toArray());

// $c = $db->application[4]->author;
// var_dump($c);
// var_dump(json_encode($c));

$d = $db->author()->application();
echo $d;