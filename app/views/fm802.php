    <div class="container">
		
		<table class="table table-hover table-striped table-bordered table-sm table-responsive-md">
			<thead class="thead-dark">
				<tr>
					<th>アーティスト名（３回以上のみ）</th>
					<th>カウント</th>
				</tr>
			</thead>
			<?php foreach ($artist_name_cnt as $key => $cnt): ?>
				<?php if ($cnt > 3): ?>
					<tr>
						<td class="text-center"><?= $key ?></td>
						<td class="text-center"><?= $cnt ?></td>
					</tr>
				<?php endif ?>
			<?php endforeach ?>
		</table>
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
