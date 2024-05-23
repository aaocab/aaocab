<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-6 text-right">
		<a href="<?= Yii::app()->createUrl('admpnl/developerReport/querylist') ?>"><div class="btn btn-primary">Query List</div></a>&nbsp;
	</div>
</div>
<div class="row pull-center">
    <form class="col-lg-6" method="POST" style="margin-top: 50px;margin-left: 25%;padding: 50px;background: #ffffff">
		<?php if (Yii::app()->user->hasFlash('success')): ?>

			<div class="text-success">
				<?php echo Yii::app()->user->getFlash('success'); ?>
			</div>

		<?php endif; ?> 

		<?php if (Yii::app()->user->hasFlash('error')): ?>

			<div class="text-danger">
				<?php echo Yii::app()->user->getFlash('error'); ?>
			</div>

		<?php endif; ?> 
        <div class="form-group">
            <label for="exampleInputPassword1">Description</label>
            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Enter description" name="desc" required="true" value="<?= $desc ?>">
        </div>
        <div class="form-group">
            <input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">
            <label for="exampleInputEmail1">Query</label>
            <textarea rows="6" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter query" name="query" value="<?= $query ?>" required="true"></textarea>
        </div>

        <div class="col-lg-12 text-center"><button type="submit" class="btn btn-primary">Execute</button></div>
    </form>
</div>

