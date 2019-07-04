    <div class="container">
		<div class="jumbotron">
			<h1 class="display-5"><?= $title ?></h1>
			<p class="lead"><?= $title_lead ?></p>
		</div>
        <form action="<?= base_url('opas/login') ?>" method="POST">
            利用者番号<input type="tel" class="form-control" name="id">
            パスワード<input type="password" class="form-control" name="password">
            取得月
            <select name="month" class="form-control" style="width: 75px;">
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo $i ?>" <?php if (date('n')+1 == $i) echo 'selected' ?>><?php echo $i ?></option>
                <?php endfor ?>
            </select>
            <button type="submit" name="action" class="btn btn-primary mt-3" value="login">ログイン</button>
        </form>
        <script type="text/javascript">
            $(function () {
                $('#date').datetimepicker({
                    format: 'LT'
                });
            });
        </script>
