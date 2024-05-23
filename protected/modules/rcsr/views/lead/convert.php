<? ?>

<div id="admin-content" class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body panel-no-padding p20">
					<? if ($success)
					{
						?>
						The lead is converted to a new Booking<br> Booking Id:
						<?=
						$newmodel->bkg_booking_id;
					}
					else
					{
						echo "Lead could not be converted.";
						echo CHtml::errorSummary($newmodel);
					}
					?>
                </div>
            </div>
        </div>
    </div>
</div>

