$(document).ready(function () {

    // メンバーの更新・削除用
    var $tr;

    // fm802 のページでだけ動作
    if ($.find('input[name="is_fm802"]').length) {
        let today = $('#today').text();
        get_artist_info(today);
        get_rank();
    }

    $('.delete_btn').on('click', function () {
        $tr = $(this).parent().parent();
        var id = $(this).data('id');
        $('#del_member_form').find('input[name="id"]').val(id);
    });

    // delete member
    $('.delete_member').on('click', function () {
        var id = $('#del_member_form').find('input[name="id"]').val();
        $.ajax({
            url: "delete_member/" + id,
            dataType: "json",
            type: 'GET',
        }).done(function (data, textStatus, jqXHR) {
            console.info('メンバー削除に成功');
            $tr.remove();
            close('#delete');
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.info('メンバー削除に失敗');
        });
    });

    $('.edit_member').on('click', function () {
        var id = $(this).data('id');
        var $form = $('#edit_member_form');
        $tr = $(this).parent().parent();
        $form.find('input[name="id"]').val(id);

        $.ajax({
            url: "get_member_data/" + id,
            dataType: "json",
            type: 'GET',
        }).done(function (data, textStatus, jqXHR) {
            $form.find('input[name="name"]').val(data.name);
            $form.find('input[name="nickname"]').val(data.nickname);
            $form.find('input[name="level"]').val(data.level);
            $form.find('select[name="sex"]').val(data.sex);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.info('メンバー情報取得に失敗');
        });
    });

    $('#edit_member_form').submit(function () {
        var $form = $('#edit_member_form');
        $.ajax({
            url: "save_member_data/",
            dataType: "json",
            type: 'POST',
            data: $(this).serialize(),
        }).done(function (data, textStatus, jqXHR) {
			const sex = data.sex == 1 ? '男性' : '女性';
            $tr.find('.name').text(data.name);
			$tr.find('.nickname').text(data.nickname);
			$tr.find('.sex').text(sex);
            $tr.find('.level').text(data.level);
			close('#edit');

			// clear form
			$form.find('input[name="name"]').val('');
            $form.find('input[name="nickname"]').val('');
            $form.find('input[name="level"]').val('');
            $form.find('select[name="sex"]').val('');
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.info('メンバー情報取得に失敗');
        }).always(function () {
            return false;
        });
        return false;
    });

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
        alert("failed to get artist info");
    });
}

function get_rank() {
    $.ajax({
        url: "fm802/get_rank",
        dataType: "json",
        type: 'POST',
        data: { type: 1 },
    }).done(function (rank, textStatus, jqXHR) {
        set_rank_chart(rank);
    }).fail(function (jqXHR, textStatus, errorThrown) {
        alert("failed to get rank");
    });
}

function set_rank_chart(rank) {
    let label_list = [];
    let data_list = [];
    for (list in rank) {
        label_list.push(rank[list]['artist']);
        data_list.push(rank[list]['count']);
    }
    let max_cnt = Math.max(...data_list);
    let ctx = document.getElementById('weekly_ranking').getContext('2d');
    let weekly_ranking = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: label_list,
            datasets: [{
                label: '週間オンエア回数',
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
                label: '本日のオンエア回数',
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

function set_rank() {
    let list = artist_info;
    return;
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

function close(modal_id = '') {
    $('body').removeClass('modal-open'); // 1
    $('.modal-backdrop').remove();       // 2
    $(modal_id).modal('hide');        // 3
}