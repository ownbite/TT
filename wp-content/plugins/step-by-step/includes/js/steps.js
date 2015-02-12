$(document).ready(function() {

	if ($('.guide-list li.current:first-child').length) {
		$('.prev-step').hide();
	}

	$('.next-step').on('click', function(e) {
		e.preventDefault();

		var currentElem = $('.guide-list li.current');

		currentElem.next().addClass("current");
		currentElem.removeClass("current");
		removeNext();
		removePrev();
	});

	$('.prev-step').on('click', function(e) {
		e.preventDefault();

		var currentElem = $('.guide-list li.current');

		currentElem.prev().addClass("current");
		currentElem.removeClass("current");
		removePrev();
		removeNext();

	});

	function removeNext() {
		if ($('.guide-list li.current:last-child').length) {
			$('.next-step').hide();
		} else {
			$('.next-step').show();
		}

	}

	function removePrev() {
		if ($('.guide-list li.current:first-child').length) {
			$('.prev-step').hide();
		} else {
			$('.prev-step').show();
		}
	}

});
