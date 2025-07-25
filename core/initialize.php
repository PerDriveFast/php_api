<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__DIR__)); // lên 1 cấp từ /core

defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT . DS . 'includes' . DS);
defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT . DS . 'core' . DS);

// Load configuration file
require_once(INC_PATH . DS . 'config.php');
require_once(CORE_PATH . DS . 'post.php');
