<?php
(@include_once __DIR__ . '/../vendor/autoload.php') || @include_once __DIR__ . '/../../../autoload.php';

if (!isset($_REQUEST["s"])) {
    die("Empty url request");
}
\Eslider\PlantUMLProxy::render($_REQUEST["s"]);


