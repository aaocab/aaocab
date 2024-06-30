<style>
    .checkbox-inline{
        padding-left: 0 !important;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .new-booking-list label{ font-size: 11px;}
	.usertype,
	.cash,
	.coin,
	.fixed{ 
		padding: 10px; 
		margin: 10px; 
		border: 1px solid silver; 
	}
</style>
<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];

$dateV	 = $model->vus_valid_till != '' ? $model->vus_valid_till : date('Y-m-d H:i:s');
?>
<div class="row">
		<div class="col-xs-12 col-md-11 col-lg-11  new-booking-list" style="float: none; margin: auto">

			<div class="row">

				<div class="col-xs-12">
					<?php
					
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'add-partner-form', 'enableClientValidation' => TRUE,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError){
										if(!hasError){
											$.ajax({
											"type":"POST",
											"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/user/addvoucher')) . '",
											"data":form.serialize(),
													"dataType": "json",
													"success":function(data1){
															if(data1.success)
															{
																if(data1.success == 3)
																{
																	$(".msgb1").html("Voucher Already Added With This Customer");
																	setTimeout(function(){ $(".msgb1").html(""); }, 3000);
																} 
																else if(data1.success == 4)
																{
																	$(".msgb2").html("Voucher Add Limit Exceeded");
																	setTimeout(function(){ $(".bootbox-close-button").click(); }, 3000);
																}
																else if(data1.success == 5)
																{
																	$(".msgb2").html(data1.error);
																	setTimeout(function(){ $(".msgb2").html(""); }, 3000);
																}
																else {
																	$(".msgb1").html("Voucher Added Sucessfully");
																	setTimeout(function(){ $(".bootbox-close-button").click(); }, 3000);
																}															
																																
															}
															else
															{
																$(".msgb2").html(data1.error);
															}
													},
											});
										}
									}'
						),
						// Please note: When you enable ajax validation, make sure the corresponding
						// controller action is handling ajax validation correctly.
						// See class documentation of CActiveForm for details on this,
						// you need to use the performAjaxValidation()-method described there.
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => 'form-horizontal'
						),
					));
					/* @var $form TbActiveForm */
					?>
<div class="row">
            <div class="col-xs-12">
					<div class="panel panel-default">
						<div class="panel-body panel-border">
						<div class="row mb15">
						<div class="col-xs-12 col-sm-6 col-md-6 h5 mt20">
							<div class="msgb1" style="color: green"></div>                
							<div class="msgb2" style="color: red"></div>   	
							<?= $form->hiddenField($model, 'vus_user_id',array('value'=>$uid)) ?>
						</div>
						</div>
							<div class="row mb15">
							
								<div class="col-xs-6 col-sm-6">
									
										<label>Voucher *</label>
										<?php
										/*			
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'vus_vch_id',
											'val'			 => $model->vus_vch_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($voucherList)),
											'htmlOptions'	 => array('style' => 'width:100%;', 'placeholder' => 'Select Voucher', 'class' => 'input-group','id'=> 'selcpart1')
										));*/
										
										
										?>
										<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'vus_vch_id',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select Voucher",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width'	 => '100%',
											'id'	 => 'selcpart1',
											'class'  => 'route-focus'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
										populateSource(this, '{$model->vus_vch_id}');
											}",

									'load'			 => "js:function(query, callback){
										loadSource(query, callback);
										}",
                                                                   
									'render'		 => "js:{
										option: function(item, escape){
										return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
										},
										option_create: function(data, escape){
										return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
										}
										}",
										),
									));
								?>
									    
								</div>
								<div class="col-xs-6 col-sm-6">
									
										<label class="control-label">Valid Till</label>
										<?=	$form->datePickerGroup($model, 'vus_valid_till', array('label' => '','widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateV)))), 'prepend'=> '<i class="fa fa-calendar"></i>'));
										?>
									
								</div>
								
								</div>
							

						</div>

						<div class="panel-footer" style="text-align: center">
							<?php echo CHtml::submitButton($isNew, array('class' => 'btn btn-primary btnsub1')); ?>
						</div>


					</div>
					<?php $this->endWidget(); ?>
</div></div>
				</div>
			</div> 
		</div>
	</div>
<script>
$sourceList = null;	

$( ".btnsub1" ).click(function() {
  $(".msgb1").html("");
  $(".msgb2").html("");
});

function populateSource(obj, userId)
    {
        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
				var url = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('aaohome/user/voucherlistbyquery')) ?>';
				
                xhr = $.ajax({
                    url: url,
                    dataType: 'json',
                    data: {
                    },
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(userId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } 
			else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue(userId);
            }
        });
    }
	function loadSource(query, callback)
    {
      
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('aaohome/user/voucherlistbyquery')) ?>?&q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			global: false,
			error: function ()
			{
				callback();
			},
			success: function (res)
			{
				callback(res);
			}
		});
        
    }
</script>