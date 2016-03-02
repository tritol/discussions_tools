<?php

$group_selection_options = elgg_extract('groups', $vars);
$group_access_options = elgg_extract('access', $vars);
$selected_group = elgg_extract('container_guid', $vars, ELGG_ENTITIES_ANY_VALUE);

global $quick_start_form_count;
if (empty($quick_start_form_count)) {
	$quick_start_form_count = 1;
} else {
	$quick_start_form_count++;
}

// show a button to expend the form
echo elgg_view('output/url', [
	'text' => elgg_echo('discussion:add'),
	'href' => "#discussions-tools-discussion-quick-start-wrapper-{$quick_start_form_count}",
	'is_trusted' => true,
	'rel' => 'toggle',
	'class' => 'elgg-button elgg-button-action',
]);

// start the form
echo "<div id='discussions-tools-discussion-quick-start-wrapper-{$quick_start_form_count}' class='hidden'>";

// group selector
echo '<div>';
echo elgg_format_element('label', [
	'for' => 'discussions-tools-discussion-quick-start-group',
], elgg_echo('discussions_tools:forms:discussion:quick_start:group'));
echo '<br />';
echo elgg_view('input/select', [
	'name' => 'container_guid',
	'options_values' => $group_selection_options,
	'value' => $selected_group,
	'id' => 'discussions-tools-discussion-quick-start-group',
]);
echo '</div>';

// hidden group access selector
echo '<div class="hidden">';
echo elgg_format_element('label', [
	'for' => 'discussions-tools-discussion-quick-start-access_id',
], elgg_echo('access'));
echo '<br />';
echo elgg_view('input/select', [
	'name' => 'access_id',
	'options_values' => $group_access_options,
	'id' => 'discussions-tools-discussion-quick-start-access_id',
]);
echo '</div>';

// title
echo '<div>';
echo elgg_format_element('label', [
	'for' => 'discussions-tools-discussion-quick-start-title',
], elgg_echo('title'));
echo elgg_view('input/text', [
	'name' => 'title',
	'id' => 'discussions-tools-discussion-quick-start-title',
	'required' => true,
]);
echo '</div>';

// description
echo '<div>';
echo elgg_format_element('label', [
	'for' => 'discussions-tools-discussion-quick-start-description',
], elgg_echo('discussion:topic:description'));
echo elgg_view('input/plaintext', [
	'name' => 'description',
	'id' => 'discussions-tools-discussion-quick-start-description',
	'required' => true,
]);
echo '</div>';

// tags
echo '<div>';
echo elgg_format_element('label', [
	'for' => 'discussions-tools-discussion-quick-start-tags',
], elgg_echo('tags'));
echo elgg_view('input/tags', [
	'name' => 'tags',
	'id' => 'discussions-tools-discussion-quick-start-tags',
]);
echo '</div>';

// buttons / footer
echo '<div class="elgg-foot">';
echo elgg_view('input/hidden', ['name' => 'status', 'value' => 'open']);
echo elgg_view('input/submit', ['value' => elgg_echo('save')]);
echo '</div>';

echo '</div>'; // end wrapper
