$(document).ready(function () {
    $('input[name="check_all_authorization"]').on('click', function () {
        var checked = $(this).is(':checked');
        $('input[name="authorize"]').prop('checked', Boolean(checked)); 
    });

    $('input[name="auth"]').on('change', function () {
        if ($(this).val() === '1') {
            $('input[name="reason"]').prop('disabled', true);
        } else {
            $('input[name="reason"]').prop('disabled', false);
        }
    });
});