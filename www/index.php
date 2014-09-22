<?php

// uncomment next line for temporarily take down your site for maintenance
//require '.maintenance.php';

$container = require __DIR__ . '/../app/bootstrap.php';

$container->getService('application')->run();
