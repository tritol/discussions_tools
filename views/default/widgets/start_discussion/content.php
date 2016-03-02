<?php
/**
 * quickly start a discussion
 */

$widget = elgg_extract('entity', $vars);
$embed = (bool) elgg_extract('embed', $vars, false);

// check if logged if
$user = elgg_get_logged_in_user_entity();
if (empty($user)) {
	if (!$embed) {
		// you have to be logged in in order to use this widget
		echo elgg_view('output/longtext', [
			'value' => elgg_echo('discussions_tools:widgets:start_discussion:login_required'),
		]);
	}
	return;
}

// check if member of a group
$group_membership = $user->getGroups(['limit' => false]);
if (empty($group_membership)) {
	if (!$embed) {
		// you must join a group in order to use this widget
		$link_start = '<a href="' . elgg_get_site_url() . '/groups/all">';
		$link_end = '</a>';
	
		$text = elgg_echo('discussions_tools:widgets:start_discussion:membership_required', [$link_start, $link_end]);
		echo elgg_view('output/longtext', [
			'value' => $text,
		]);
	}
	return;
}

$owner = $widget->getOwnerEntity();
$selected_group = ELGG_ENTITIES_ANY_VALUE;
if (($owner instanceof ElggGroup) && ($owner->forum_enable !== 'no')) {
	// preselect the current group
	$selected_group = $owner->getGUID();
}

$group_selection_options = [];
$group_access_options = [];

if (empty($selected_group)) {
	// no group container, so add empty record, so a user is required to select a group (instead of defaulting to the first option)
	$group_selection_options[] = '';
	$group_access_options['-1'] = '';
}

foreach ($group_membership as $group) {
	
	// does the group have discussions disabled
	if ($group->forum_enable === 'no') {
		continue;
	}
	
	$group_selection_options[$group->getGUID()] = $group->name;
	$group_access_options[$group->group_acl] = $group->getGUID();
}

if ((empty($selected_group) && (count($group_selection_options) === 1)) || (!empty($selected_group) && empty($group_selection_options))) {
	// non of your groups have discussions enabled
	if (!$embed) {
		echo elgg_view('output/longtext', [
			'value' => elgg_echo('discussions_tools:widgets:start_discussion:not_enabled'),
		]);
	}
	return;
}

// sort the groups by name
natcasesort($group_selection_options);

$form_vars = [
	'id' => 'discussions-tools-start-discussion-widget-form',
	'action' => 'action/discussion/save',
];
$body_vars = [
	'groups' => $group_selection_options,
	'access' => $group_access_options,
	'container_guid' => $selected_group,
];

echo elgg_view_form('discussions_tools/quick_start', $form_vars, $body_vars);

echo elgg_format_element('script', ['type' => 'text/javascript'], 'require(["discussions_tools/start_discussion"]);');
