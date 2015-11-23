<?php

namespace CustomDynamicObjects;

class WordpressConnector{

	public function add_action($hook, $function_to_add, $priority = null, $accepted_args = null){
		add_action($hook, $function_to_add, $priority, $accepted_args);
	}

	public function add_meta_box($id, $title, $callback, $screen = null, $context = null, $priority = null, $callback_args = null){
		 add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args);
	}

}