<div class="container-fluid">
	
	<div class="container mt30 mb30">
		<?php 
		$form = $this->beginWidget('CActiveForm', array(
			'id'					 => 'Account',
			'enableClientValidation' => false,
			'clientOptions'			 => [
				'validateOnSubmit'	 => false,
				'errorCssClass'		 => 'has-error',
			],
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class'			 => 'form-horizontal',
				'autocomplete'	 => 'off',
			),
				));
		/* @var $form CActiveForm */

		?>
		<input type="hidden" id="step1" name="step" value="1">
		<div class="menuTripType">
                    <div class="row">
                        <div class="col-12 text-center mt-3 style-widget-1"><h2 class="gothic weight600">Do you already have a gozo account?</h2></div>

                        <div class="col-12 col-lg-4 offset-lg-4 mt-3">
                            <div class="row mb-3 radio-style2">
                                <div class="col-12">
                                    <div class="radio">
                                        <input id="checkaccount_0" value="1" type="radio" name="checkaccount" class="bkg_user_trip_type" checked="">	
                                        <label for="checkaccount_0">YES</label>
                                    </div>
                                </div>
                                <div class="col-12 radio">
                                    <div class="radio">
                                        <input id="checkaccount_1" value="2" type="radio" name="checkaccount" class="bkg_user_trip_type" checked="">
                                        <label for="checkaccount_1">NO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center pb-3">
                            <input type="submit" value="Next" name="yt0" id="checkaccount" class="btn btn-primary pl-5 pr-5">
                        </div>
                    </div>
		</div>
		<?php $this->endWidget(); ?>
     </div>
	
</div>


