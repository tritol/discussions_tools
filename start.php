<?php
/**
 * Main file of the plugin
 */

elgg_register_event_handler('init', 'system', 'discussions_tools_init');
elgg_register_event_handler('pagesetup', 'system', 'discussions_tools_pagesetup');

/**
 * Called during system init
 *
 * @return void
 */
function discussions_tools_init() {
	
	// register widgets
	elgg_register_widget_type('start_discussion', elgg_echo('discussions_tools:widgets:start_discussion:title'), elgg_echo('discussions_tools:widgets:start_discussion:description'), ['index', 'dashboard', 'groups']);
	elgg_register_widget_type('discussion', elgg_echo('discussion:latest'), elgg_echo('discussions_tools:widgets:discussion:description'), ['index', 'dashboard'], true);
	elgg_register_widget_type('group_forum_topics', elgg_echo('discussion:group'), elgg_echo('discussions_tools:widgets:group_forum_topics:description'), ['groups']);
	
	// register plugin hooks
	elgg_register_plugin_hook_handler('register', 'menu:entity', '\ColdTrick\DiscussionsTools\EntityMenu::discussionStatus');
	elgg_register_plugin_hook_handler('entity:url', 'object', '\ColdTrick\DiscussionsTools\WidgetManager::widgetURL');
	elgg_register_plugin_hook_handler('group_tool_widgets', 'widget_manager', '\ColdTrick\DiscussionsTools\WidgetManager::groupToolWidgets');
	
	// register actions
	elgg_register_action('discussions/toggle_status', dirname(__FILE__) . '/actions/discussions/toggle_status.php');
}

function discussions_tools_pagesetup() {
	
	$page_owner = elgg_get_page_owner_entity();
	
	if ($page_owner instanceof ElggGroup) {
		if ($page_owner->forum_enable === 'no') {
			// unregister widget because the tool option is disabled
			elgg_unregister_widget_type('group_forum_topics');
		}
	}
}
