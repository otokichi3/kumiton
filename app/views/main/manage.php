    <div class="container">
        <?php if ( ! empty($add_member)): ?>
			<div class="alert alert-info">
				メンバーを追加しました。
			</div>
        <?php endif; ?>
        <form action="<?= base_url('main/manage_member'); ?>" method="POST">
            <div class="jumbotron">
                <h1 class="display-5">メンバー管理</h1>
                <p class="lead">メンバーの追加、編集、および削除を行えます。</p>
            </div>
            <table id="member_list" class="table table-bordered table-striped table-hover table-sm table-responsive-md text-center">
                <thead class="thead-light">
                    <tr>
                        <th>名前</th>
                        <th>ニックネーム</th>
                        <th>性別</th>
                        <th>レベル</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_member_info as $member): ?>
                    <tr>
                        <td class="name"><?= $member['name']; ?></td>
                        <td class="nickname"><?= $member['nickname']; ?></td>
                        <td class="sex text-center">
							<?= $member['sex'] === '1' ? '男性' : '女性' ?>
                        </td>
                        <td class="level"><?= $member['level']; ?></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-sm edit_member" data-id="<?= $member['id']; ?>" data-toggle="modal" data-target="#edit">変更</button>
                            <button type="button" class="btn btn-danger btn-sm delete_btn" data-id="<?= $member['id']; ?>" data-toggle="modal" data-target="#delete">削除</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
                    <td width="40%" class="align-middle">
                        <input type="text" name="add_member_name[<?= $i; ?>]" class="form-control">
                    </td>
                    <td width="40%">
						<div class="form-inline align-middle">
							<label class="m-1"><input type="radio" name="add_member_sex[<?= $i; ?>]" value="1">男</label>
							<label class="m-1"><input type="radio" name="add_member_sex[<?= $i; ?>]" value="2">女</label>
							<label class="m-1"><input type="radio" name="add_member_sex[<?= $i; ?>]" value="3">その他</label>
						</div>
                    </td>
                    <td width="20%" class="align-middle">
                        <select name="add_member_level[<?= $i; ?>]" class="form-control">
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
                <?php endfor; ?>
            </table>
            <button type="submit" class="btn btn-primary btn-sm">追加</button>
        </form>
        <!-- modal edit -->
        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="label1">メンバー編集</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="edit_member_form">
                            <?= form_hidden('id', '') ?>
                            <div class="form-group">
                                <label class="control-label col-xs-2">名前</label>
                                <div class="col-xs-5">
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-2">ニックネーム</label>
                                <div class="col-xs-5">
                                    <input type="text" name="nickname" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-2">性別</label>
                                <div class="col-xs-5">
                                    <select name="sex" class="form-control" required>
                                        <option value="1">男性</option>
                                        <option value="2">女性</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-2">レベル</label>
                                <div class="col-xs-5">
                                    <input type="number" name="level" class="form-control" step="0.5" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-offset-2 col-xs-10 text-center">
                                    <button type="submit" class="btn btn-primary">更新</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">OK</button>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- modal edit -->

        <!-- modal delete -->
        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="label1">メンバー削除</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="del_member_form">
                            <?= form_hidden('id', '') ?>
                            <div>選択したメンバーを削除します。</div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary delete_member">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal delete -->
