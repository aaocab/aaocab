<style type="text/css">
    .selectize-input {
        min-width: 0px !important;
        width: 100% !important;
    }
    .bordered {
        border:1px solid #ddd;
        min-height: 45px;
        line-height: 1.1;

    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .new-booking-list label{ font-size: 11px;}
</style>
<?php
/* @var $model Drivers */
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$mergeBorder		 = ($model->drv_id_merge != '') ? "bordered mb10" : '';
$mergeshow			 = ($model->drv_id_merge != '') ? '' : "hide";
$readOnly			 = array();
$readOnly1			 = array('label' => '', 'class' => 'form-control', 'placeholder' => 'Contact Number');
$readOnly2			 = "";

if($model->drv_id != '' )
{
	$email = ContactEmail::model()->getContactEmailById($model->drv_contact_id);
	$phone = ContactPhone::model()->getContactPhoneById($model->drv_contact_id);
}

if ($model->isNewRecord)
{
	$title	 = "Add";
//CONFIRM
	$js		 = "if($.isFunction(window.refreshDriver)){
        window.refreshDriver();
        } else {
        window.location.reload();
        }";
}
//UPDATE
else
{
	$title		 = "Edit";
	$readOnly	 = array('htmlOptions' => array('readOnly' => 'readOnly'));
	if(isset($model->drv_phone) && $model->drv_phone!='')
	{
		$readOnly1	 = array('label' => '', 'class' => 'form-control', 'placeholder' => 'Contact Number', 'readOnly' => 'true');
	}
	$readOnly2	 = 'pointer-events: none';
	$js			 = "	if($.isFunction(window.refreshDriver)){
        window.refreshDriver();
        } else {
        alert('updated');
        }";
}
$title		 = ($model->drv_id_merge != '') ? 'Merge' : $title;
$ajax		 = Yii::app()->request->isAjaxRequest;
$hideAjax	 = '';
if (Yii::app()->request->isAjaxRequest)
{
	$panelCss	 = "col-xs-12 ";
	$hideAjax	 = 'hide';
	$isNew		 = true;
}
else
{
	$panelCss = "col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12";
}
?>
<div class="row" id="errShow" >
	<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12" >
		<div class="col-xs-12 mb20" style="color:#F00;text-align: center">
			<?php echo Yii::app()->user->getFlash('notice'); ?>
		</div>
	</div>    
