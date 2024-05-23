<?php
$boardingcheckPoints = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_BORDING_CHECK);
?>
<div class="card-body font-12">
	<?php
	if (count($boardingcheckPoints) > 0)
	{
		echo "<ul class='pl15'>";
		foreach ($boardingcheckPoints as $c)
		{
			echo "<li class='mb15'>" . $c['tnp_text'] . "</li>";
		}
		echo "</ul>";
	}
	?>
</div>