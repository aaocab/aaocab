<div class="col-xs-8 col-sm-6 col-lg-4" style="float: none;margin: auto">
    <div class="panel">
        <div class="panel-body">
			<?= CHtml::beginForm(Yii::app()->createUrl('admin/index/changepassword'), "post", ['id' => "formId", 'accept-charset' => "utf-8", 'name' => "ChangePassword"]); ?>

            <div style="color: red; margin-bottom: 10px;">    
                <?
				if ($status == 'error')
				{
				echo "( " . $message . " )";
                }
                ?>
            </div>
            <div class="form-group">
                <label for="oldpassword">Current Password</label>
                <input name="oldpassword" class="form-control" type="password" id="oldpassword" required>
                <div e_rel="oldpassword"></div>
            </div>
            <div class="form-group">
                <label for="newpassword">New Password</label>
                <input name="newpassword" class="form-control" minlength="3" type="password" id="newpassword" required>
                <div e_rel="newpassword"></div>
            </div>
            <div class="form-group">
                <label for="confirmpassword">Confirm Password</label>
                <input name="confirmpassword" class="form-control" minlength="3" type="password" id="confirmpassword" required>
                <div e_rel="confirmpassword"></div>
            </div>
            <div class="form-group text-center" >
                <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-lock"></i>  Change Password</button>
            </div>
			<?= CHtml::endForm() ?>
        </div>
    </div>
</div>
