<?php

use \Silex\Application;

/**
 * Bootstrap.
 *
 * PHP version 5.6
 *
 * @author jfranciscos4 <silvaivctd@gmail.com>
 * @copyright (c) 2007/2017, Grupo BRA - Solucoes para Gestao Publica
 *
 * @version 1.0.0
 */

/**
 * @var Application Description
 */
$app = require_once __DIR__.DIRECTORY_SEPARATOR.'bootstrap.php';

$app->run();
