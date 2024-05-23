<style>
	@page {
		margin-top: 0cm;
		margin-bottom: 0cm;
		margin-left: 0cm;
		margin-right: 0cm;
	}

	@page * {
		margin-top: 0.5cm;
		margin-bottom: 0.5cm;
		margin-left: 0.5cm;
		margin-right: 0.5cm;
	}
	.image-break img { 
		page-break-before: auto; /* 'always,' 'avoid,' 'left,' 'inherit,' or 'right' */ 
		page-break-after: auto; /* 'always,' 'avoid,' 'left,' 'inherit,' or 'right' */ 
		page-break-inside: avoid; /* or 'auto' */ 
	}
</style>
<div class="main-div">
    <table width="100%">
        <tbody>
			<?php
			//$host = Yii::app()->params['host'];
			if (count($agmtDocs) > 0)
			{
				$ctr = 1;
				foreach ($agmtDocs as $agmt)
				{
					$image_url = $baseURL . $agmt['vd_agmt'];
					?>
					<tr>
						<td align="center">
							<table width="100%">
								<tr><td align="center" class="image-break"><img src="<?= $image_url; ?>" alt="<?= $model->vnd_name; ?>" class="image-break"></td></tr>
							</table>
						</td>
					</tr>
					<?php
					$ctr++;
				}
			}
			?>
        </tbody>
    </table>
</div>