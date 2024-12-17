<?php

use MVC\core\app;

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__) . DS . 'imperative' . DS);
define('APP', ROOT . 'app' . DS);
define('CONTROLLERS', APP . 'controllers' . DS);
define('CORE', APP . 'core' . DS);
define('MODELS', APP . 'models' . DS);
define('VIEWS', APP . 'views' . DS);
//define('LINK', 'http://localhost/restaurant-mvc/app/views/css/');

require_once('vendor/autoload.php');
$app = new app;
