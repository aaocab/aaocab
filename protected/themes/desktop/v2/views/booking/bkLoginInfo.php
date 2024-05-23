<?php
$this->layout = 'column_booking';
?>

	<div class="col-md-4 bg-gray" style="display :<?= $userdiv ?>" id="userdiv">
					<div class="col-12 search-panel-3 text-center mt20">
						<?
						if ($model->bkg_vehicle_type_id != VehicleCategory::SHARED_SEDAN_ECONOMIC)
						{
							?>
						<span class="font-24 text-uppercase"><b>Log In to Gozocabs</b></span><br>
						<span class="text-center">New to Gozocabs? <a href="#" onclick="$jsLogin.callSignupbox('<?=Yii::app()->createUrl('users/partialsignup', ['callback' => 'refreshNavbar(data1)']) ?>')" role="button">Sign up</a></span>
						<div class="col-12 ctext-center mt10 mb20">
								<!--<a class="social-btn bg-facebook" onclick="socailSigin('facebook')" role="button"><b><i class="fab fa-facebook-square mr10"></i>   Login with Facebook</b></a>-->
								<a target="_blank" href="/users/oauth?provider=Google"><img src="/images/btn_google_signin_light_normal_web.png?v=0.1" alt="Login with Google"></a>
							</div>
							<?
						}
						else
						{
							?>
							<h4 class="text-uppercase mt0 mb10 heading-part" style="text-align: center;">To create a flexxi share booking<i class="fa fa-sign-in" style="margin-left:5px;"></i></h4>
						<? } ?>
						<?
						if ($model->bkg_vehicle_type_id != VehicleCategory::SHARED_SEDAN_ECONOMIC)
						{
							$isFlexxi = true;
						}
						else
						{
							$isFlexxi = false;
						}
						$this->renderPartial('partialsignin', ['model' => $ulmodel, 'isFlexxi' => $isFlexxi], false, true);
						?>
					</div>
					

	</div>
<?php
$dboApplicable = Filter::dboApplicable($bkgModel);
if ($dboApplicable)
{
?>
		<div class="col-sm-12 text-center mt20">
			<img src="/images/doubleback_fares2.jpg" alt="" width="350" class="img-responsive">
		</div>
<?php
}
?>
<div class="modal fade bd-example-modal-lg" id="bkSignupModel" tabindex="-1" role="dialog" aria-labelledby="bkSignupModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signupModalLabel">Register</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt0 pb0" id="bkSignupModelBody">
                ...
            </div>
        </div>
    </div>
</div>