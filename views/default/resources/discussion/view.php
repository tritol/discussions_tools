<?php
/**
 * ColdTrick
 * - added icon before title to show discussion status (closed)
 */

$guid = elgg_extract('guid', $vars);

// We now have RSS on topics
global $autofeed;
$autofeed = true;

elgg_entity_gatekeeper($guid, 'object', 'discussion');

$topic = get_entity($guid);

$container = $topic->getContainerEntity();

elgg_load_js('elgg.discussion');

elgg_set_page_owner_guid($container->getGUID());

elgg_group_gatekeeper();

if ($container instanceof ElggGroup) {
	$owner_url = "discussion/group/$container->guid";
} else {
	$owner_url = "discussion/owner/$container->guid";
}

elgg_push_breadcrumb($container->getDisplayName(), $owner_url);
elgg_push_breadcrumb($topic->title);

$params = array(
	'topic' => $topic,
	'show_add_form' => false,
);

$content = elgg_view_entity($topic, array('full_view' => true));
if ($topic->status == 'closed') {
	$content .= elgg_view('discussion/replies', $params);
	$content .= elgg_view('discussion/closed');
} elseif (elgg_instanceof($container, 'group')) {
	// Allow only group members to reply to a discussion within a group
	if ($container->canWriteToContainer(0, 'object', 'discussion')) {
		$params['show_add_form'] = true;
	}
	$content .= elgg_view('discussion/replies', $params);
} else {
	$params['show_add_form'] = true;
	$content .= elgg_view('discussion/replies', $params);
}

$title = '';
if ($topic->status == 'closed') {
	$title .= elgg_format_element('span', [
		'title' => elgg_echo('discussion:topic:closed:title'),
		'class' => 'mrs',
	], elgg_view_icon('lock-closed'));
}
$title .= $topic->title;

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($topic->title, $body);
