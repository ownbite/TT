jQuery(document).ready(function($) {
	if (!$('body').hasClass('widgets_access')) {
		helsingborgSetupList($);
		$('.hbgllw-edit-item').addClass('toggled-off');
		helsingborgSetupHandlers($);
	}

	$(document).ajaxSuccess(function() {
		helsingborgSetupList($);
		$('.hbgllw-edit-item').addClass('toggled-off');
	});
});

function helsingborgSetupList($) {
	$(".helsingborg-link-list").sortable({
		items: '.list-item',
		opacity: 0.6,
		cursor: 'n-resize',
		axis: 'y',
		handle: '.moving-handle',
		placeholder: 'sortable-placeholder',
		start: function(event, ui) {
			ui.placeholder.height(ui.helper.height());
		},
		update: function() {
			updateOrder($(this));
		}
	});

	$(".helsingborg-link-list .moving-handle").disableSelection();
}

// All Event handlers
function helsingborgSetupHandlers($) {
	$("body").on('click.hbgllw', '.hbgllw-delete', function() {
		$(this).parent().parent().fadeOut(500, function() {
			var hbgllw = $(this).parents(".widget-content");
			$(this).remove();
			hbgllw.find('.order').val(hbgllw.find('.helsingborg-link-list')
				.sortable('toArray'));
			var num = hbgllw.find(".helsingborg-link-list .list-item").length;
			var amount = hbgllw.find(".amount");
			amount.val(num);
		});
	});

	$("body").on('click.hbgllw', '.hbgllw-add', function() {
		var hbgllw = $(this).parent().parent();
		var num = hbgllw.find('.helsingborg-link-list .list-item').length + 1;

		hbgllw.find('.amount').val(num);

		var item = hbgllw.find('.helsingborg-link-list .list-item:last-child')
			.clone();
		var item_id = item.attr('id');
		item.attr('id', increment_last_num(item_id));

		$('.toggled-off', item).removeClass('toggled-off');
		$('.number', item).html(num);
		$('.item-title', item).html('');

		var preview_id = $('.widefat', item).attr('id');
		if (preview_id !== undefined) {
			$('.widefat', item).attr('id', increment_last_num(preview_id));
		}

		// $('div', item).each(function() {
		//   var id_val = $(this).attr('id');
		//   if (id_val !== undefined) {
		//     $(this).attr('id', increment_last_num(id_val));
		//   }
		// });

		$('label', item).each(function() {
			var for_val = $(this).attr('for');
			$(this).attr('for', increment_last_num(for_val));
		});

		$('button', item).each(function() {
			var id_val = $(this).attr('id');
			var name_val = $(this).attr('name');
			$(this).attr('id', increment_last_num(id_val));
			$(this).attr('name', increment_last_num(name_val));
		});

		$('input', item).each(function() {
			var id_val = $(this).attr('id');
			var name_val = $(this).attr('name');

			var value = $(this).attr('onclick');
			if (value !== undefined) {
				value = value.replace(/\d\u0027+/g, num + "'");
				$(this).attr('onclick', value);
			}
			$(this).attr('id', increment_last_num(id_val));

			if (name_val !== undefined) {
				$(this).attr('name', increment_last_num(name_val));
			}

			if ($(':checked', this)) {
				$(this).removeAttr('checked');
			}
			if (value === undefined) {
				$(this).val('');
			} else {
				$(this).val('Välj bild');
			}
		});

		$('select', item).each(function() {
			var id_val = $(this).attr('id');
			var name_val = $(this).attr('name');
			$(this).attr('id', increment_last_num(id_val));
			$(this).attr('name', increment_last_num(name_val));
			$(this).val(null);
		});

		$('img', item).each(function() {
			$(this).remove();
		});

		hbgllw.find('.helsingborg-link-list').append(item);
		hbgllw.find('.order').val(hbgllw.find('.helsingborg-link-list').sortable(
			'toArray'));
	});

	$('body').on('click.hbgllw', '.moving-handle', function() {
		$(this).parent().find('.hbgllw-edit-item').slideToggle(200);
	});
}

function increment_last_num(v) {
	return v.replace(/[0-9]+(?!.*[0-9])/, function(match) {
		return parseInt(match, 10) + 1;
	});
}

function updateOrder(self) {
	var hbgllw = self.parents(".widget-content");
	hbgllw.find('.order').val(hbgllw.find('.helsingborg-link-list').sortable(
		'toArray'));
}
