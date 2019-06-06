    <div class="container">
        <?php if ( ! empty($add_member)): ?>
            <div class="alert alert-info">
                メンバーを追加しました。
            </div>
        <?php endif ?>
        <form action="<?= base_url('main/manage_member') ?>" method="POST">
            <div class="jumbotron">
                <h1 class="display-5">メンバー管理</h1>
                <p class="lead">メンバーの追加、編集、および削除を行えます。</p>
            </div>
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
                    <td class="align-middle" style="width: 120px; font-size: small;">
                        <input type="text" name="add_member_name[<?= $i ?>]" class="form-control">
                    </td>
                    <td style="font-size: small;">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="add_member_sex[<?= $i ?>]" id="sex_man<?= $i ?>" value="1">
                            <label class="form-check-label" for="sex_man<?= $i ?>">男</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="add_member_sex[<?= $i ?>]" id="sex_woman<?= $i ?>" value="2">
                            <label class="form-check-label" for="sex_woman<?= $i ?>">女</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="add_member_sex[<?= $i ?>]" id="sex_other<?= $i ?>" value="3">
                            <label class="form-check-label" for="sex_other<?= $i ?>">その他</label>
                        </div>
                    </td>
                    <td class="align-middle">
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
            <button type="submit" class="btn btn-primary btn-sm">追加</button>
        </form>
