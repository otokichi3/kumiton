$(document).ready(function () {

	get_artist_info($('#today').text());

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

	$('#prev_day').click(function () {
		let today = $('#today').text();
		let today_ms = Date.parse(today);
		let date_today = new Date(today_ms);

		// -1 day
		date_today.setDate(date_today.getDate() - 1);

		var year = date_today.getFullYear() + '';
		var month = date_today.getMonth() + 1 + '';
		var date = date_today.getDate() + '';
		let prev_date = year + '-' + month + '-' + date;
		$('#today').text(prev_date);

		console.log(prev_date);
		get_artist_info(prev_date);
	})

	$('#next_day').click(function () {
		let today = $('#today').text();
		let today_ms = Date.parse(today);
		let date_today = new Date(today_ms);
		// +1 day
		date_today.setDate(date_today.getDate() + 1);
		var year = date_today.getFullYear() + '';
		var month = date_today.getMonth() + 1 + '';
		var date = date_today.getDate() + '';
		let next_date = year + '-' + month + '-' + date;
		$('#today').text(next_date);

		console.log(next_date);
		get_artist_info(next_date);
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
	// console.log(date);
	console.table(artist_info);
	let list = artist_info;
	let label_list = [];
	let data_list  = [];
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
