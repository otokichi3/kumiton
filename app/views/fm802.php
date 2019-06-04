    <div class="container">
		<pre>
			<table class="table table-hover table-striped table-bordered">
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
		</pre>
