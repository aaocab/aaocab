<?php
$othertermsPoints	 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_OTHER_TERMS);
?>
<div class="card-body font-12 text-uppercase">
	<?php
	if (count($othertermsPoints) > 0)
	{
		echo "<ul class='pl15'>";
		foreach ($othertermsPoints as $c)
		{
			echo "<li class='mb15'>" . $c['tnp_text'] . "</li>";
		}
		echo "</ul>";
	}
	?>
</div>