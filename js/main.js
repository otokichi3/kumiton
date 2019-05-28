$(document).ready(function () {
    $('input[name="check_all"]').on('click', function () {
        var checked = $(this).is(':checked');
        $('input[name="selected_member[]"]').prop('checked', Boolean(checked));
    });

    $('#next_match').on('click', function () {
        callAjax();
    })
});

function callAjax() {
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