</div>
<div class="row ">
	<div class="<?= $panelCss ?> new-booking-list pb10  " >
		<div class="row">
			<div class="col-xs-12">
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'driver-register-form',
					'enableClientValidation' => TRUE,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'		 => 'form-horizontal',
						'enctype'	 => 'multipart/form-data',
					),
				));
				/* @var $form TbActiveForm */
				?>
					
				<div class="panel panel-default panel-border">
					<div class="panel-body">
						<?= $form->hiddenField($model, 'drv_id') ?>
	                  <div class="text-danger" id="errordivcustom" style="display: none"></div>
						<div class="col-xs-12">
							<div class="text-danger" id="errordiv" style="display: none"></div>
							<div class="row">
								<div class="col-xs-12 col-sm-6"> 
									<div class="form-group">
										<label class="control-label" for="Drivers_drv_vendor_id1">Select Vendor</label>
										<?php
      					                    $this->widget('ext.yii-selectize.YiiSelectize', array(
											'model'				 => $model,
											'attribute'			 => 'drv_vendor_id1',
											'useWithBootstrap'	 => true,
											"placeholder"		 => "Select Vendor",
											'fullWidth'			 => false,
											'htmlOptions'		 => array('width' => '100%', 'readonly' => true,'id'	 => 'drv_vendor_id1'
											),
											'defaultOptions'	 => $selectizeOptions + array(
										    'onInitialize'	     => "js:function(){
                                                                          populateVendor(this, '{$model->drv_vendor_id1}');
                                                                   }",
										    'load'			     => "js:function(query, callback){
                                                                        loadVendor(query, callback);
                                                                   }",
										    'render'		         => "js:{
                                                                     option: function(item, escape){
                                                                      return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                                                    },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                        }
                                }",
								),
										));
										?>
										<span class="has-error"><? echo $form->error($model, 'drv_vendor_id1'); ?></span>
									</div>
								</div>
								<div class="col-xs-12 col-sm-6  <?= $mergeBorder ?>"> 
									<?= $form->textFieldGroup($model, 'drv_name', ['label' => 'Name ( * )']) ?>
								</div>
							</div>
							<div class="" id="dvr_detail" style="display: <?= $displayBlock ?>">
								<div class="<?= $hideAjax ?>">
									<div class="row">
										<div class="col-xs-12 pl0"> <h4>Proof of address</h4></div>
									</div>
									<div class="row" id="contactSelectDetails">
										<div class="col-xs-12 contact_div_details"> <label>Contact Info</label></div>
										<?php	
										      echo $form->hiddenField($model, 'drv_contact_id');
										      echo $form->hiddenField($model, 'drv_contact_name');
										?>
										<div class="col-xs-12 col-sm-6 contact_div_details hide" style="background-color: lightgray;" >
									    <label id="contactDetails"></label>
										</div>
										<?php if ($model->drv_id != "")	{?>
  										    <div class="col-xs-4 col-sm-3">
													<label>&nbsp;</label>
													<div><a class="btn btn-info modifyContact" target="_blank" href="<?= Yii::app()->createUrl('admin/contact/form', array('ctt_id' => $model->drv_contact_id, 'type' => 1)) ?>" >Modify Contact</a></div>
											</div>
									    <?php } ?>
									</div>
								</div>
								<div class="<?= $hideAjax ?>">
									<div class="row">
										<div class="col-xs-6 pl0 ">
                                           <?php if ($model->drv_approved == 1){
											           $is_approved = true;
											}
											else{
											          $is_approved = false;
											}?>
											<?= $form->checkboxListGroup($model, 'drv_approved', array('label' => '', 'widgetOptions' => array('data' => array(1 => '<b>Is Approved</b>'), 'htmlOptions' => ['checked' => $is_approved]), 'inline' => true)) ?>
										</div>
										<div class="col-xs-6 pl0 ">
											<?= $form->checkboxGroup($model, 'drv_is_uber_approved', ['label' => "Uber approved", 'groupOptions' => ['style' => 'margin:0px;padding:0px;', "class" => "checkbox-inline"]]) ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-6"> 
											<?php if ($model->drv_dob_date){
												$model->drv_dob_date = DateTimeFormat::DateToDatePicker($model->drv_dob_date);
											}
											echo $form->datePickerGroup($model, 'drv_dob_date', array('label'=> 'Date of Birth','widgetOptions'	 => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy'))));
											?>
										</div>
										<div class="col-xs-12 col-sm-6"> 
											<?php if ($model->drv_doj && $model->drv_doj != '1970-01-01'){
												$model->drv_doj = DateTimeFormat::DateToDatePicker($model->drv_doj);
											}
											else{
												$model->drv_doj = '';
											}
											echo $form->datePickerGroup($model, 'drv_doj', array('label' => 'Date of Joining','widgetOptions'	 => array('options' => array('autoclose' => true, 'endDate' => '+1d', 'format' => 'dd/mm/yyyy'))));
											?>
										</div> 
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-6 <?= $mergeBorder ?>"> 
											<?= $form->textFieldGroup($model, 'drv_zip') ?>
										</div>
									</div>
								</div>
									<div class="row">&nbsp;</div>
									<div class="row" style="text-align: center" id="DivDriverSubmit">
											<?php echo CHtml::Button('Submit', array('class' => 'btn btn-primary', 'name' => 'driversubmit')); ?>
									</div>
							</div>
						</div>
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>
		</div> 
	</div>
	<?php echo CHtml::endForm(); ?>
	
	<?php if(count($modelMerge)>0){
		?>
	<div class="col-lg-3 col-md-3 col-sm-10 col-md-offset-0 col-sm-offset-1 col-xs-12 pb10 border border-radius">
	<h4>Details of Driver to be imported : </h4>
	</div>
	<?php
		for($i=0;$i<count($modelMerge);$i++){?>
	
			<div class="col-lg-3 col-md-3 col-sm-10 col-md-offset-0 col-sm-offset-1 col-xs-12 pb10 border border-radius" id="mdrvdetails" style="">
				<div class="row">
					<div class="col-xs-12 bg bg-warning" style="color: #666">
						<ul>
							<li><b>Vendors Assigned </b> : <?php echo $modelMerge[$i]['vnd_name']?></li>
							<li><b>Driver Name</b> :  <?php echo $modelMerge[$i]['drv_name']?></li>
							<li><b>Country Code</b> : <?php echo $modelMerge[$i]['phn_phone_country_code']?></li>
							<li><b>Phone</b> : <?php echo $modelMerge[$i]['drv_phone']?></li>
							<li><b>Email</b> : <?php echo $modelMerge[$i]['drv_email']?></li>
							<li><b>Licence Number</b> : <?php echo $modelMerge[$i]['ctt_license_no']?></li>
							<li><button class="btn btn-primary btn-sm" style="text-align: center" onclick='copyDriver(<?=$i?>)' >Copy Driver</button></li>
						</ul>
					</div>  
                </div>
	        </div>
		<?php }	}?>
</div>
<script type="text/javascript">
 $(document).ready(function () {
	$('input[type="button"]').click(function(){
	      bootbox.confirm({ message: "Are you sure you want to merge driver?",
			buttons: {  
				 confirm: { 
					 label: 'Yes',
					 className: 'btn-success'
                   },
                 cancel: {
                     label: 'No',
                     className: 'btn-danger'
                }
                },
               callback: function (result) {
			    if(result){
					$('#driver-register-form').submit();
					return false;
				 }
            }
         });
	    });
	$("#contactDetails").html('<?= $model->drv_contact_name . ' | ' . $email . ' | ' . $phone ?>');
	$('#Drivers_drv_contact_id').val('<?= $model->drv_contact_id ?>');
	$('#Drivers_drv_contact_name').val('<?= $model->drv_contact_name ?>');
	$('#Drivers_drv_phone').mask('9999999999');	
	<?php if ($model->drv_id == ""){?>
	        $('#Drivers_drv_contact_id').val('');
			$('#Drivers_vnd_contact_name').val('');
			$('#contactSelectDetails').removeClass('hide');
			$('.searchContact ').removeClass('hide');
			$('.contact_div_details').addClass('hide');
			$(".viewcontctsearch").addClass('hide');
<?php }
else{?>
        $('#contactSelectDetails').removeClass('hide');
        $('.searchContact ').hide();
        $('.contact_div_details').removeClass('hide');
        $(".viewcontctsearch").hide();
		$(".addContact").hide();
<?php }?>

});
	
 function copyDriver(index) {
	
	var pausecontent = <?php echo json_encode($modelMerge); ?>;
    $("#Drivers_drv_name").val(pausecontent[index].drv_name!==null? pausecontent[index].drv_name:"");
    $("#Drivers_drv_zip").val(pausecontent[index].drv_zip!==null? pausecontent[index].drv_zip:"");
	$("#Drivers_drv_dob_date").val(moment(pausecontent[index].drv_dob_date, 'YYYY-MM-DD', true).isValid()? moment(pausecontent[index].drv_dob_date, 'YYYY-MM-DD', true).format('DD/MM/YYYY'):'');
	$("#Drivers_drv_doj").val(moment(pausecontent[index].drv_doj, 'YYYY-MM-DD', true).isValid()? moment(pausecontent[index].drv_doj, 'YYYY-MM-DD', true).format('DD/MM/YYYY'):'');
    var $select = $('select').selectize();
    var selectize = $select[0].selectize;
	selectize.setValue([pausecontent[index].vnd_id]);
   
	if(pausecontent[index].drv_is_uber_approved==1){
		$("#uniform-Drivers_drv_is_uber_approved span").addClass("checked");
	}
	else{
		 $("#uniform-Drivers_drv_is_uber_approved span").removeClass("checked");
	}
	$('#ytDrivers_drv_is_uber_approved').val(pausecontent[index].drv_is_uber_approved);
	
	if(pausecontent[index].drv_approved==1){
		$("#uniform-Drivers_drv_approved_0 span").addClass("checked");
	}
	else{
		 $("#uniform-Drivers_drv_approved_0 span").removeClass("checked");
	}
	$('#ytDrivers_drv_approved').val(pausecontent[index].rv_approved);
}	

</script>
