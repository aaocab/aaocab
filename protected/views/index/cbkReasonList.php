 


<div class="container  ">
	<div class="float-none marginauto   col-sm-12 ">
		<div class="    ">
			<label>Select Reason to request Call Back</label>				<div class="row">

				<?php
				foreach (FollowUps::getReasonList() as $k => $v)
				{
					?>
					<button type="button" class="btn btn-primary mt20 col-sm-12" onclick="reqCMB(<?php echo "$k" ?>)"><?php echo $v ?></button>
					<?
				}
				?>

				<? //php echo $form->dropDownList($model, 'fwp_ref_type', FollowUps::getReasonList(), ['class' => 'form-control']) ?>
			</div>
		</div >	
		<div class="col-sm-6 col-xs-12 Submit-button text-center mt30 hide" >
			<button type="submit" id="cbkStep2" class="btn btn-effect-ripple btn-success p5 pl10 pr10" name="cbkStep2"  >Request</button>

		</div> 
	</div>
</div> 

