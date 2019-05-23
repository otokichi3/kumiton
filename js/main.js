$(document).ready(function () {
    $('input[name="check_all"]').on('click', function () {
        var checked = $(this).is(':checked');
        $('input[name="selected_member[]"]').prop('checked', Boolean(checked));
    });

    $('#next_match').on('click', function () {
    })
});

function callAjax() {
    $.ajax({
        url: "http://itref.fc2web.com/javascript/jquery/sample.json",
        dataType: "json"
    })
        .done(function (data, textStatus, jqXHR) {
            alert(data.foo);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            alert("fail ");
        });
}