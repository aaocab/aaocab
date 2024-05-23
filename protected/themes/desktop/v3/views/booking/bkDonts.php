<?php $dosdontsPoints		 = TncPoints::model()->getTncDescription(TncPoints::TNC_TYPE_CUSTOMER, $model->bkg_booking_type, $model->bkgSvcClassVhcCat->scv_scc_id, TncPoints::TNC_DOS_AND_DONTS);
?>
<div class="card-body font-12">
	<?php
	if (count($dosdontsPoints) > 0)
	{
		echo "<ul class='pl15'>";
		foreach ($dosdontsPoints as $c)
		{
			echo "<li class='mb15'>" . $c['tnp_text'] . "</li>";
		}
		echo "</ul>";
	}
	?>
</div>