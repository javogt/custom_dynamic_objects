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
require_once('config.php');
require_once('classes/admin.php');
require_once('classes/fileSystem.php');
require_once('classes/database.php');

global $wpdb;
$cdo_Database = new CustomDynamicObjectDatabase($wpdb);
$cdo_FileSystem = new CustomDynamicObjectFileSystem($cdo_config['jsonsPath']);
$cdo_Admin = new CustomDynamicObjectsAdmin($cdo_FileSystem);


function activate_custom_dynamic_objects() {

};

add_action(
	'activate_custom_dynamic_objects/custom_dynamic_objects.php',
	'activate_custom_dynamic_objects'
);

add_action('add_meta_boxes', array($cdo_Admin, 'addingObjectTypeMetaBox'));

?>
