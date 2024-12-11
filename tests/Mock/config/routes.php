<?php

use Tests\Mock\Controller\TestController;

$router->get('test', TestController::class, ['as' => 'test']);
$router->get('test/{id}', TestController::class, ['as' => 'test2']);
