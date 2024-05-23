<title>Booking Uploads</title>
<div class="row" style="margin: 20px;">
    <div class="col-xs-12">
		<?php
		$model = Booking::model()->findbyPk($bkg_id);
		$driver_id = $model->bkg_driver_id;
		$dir = PUBLIC_PATH . '/driver/' . $driver_id . '/' . $bkg_id;
		$files = scandir($dir);
		foreach ($files as $file)
		{
			if (is_file($dir . '/' . $file))
			{
				?>
				<img style="border:5px double black;" src="<?= '/driver/' . $driver_id . '/' . $bkg_id . '/' . $file ?>" alt="Odometer" height="200" width="200">
				<?php
			}
		}
		?>
    </div>
    <div class="col-xs-12">
		<?php
		$dir1 = PUBLIC_PATH . '/driver/' . $driver_id . '/' . $bkg_id . '/files';
		$files1 = scandir($dir1);
		if ($files == '' && $files1 == '')
		{
			?>
			<div style="text-align: center;">Sorry there is nothing uploaded for this booking</div>
			<?php
		}
		foreach ($files1 as $file1)
		{
			if (is_file($dir1 . '/' . $file1))
			{
				?>
				<br><br><a href="<?= '/driver/' . $driver_id . '/' . $bkg_id . '/files/' . $file1 ?>"><button type="button"><?= $file1 ?></button></a>
				<?php
			}
		}
		?>
    </div>
</div>


