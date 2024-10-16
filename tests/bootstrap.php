<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\Facade;

$app = require __DIR__ . '/../bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Facade::setFacadeApplication($app);
