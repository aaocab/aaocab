<title>Booking Uploads</title>
<div class="row" style="margin: 20px;">
    <div class="col-xs-12">
		<?php
		$model		 = Booking::model()->findbyPk($bkg_id);
		$cabmodel	 = $model->getBookingCabModel();
		$driver_id	 = $cabmodel->bcb_driver_id;
		$dir		 = PUBLIC_PATH . '/driver/' . $driver_id . '/' . $bkg_id;
		$files		 = scandir($dir);
		foreach ($files as $file)
		{
			if (is_file($dir . '/' . $file))
			{
				?>
				<img style="border:5px double black;margin-right: 5px;" src="<?= '/driver/' . $driver_id . '/' . $bkg_id . '/' . $file ?>" alt="Odometer" height="200" width="200">
				<?php
			}
		}
		?>
    </div>
    <div class="col-xs-12">
		<?php
		$dir1	 = PUBLIC_PATH . '/driver/' . $driver_id . '/' . $bkg_id . '/files';
		$files1	 = scandir($dir1);
		if ($files == '' && $files1 == '')
		{
			?>
			<div style="text-align: center;">Sorry there is nothing uploaded for this booking</div>
			<?php
		}
		$lat	 = [];
		$long	 = [];
		foreach ($files1 as $file1)
		{
			if (is_file($dir1 . '/' . $file1))
			{
				?>
				<br><a href="<?= '/driver/' . $driver_id . '/' . $bkg_id . '/files/' . $file1 ?>"><button type="button"><?= $file1 ?></button></a><br>
				<?php
				$file2	 = fopen(PUBLIC_PATH . '/driver/' . $driver_id . '/' . $bkg_id . '/files/' . $file1, "r");
				while ($row	 = fgetcsv($file2))
				{
					array_push($lat, $row[2]);
					array_push($long, $row[3]);
				}
//                if (($key1 = array_search("phone", $lat)) !== false) {
//                    unset($lat[$key1]);
//                }
//                if (($key2 = array_search("city", $long)) !== false) {
//                    unset($long[$key2]);
//                }
				fclose($file2);
			}
		}
		echo '<pre>';
		print_r(json_encode(['lat' => array_values($lat), 'long' => array_values($long)]));
		?>
    </div>
</div>


