<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Test BDD</title>
    <link rel="stylesheet" href="test_bdd.css">
  </head>
  <body>
    <?php
    require 'BDD.php';
    $test = new Model();
    //$test->get_php_info();
    //$test->get_available_drivers();
    $test->connect();
    $test->ask_db("SELECT * FROM Auteur");
    $test->disconnect();
    ?>
  </body>
</html>
