<?php

try {
    $pdo = new PDO('mysql:host=db;dbname=web', 'web', 'web');
    $statement = $pdo->prepare('SELECT * FROM web.users');

    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    print_r($result);
} catch (Exception $e) {
    echo $e->getMessage();
    echo '<br/><strong>If you are seeing this - please create the missing table (or any table for that matter) this bit of code is for example purposes only.</strong>';
}
