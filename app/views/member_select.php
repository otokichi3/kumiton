    <div class="container">
        <?php if ( ! empty($add_member)): ?>
            <div class="alert alert-info">
                メンバーを追加しました。
            </div>
        <?php endif ?>
        <form action="/main/show_game" method="post">
            <div class="jumbotron">
                <h1 class="display-5">メンバー</h1>
                <p class="lead">メンバーの選択、追加、削除を行えます。</p>
            </div>
			<div class="member_grid text-center">
				<?php foreach ($all_member as $name => $level): ?>
				<div class="align-middle husanka" data-name="<?= $name ?>"><?= $name.'('.$level.')' ?>
					<br><i class="fas fa-male"></i>
				</div>
				<?php endforeach ?>
			</div>
            <table class="table table-bordered table-hover table-sm table-responsive-md">
                <caption>メンバーリスト</caption>
                <thead class="thead-light">
                    <tr>
                        <th scope="col">名前</th>
                        <th scope="col">レベル</th>
                        <th scope="col">参加<span class="pl-1"><input type="checkbox" name="check_all"></span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_member as $name => $level): ?>
                    <?php $checked = (array_search($name, (array)$selected_member) !== FALSE) ? 'checked' : '' ?>
                    <tr>
                        <td><?= $name ?></td>
                        <td><?= $level ?></td>
                        <td>
                            <input type="checkbox" name="selected_member[]" class="selected" value="<?= $name ?>" <?= $checked ?>>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">次へ</button>
        </form>

        <form action="/main" method="post">
            <h2>メンバー追加</h2>
            <table class="table table-bordered table-hover table-sm table-responsive-md">
                <thead class="thead-light">
                    <tr>
                        <th>名前</th>
                        <th>性別</th>
                        <th>レベル</th>
                    </tr>
                </thead>
                <?php for ($i = 0; $i < 5; $i++): ?>
                <tr>
                    <td>
                        <input type="text" name="add_member_name[<?= $i ?>]" class="form-control">
                    </td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="add_member_sex[<?= $i ?>]" id="sex_man<?= $i ?>" value="1">
                            <label class="form-check-label" for="sex_man<?= $i ?>">男</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="add_member_sex[<?= $i ?>]" id="sex_woman<?= $i ?>" value="2">
                            <label class="form-check-label" for="sex_woman<?= $i ?>">女</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="add_member_sex[<?= $i ?>]" id="sex_other<?= $i ?>" value="3">
                            <label class="form-check-label" for="sex_other<?= $i ?>">その他</label>
                        </div>
                    </td>
                    <td>
                        <select name="add_member_level[<?= $i ?>]" class="form-control">
                            <option value="" selected>---</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </td>
                </tr>
                <?php endfor ?>
            </table>
            <button type="submit" class="btn btn-primary">追加</button>
        </form>
