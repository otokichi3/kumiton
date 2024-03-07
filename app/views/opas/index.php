    <div class="container">
		<div class="jumbotron">
			<h1 class="display-5"><?= $title ?></h1>
			<p class="lead"><?= $title_lead ?></p>
		</div>

		<div class="text-center">
			<a href="javascript:void(0);" class="prev_month">
				<i class="fas fa-angle-double-left fa-2x"></i>
			</a>
			<span class="current_month h3"><?= $month ?></span><span class="h4">月</span>
			<a href="javascript:void(0);" class="next_month">
				<i class="fas fa-angle-double-right fa-2x"></i>
			</a>
		</div>

		<div class="text-right">
			<input type="checkbox" name="show_canceled" id="show_canceled"><label for="show_canceled" class="checkbox-inline">取消分を表示</label>
		</div>

		<!-- <table id="gym_list" class="text-center table table-hover table-striped table-bordered table-sm table-responsive-md"> -->
		<?= $table_view ?>
		<!-- </table> -->
		<button type="button" id="view_main_txt" class="btn btn-primary btn-sm mb-3" data-toggle="modal" data-target="#main_txt">
			全体公開用テキスト
		</button>
		<button type="button" id="view_sub_txt" class="btn btn-primary btn-sm mb-3" data-toggle="modal" data-target="#sub_txt">
			予約グループ用テキスト
		</button>

        <form action="<?= base_url('opas/login') ?>" method="POST">
            利用者番号<input type="tel" class="form-control" name="id" autocomplete required>
            パスワード<input type="password" class="form-control" name="password" autocomplete required>
            取得月
            <select name="month" class="form-control" style="width: 75px;" required>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo $i ?>" <?php if (date('n')+1 == $i) echo 'selected' ?>><?php echo $i ?></option>
                <?php endfor ?>
            </select>
            <button type="submit" name="action" class="btn btn-primary mt-3" value="login">取得</button>
        </form>

        <!-- modal main_txt -->
        <div class="modal fade" id="main_txt" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="label1">テキスト</h5>
                        <button type="button" class="btn btn-info btn-sm ml-3" id="copy_main_txt">コピー</button>
                        <!-- <button type="button" class="btn btn-info btn-sm ml-3" id="to_line">LINE</button> -->
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
						<div id="main_text_info"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal delete -->

        <!-- modal sub_txt -->
        <div class="modal fade" id="sub_txt" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="label1">テキスト</h5>
                        <button type="button" class="btn btn-info btn-sm ml-3" id="copy_sub_txt">コピー</button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
						<div id="sub_text_info"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal delete -->

        <!-- modal txt -->
        <div class="modal fade" id="txt" tabindex="-1" role="dialog" aria-labelledby="label1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="label1">テキスト</h5>
                        <button type="button" class="btn btn-info btn-sm ml-3" id="copy_gym_txt">コピー</button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
						<div id="gym_text_info"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
		<!-- modal txt -->
