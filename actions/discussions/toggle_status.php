<?php
/**
 * Quickly open/close a discussion
 */

$guid = (int) get_input('guid');
if (empty($guid)) {
	register_error(elgg_echo('error:missing_data'));
	forward(REFERER);
}

elgg_entity_gatekeeper($guid, 'object', 'discussion');
$entity = get_entity($guid);
if (!$entity->canEdit()) {
	register_error(elgg_echo('actionunauthorized'));
	forward(REFERER);
}

if ($entity->status === 'closed') {
	$entity->status = 'open';
	
	system_message(elgg_echo('discussions_tools:action:discussions:toggle_status:success:open'));
} else {
	$entity->status = 'closed';
	
	system_message(elgg_echo('discussions_tools:action:discussions:toggle_status:success:close'));
}

forward(REFERER);
