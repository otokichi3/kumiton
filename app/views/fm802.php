<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js" crossorigin="anonymous"></script>
    <div class="container">
		<div class="jumbotron">
			<h1 class="display-5"><?= $title ?></h1>
			<p class="lead"><?= $title_lead ?></p>
		</div>
		
		<table class="table table-hover table-striped table-bordered table-sm table-responsive-md">
			<caption>アーティストのカウント</caption>
			<thead class="thead-dark">
				<tr>
					<th>アーティスト名</th>
					<th>カウント（３回以上のみ）</th>
				</tr>
			</thead>
			<?php  ?>
			<?php foreach ($artist_name_cnt as $key => $cnt): ?>
                <tr>
                    <td class="text-center"><?= $key ?></td>
                    <td class="text-center"><?= $cnt ?></td>
                </tr>
			<?php endforeach ?>
			<?php  ?>
		</table>
		<hr>
		<canvas id="myChart" width="400px" height="200px"></canvas>
		<script>
		let list = <?= json_encode($artist_info) ?>;
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
					label: 'アーティスト登場回数',
					data: data_list,
					fill: false,
					// borderColor: [
					// 	'rgba(220, 20, 60, 1)',
					// ],
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
								labelString: 'アーティスト名',
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
		</script>
		<hr>

        <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#onair_list">曲目の表示</button>
        <div id="onair_list" class="collapse">
			<table class="table table-hover table-striped table-bordered table-sm">
				<thead class="thead-dark">
					<tr>
						<th>アーティスト</th>
						<th>曲名</th>
					</tr>
				</thead>
				<?php foreach ($song_name_list as $key => $song_name): ?>
					<tr>
						<td><?= $artist_name_list[$key] ?></td>
						<td><?= $song_name_list[$key] ?></td>
					</tr>
				<?php endforeach ?>
			</table>
        </div>
