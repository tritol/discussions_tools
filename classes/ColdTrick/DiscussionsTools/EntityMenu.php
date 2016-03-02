<?php

namespace ColdTrick\DiscussionsTools;

class EntityMenu {
	
	/**
	 * Add quick status change items
	 *
	 * @param string          $hook         the name of the hook
	 * @param string          $type         the type of the hook
	 * @param \ElggMenuItem[] $return_value current return vaue
	 * @param array           $params       supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function discussionStatus($hook, $type, $return_value, $params) {
		
		$entity = elgg_extract('entity', $params);
		if (!elgg_instanceof($entity, 'object', 'discussion')) {
			return;
		}
		
		if (!$entity->canEdit()) {
			return;
		}
		
		// load js
		elgg_require_js('discussions_tools/status_toggle');
		
		// add menu items
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'status_change_open',
			'text' => elgg_echo('open'),
			'confirm' => elgg_echo('discussions_tools:discussion:confirm:open'),
			'href' => "action/discussions/toggle_status?guid={$entity->getGUID()}",
			'priority' => 200,
			'item_class' => ($entity->status == 'closed') ? '' : 'hidden',
		]);
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'status_change_close',
			'text' => elgg_echo('close'),
			'confirm' => elgg_echo('discussions_tools:discussion:confirm:close'),
			'href' => "action/discussions/toggle_status?guid={$entity->getGUID()}",
			'priority' => 201,
			'item_class' => ($entity->status == 'closed') ? 'hidden' : '',
		]);
		
		return $return_value;
	}
}
