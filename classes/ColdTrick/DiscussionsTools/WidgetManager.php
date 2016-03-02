<?php

namespace ColdTrick\DiscussionsTools;

class WidgetManager {
	
	/**
	 * Set the title URL for the discussions_tools widgets
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param string $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|string
	 */
	public static function widgetURL($hook, $type, $return_value, $params) {
		
		if (!empty($return_value)) {
			// someone already set an url
			return;
		}
		
		$widget = elgg_extract('entity', $params);
		if (!($widget instanceof \ElggWidget)) {
			return;
		}
		
		switch ($widget->handler) {
			case 'start_discussion':
				$owner = $widget->getOwnerEntity();
				if ($owner instanceof \ElggGroup) {
					$return_value = "discussion/add/{$owner->getGUID()}";
				}
				break;
			case 'discussion':
				$return_value = 'discussion/all';
				break;
			case 'group_forum_topics':
				$page_owner = elgg_get_page_owner_entity();
				if (($page_owner instanceof \ElggGroup)) {
					$return_value = "discussion/owner/{$page_owner->getGUID()}";
				}
				break;
		}
		
		return $return_value;
	}
	
	/**
	 * Add or remove widgets based on the group tool option
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function groupToolWidgets($hook, $type, $return_value, $params) {
		
		$entity = elgg_extract('entity', $params);
		if (!($entity instanceof \ElggGroup)) {
			return;
		}
		
		if (!is_array($return_value)) {
			return;
		}
		
		// check different group tools for which we supply widgets
		if ($entity->forum_enable === 'yes') {
			$return_value['enable'][] = 'group_forum_topics';
		} else {
			$return_value['disable'][] = 'group_forum_topics';
			$return_value['disable'][] = 'start_discussion';
		}
		
		return $return_value;
	}
}
