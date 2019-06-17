<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js" crossorigin="anonymous"></script>
    <div class="container">
		<div class="jumbotron">
			<h1 class="display-5"><?= $title ?></h1>
			<p class="lead"><?= $title_lead ?></p>
		</div>
        
        <div class="artist_today">
            <table class="table table-hover table-striped table-bordered table-sm table-responsive-md">
                <caption>アーティストのカウント</caption>
                <thead class="thead-dark">
                    <tr>
                        <th>アーティスト名</th>
                        <th>オンエア回数（２～）</th>
                    </tr>
                </thead>
                <?php foreach ($artist_cnt as $key => $cnt): ?>
                    <tr>
                        <td class="text-center"><?= $key ?></td>
                        <td class="text-center"><?= $cnt ?></td>
                    </tr>
                <?php endforeach ?>
                <?= form_hidden('table_for_line', var_export($artist_cnt, true)) ?>
            </table>
        </div>
		<hr>
		<div class="form-inline text-center">
			<a href="javascript:void(0);" class="chg_date" data-type="prev"> << </a>
			<span id="today"><?= date('Y-m-d', strtotime('-1 day')) ?></span>
            <a href="javascript:void(0);" class="chg_date" data-type="next"> >> </a>
		</div>
        <button type="button" class="btn btn-info float-right" id="line_notify">LINEに送る</button>
        <canvas id="myChart" style="position: relative; height:60vh; width:80vw"></canvas>
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
				<?php foreach ($song_list as $key => $song_name): ?>
					<tr>
						<td><?= $artist_list[$key] ?></td>
						<td><?= $song_list[$key] ?></td>
					</tr>
				<?php endforeach ?>
			</table>
        </div>
		<script>
			get_artist_info($('#today').text());
		</script>
