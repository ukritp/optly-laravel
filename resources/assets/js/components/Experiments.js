
$(document).ready(function() {

	const $appetizerRadio = $('input[name="appetizer"]');
	const $mainRadio = $('input[name="main"]');
	const $dessertRadio = $('input[name="dessert"]');
	const $specialRadio = $('input[name="special"]');

	$specialRadio.on('click', function() {
		const isYes = $(this).val() === '1';
		if (isYes) {
			$appetizerRadio.prop({
				'checked': false,
				'disabled': true
			});
			$mainRadio.prop({
				'checked': false,
				'disabled': true
			});
			$dessertRadio.prop({
				'checked': false,
				'disabled': true
			});
		} else {
			$appetizerRadio.prop('disabled', false);
			$mainRadio.prop('disabled', false);
			$dessertRadio.prop('disabled', false);
		}
	});

})
