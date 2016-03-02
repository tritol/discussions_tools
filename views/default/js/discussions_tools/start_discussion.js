define(['jquery', 'elgg'], function ($, elgg) {
	
	$(document).on('submit', '#discussions-tools-start-discussion-widget-form', function () {
		var selected_group = $('#discussions-tools-discussion-quick-start-group').val();
		if (selected_group !== '0') {
			$('#discussions-tools-discussion-quick-start-access_id option').removeAttr('selected');
			$('#discussions-tools-discussion-quick-start-access_id option').each(function (index, elem) {
				if ($(elem).html() === selected_group) {
					$(elem).attr('selected', 'selected');
				}
			});
		} else {
			elgg.register_error(elgg.echo('discussions_tools:forms:discussion:quick_start:group:required'));
			return false;
		}
	});
});