#!/usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Domain\Commands\GenerateReportCommand;
use Symfony\Component\Console\Application;
use Domain\Commands\UploadCommand;

$app = new Application();
$app->add(new UploadCommand());
$app->add(new GenerateReportCommand());
$app->run();
