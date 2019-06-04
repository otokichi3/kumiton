    <div class="container">
		
		<table class="table table-hover table-striped table-bordered table-sm">
			<thead class="thead-dark">
				<tr>
					<th>アーティスト名</th>
					<th>カウント</th>
				</tr>
			</thead>
			<?php foreach ($artist_name_cnt as $key => $cnt): ?>
				<tr>
					<td class="text-center"><?= $key ?></td>
					<td class="text-center"><?= $cnt ?></td>
				</tr>
			<?php endforeach ?>
		</table>
		<hr>
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
