		<table id="gym_list" class="text-center table table-hover table-striped table-bordered table-sm table-responsive-md">
			<caption>体育館予約情報</caption>
			<thead class="thead-light">
				<tr>
					<th>予約</th>
					<th>日付</th>
					<th>ＳＣ（体育場）</th>
					<th>時間</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($gym_list as $idx => $gym): ?>
				<tr <?php if ($gym['canceled']) echo 'style="background: lightgray;"' ?>>
                    <td class="align-middle"><?php echo $gym['user_name'] ?></td>
                    <td class="align-middle"><?php echo date('m/d', strtotime($gym['date'])) ?></td>
                    <td class="align-middle">
						<?php echo str_replace('スポーツセンター', 'ＳＣ', $gym['name']) . '（' . str_replace('体育場', '', $gym['place'] . '）') ?>
					</td>
                    <td class="align-middle"><?php echo $gym['time'] ?></td>
                    <td class="align-middle">
						<button type="button" class="btn btn-sm btn-primary txt" data-id="<?php echo $gym['id'] ?>" data-toggle="modal" data-target="#txt">
						TXT
						</button>
					</td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>