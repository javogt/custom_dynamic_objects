<?php

/**
 * @package Custom Objects
 * @version 0.0.0
 */

/*
Plugin Name: Dynamic Custom Objects
Description: Plugin to create dynamic custom objects
Author: Jakob Andreas Vogt
Version: 0.0.0
Author URI:
*/

require_once ('vendor/autoload.php');

use CustomDynamicObjects\WordpressConnector;
use CustomDynamicObjects\Jsons;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$customDynamicObjects = new CustomDynamicObjects(new WordpressConnector() , new Jsons(__DIR__ . '/customObjects') , new Capsule());

global $wpdb;
$customDynamicObjects->addConnection($wpdb);
$customDynamicObjects->createBackend();
// $customDynamicObjects->migrate();
?>