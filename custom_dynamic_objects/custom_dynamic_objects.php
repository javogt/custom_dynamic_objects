<?php
/**
 * @package Custom Objects
 * @version 0.0.0
 */
/*
Plugin Name: Custom Dynamic Objects
Description: Plugin to creat custom dynamic objects
Author: Jakob Andreas Vogt
Version: 0.0.0
Author URI:
 */ 

require_once('vendor/autoload.php');

use CustomDynamicObjects\WordpressConnector;
use CustomDynamicObjects\Jsons;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

global $wpdb;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $wpdb->dbhost,
    'database'  => $wpdb->dbname,
    'username'  => $wpdb->dbuser,
    'password'  => $wpdb->dbpassword,
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => $wpdb->prefix
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
date_default_timezone_set('UTC');

$customDynamicObjects = new CustomDynamicObjects(
		new WordpressConnector(), 
		new Jsons(__DIR__ . '/objects'),
		$capsule
	);

$customDynamicObjects->createBackend();
// $customDynamicObjects->migrate();

?>
