<?php
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?><style>
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .bootstrap-timepicker-widget input  {
        border: 1px #555555 solid;color: #555555;
    }
    .navbar-nav > li > a {
        padding: 6px 30px;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin:0;
    }
    .selectize-input {
        min-width: 0px !important; 
        width: 30% !important;
    }
    .checkbox label {
        padding-left: 0px;
    }
    .dtpiker {
        position: relative;
        left: 0px;
        top: 0px;
        z-index: 99999!important;
    }
    .tmpiker {
        position: relative;
        left: 0px;
        top: 0px;
        z-index: 99999!important;
    }

    td, th {
        padding: 10px  !important ; 
    }
</style>

<div class="panel">
    <div class="panel-body">
        <div class="col-md-12">
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'multicitywidget_form',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError)
			{
				if(!hasError)
				{                                      
                                               
				}
                               
			}'
				),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => 'form-horizontal',
				),
			));
			/* @var $form TbActiveForm */
			?>

            <div class="row" style="position: relative">
                <div class="col-xs-12 col-sm-6 col-md-6 mb10 p5" >
                    <div class="input-group col-xs-12">
                        <input type="hidden" id="multicitysubmit" name="multicitysubmit" value="[]">
                        <input type="hidden" id="packageID" name="packageID" value="<?= $packageID ?>">



                        <div  class="row">
                            <div  class="col-xs-12">
								<textarea class="form-control" placeholder="First Pickup Location" name="first_pickup" id="first_pickup_popup"></textarea>
                            </div>
                        </div>
						<div  class="row">
                            <div  class="col-xs-12 p10">
                            </div>
                        </div>

						<div  class="row">
                            <div  class="col-xs-12">
								<textarea class="form-control" placeholder="Last DropOff Location" name="last_dropoff" id="last_dropoff_popup"></textarea>
                            </div>
                        </div>

                    </div>
					<div class="col-xs-12 text-center mt10" id="multisubmitbtn" >

						<button type="button" class="btn btn-success btn-lg pl40 pr40" onclick="savepckdel()">SAVE</button>
					</div>
                </div>
            </div>

			<?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<script>




</script>