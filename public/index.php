<?php

require_once __DIR__.'/../vendor/autoload.php';

use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__.'/../config.php');
$container = $containerBuilder->build();


?>


<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Phone Validator</title>
    </head>
    <body>
        
        <h1>Phone Validator</h1>
        <?php
        // put your code here
        ?>
    </body>
</html>
