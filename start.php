<?php
/**
 * Main file of the plugin
 */

elgg_register_event_handler('init', 'system', 'discussions_tools_init');

/**
 * Called during system init
 *
 * @return void
 */
function discussions_tools_init() {
	
	// register plugin hooks
	elgg_register_plugin_hook_handler('register', 'menu:entity', '\ColdTrick\DiscussionsTools\EntityMenu::discussionStatus');
	
	// register actions
	elgg_register_action('discussions/toggle_status', dirname(__FILE__) . '/actions/discussions/toggle_status.php');
}
