$(document).ready(function () {

	if ($.find('input[name="is_fm802"]').length) {
		let today = $('#today').text();
		get_artist_info(today);
	}

    $('#line_notify').click(function () {
        const info = $('input[name="table_for_line"]').val();
        line_notify(info);
    });

    $('#check_all').on('click', function () {
        let checked = $(this).is(':checked');
        $('input[name="selected_member[]"]').prop('checked', Boolean(checked));
    });

    $('#check_all2').on('click', function () {
        $('#check_all').click();
        let text = $(this).text();
        if (text === '全選択') {
            $(this).removeClass('btn-primary').addClass('btn-info');
            $(this).text('全解除');
            $('.husanka').addClass('sanka').removeClass('husanka');
        } else {
            $(this).removeClass('btn-info').addClass('btn-primary');
            $(this).text('全選択');
            $('.sanka').addClass('husanka').removeClass('sanka');
        }

    });

    $('.sanka, .husanka').on('click', function () {
        $obj = $(this);
        toggle_sanka($obj);
    });

    $('#next_match').on('click', function () {
        set_next_match();
    })

    $('.chg_date').click(function () {
        var date_str;
        let type = $(this).data('type');
        let $today = $('#today');

        if (type === "prev") {
            // prev
            date_str = moment($today.text(), "YYYY-MM-DD").subtract(1, 'days').format("YYYY-MM-DD");
        } else {
            // next
            date_str = moment($today.text(), "YYYY-MM-DD").add(1, 'days').format("YYYY-MM-DD");
        }
        $today.text(date_str);
        get_artist_info(date_str);
    });
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
		console.info('次の試合の取得に失敗しました。');
    });
}

function get_artist_info(onair_date) {
    $.ajax({
        url: "fm802/get_artist_info",
        dataType: "json",
        type: 'POST',
        data: { onair_date: onair_date },
    }).done(function (artist_info, textStatus, jqXHR) {
        set_artist_chart(artist_info, onair_date);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        alert("fail");
    });
}

function set_artist_chart(artist_info, onair_date) {
    let list = artist_info;
    let label_list = [];
    let data_list = [];
    for (let item in list) {
        for (let artist in list[item]) {
            label_list.push(artist);
            data_list.push(parseInt(list[item][artist]));
        }
    }
    let max_cnt = Math.max(...data_list);
    let ctx = document.getElementById('myChart').getContext('2d');
    let myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: label_list,
            datasets: [{
                label: 'オンエアチャート',
                data: data_list,
                fill: false,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                xAxes: [
                    {
                        scaleLabel: {
                            display: true,
                            labelString: '名前',
                        },
                    }
                ],
                yAxes: [
                    {
                        scaleLabel: {
                            display: true,
                            labelString: 'オンエア回数',
                        },
                        ticks: {
                            min: 1,
                            max: max_cnt + 1,
                        }
                    }
                ]
            }
        }
    });

}

function line_notify(message) {

    $.ajax({
        url: "main/line_notify",
        dataType: "json",
        type: 'POST',
        data: { message: message },
    }).done(function (data, textStatus, jqXHR) {
        alert("LINEに送ったよ！");
    }).fail(function (jqXHR, textStatus, errorThrown) {
        alert("fail");
    });
}