$(document).ready(function () {

	$('#check_all').on('click', function () {
		let checked = $(this).is(':checked');
		$('input[name="selected_member[]"]').prop('checked', Boolean(checked));
	});

	$('#check_all2').on('click', function () {
		$('#check_all').click();
		$('.husanka').addClass('sanka').removeClass('husanka');
	});

	$('.sanka, .husanka').on('click', function () {
		$obj = $(this);
		toggle_sanka($obj);
	});

	$('#next_match').on('click', function () {
		set_next_match();
	})
});

function toggle_sanka(obj) {
	let name = obj.data('name');
	if (obj.hasClass('sanka')) {
		obj.addClass('husanka');
		obj.removeClass('sanka');
		for (obj of $('[name="selected_member[]"]')) {
			if ($(obj).val() === name) {
				$(obj).prop('checked', false);
			}
		}
	} else {
		obj.addClass('sanka');
		obj.removeClass('husanka');
		for (obj of $('[name="selected_member[]"]')) {
			if ($(obj).val() === name) {
				$(obj).prop('checked', true);
			}
		}
	}
}

function set_next_match() {
	$.ajax({
		url: "get_match",
		dataType: "json",
		type: 'POST',
		data: { num: 4 },
	}).done(function (data, textStatus, jqXHR) {
		$('#court_list').html(data);
	}).fail(function (jqXHR, textStatus, errorThrown) {
		alert("fail");
	});
}