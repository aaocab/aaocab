<div class="row">
	<div class="col-xs-12 table-style-panel">
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr class="bg-purple color-white">
					<td><b>Vendor</b></td>
					<td><b>Vendor Code</b></td>
				</tr>
				<?php
				$vendorName	 = explode(",", $data['vnd_name']);
				$vnd_code	 = explode(",", $data['vnd_code']);
				for ($i = 0; $i < count($vendorName); $i++)
				{
					?>
					<tr>
						<td><b><?= $vendorName[$i] ?></b></td>
						<td><b><a target="_blank"  href="<?= Yii::app()->createUrl('admin/vendor/view', ['code' => $vnd_code[$i]]) ?>" target="_blank"><?= $vnd_code[$i]; ?></a</b></td>
					</tr>
	          <?php } ?>
			</table>
		</div>
	</div>
</div>