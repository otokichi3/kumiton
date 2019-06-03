$(document).ready(function () {
    $('input[name="check_all"]').on('click', function () {
        var checked = $(this).is(':checked');
        $('input[name="selected_member[]"]').prop('checked', Boolean(checked));
	});

    $('.sanka, .husanka').on('click', function () {
		if ($(this).hasClass('.sanka')) {
			$(this).addClass('.husanka');
			$(this).removeClass('.sanka');
			$(this).css('background-color', 'aliceblue');
		} else {
			$(this).addClass('.sanka');
			$(this).removeClass('.husanka');
			$(this).css('background-color', 'lightskyblue');
		}
    });

    $('#next_match').on('click', function () {
        set_next_match();
    })
});

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