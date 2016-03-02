<?php
/**
 * Forum topic entity view
 *
 * ColdTrick
 * - added icon before title to show discussion status (closed)
 * - removed duplicate title in full view
 */

$full = elgg_extract('full_view', $vars, FALSE);
$topic = elgg_extract('entity', $vars, FALSE);

if (!$topic) {
	return;
}

$poster = $topic->getOwnerEntity();
if (!$poster) {
	elgg_log("User {$topic->owner_guid} could not be loaded, and is needed to display entity {$topic->guid}", 'WARNING');
	if ($full) {
		forward('', '404');
	}
	return;
}

$excerpt = elgg_get_excerpt($topic->description);

$poster_icon = elgg_view_entity_icon($poster, 'tiny');

$by_line = elgg_view('page/elements/by_line', $vars);

$tags = elgg_view('output/tags', array('tags' => $topic->tags));

$replies_link = '';
$reply_text = '';

$num_replies = elgg_get_entities(array(
	'type' => 'object',
	'subtype' => 'discussion_reply',
	'container_guid' => $topic->getGUID(),
	'count' => true,
	'distinct' => false,
));

if ($num_replies != 0) {
	$last_reply = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'discussion_reply',
		'container_guid' => $topic->getGUID(),
		'limit' => 1,
		'distinct' => false,
	));
	if (isset($last_reply[0])) {
		$last_reply = $last_reply[0];
	}

	$poster = $last_reply->getOwnerEntity();
	$reply_time = elgg_view_friendly_time($last_reply->time_created);
	$reply_text = elgg_echo('discussion:updated', array($poster->name, $reply_time));

	$replies_link = elgg_view('output/url', array(
		'href' => $topic->getURL() . '#group-replies',
		'text' => elgg_echo('discussion:replies') . " ($num_replies)",
		'is_trusted' => true,
	));
}

// do not show the metadata and controls in widget view
$metadata = '';
if (!elgg_in_context('widgets')) {
	// only show entity menu outside of widgets
	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'discussion',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}

if ($full) {
	$subtitle = "$by_line $replies_link";

	$params = array(
		'entity' => $topic,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	$info = elgg_view_image_block($poster_icon, $list_body);

	$body = elgg_view('output/longtext', array(
		'value' => $topic->description,
		'class' => 'clearfix',
	));

	echo <<<HTML
$info
$body
HTML;

} else {
	// show status indicator
	$title = '';
	if ($topic->status == 'closed') {
		$title .= elgg_format_element('span', [
			'title' => elgg_echo('discussion:topic:closed:title'),
			'class' => 'mrs',
		], elgg_view_icon('lock-closed'));
	}
	$title .= elgg_view('output/url', [
		'text' => $topic->title,
		'href' => $topic->getURL(),
		'is_trusted' => true,
	]);
	
	// brief view
	$subtitle = "$by_line $replies_link <span class=\"float-alt\">$reply_text</span>";

	$params = array(
		'entity' => $topic,
		'title' => $title,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($poster_icon, $list_body);
}
