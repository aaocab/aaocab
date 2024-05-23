

<div id="admin-content" class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body panel-no-padding p20">
					<? if ($success)
					{
						?>
                    The Booking is converted to lead.
						<?
					}
					else
					{
					echo "Booking could not be converted.";
					echo CHtml::errorSummary(leadmodel); 
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

