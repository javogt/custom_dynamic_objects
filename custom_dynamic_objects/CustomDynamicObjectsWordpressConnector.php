<?php

class CustomDynamicObjectsWordpressConnector{

	public function add_action($hook, $function_to_add){
		add_action($hook, $function_to_add);
	}

}