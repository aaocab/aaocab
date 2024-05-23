<div class="row">
    <div class="col-lg-10 col-md-6 col-sm-8 pb10 new-booking-list" style="float: none; margin: auto">
		<div id="messagePhone"></div>
		   <div class="col-xs-12">
				<?php
				$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'contact-manage-form', 'enableClientValidation' => TRUE,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal'
					),
				));
				/* @var $form TbActiveForm */
				?>
				<div class="col-xs-12">
					<div class="panel panel-default panel-border">
						<div class="panel-body">
							
<!--							<div class="row">
								<div class="col-xs-12 col-sm-8 col-md-10">
									<label class="control-label" for="phn_phone_country_code">Country Code</label>
									<?php
												$this->widget('ext.yii-selectize.YiiSelectize', array(
													'model'				 => $model,
													'attribute'			 => 'phn_phone_country_code',
													'useWithBootstrap'	 => true,
													"placeholder"		 => "Country Code",
													'fullWidth'			 => false,
													'htmlOptions'		 => array(
													),
													'defaultOptions'	 => array(
														'create'			 => false,
														'persist'			 => false,
														'selectOnTab'		 => true,
														'createOnBlur'		 => true,
														'dropdownParent'	 => 'body',
														'optgroupValueField' => 'id',
														'optgroupLabelField' => 'pcode',
														'optgroupField'		 => 'pcode',
														'openOnFocus'		 => true,
														'labelField'		 => 'pcode',
														'valueField'		 => 'pcode',
														'searchField'		 => 'name',
														//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
														'closeAfterSelect'	 => true,
														'addPrecedence'		 => false,
														'onInitialize'		 => "js:function(){
															this.load(function(callback){
															var obj=this;
															xhr=$.ajax({
															url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
																dataType:'json',
																success:function(results){
																obj.enable();
																callback(results.data);
																},
																error:function(){
																callback();
																}});
																});
																}",
														'render'			 => "js:{
															option: function(item, escape){
															return '<div><span class=\"\">' + escape(item.name) +'</span></div>';
															},
															option_create: function(data, escape){
															return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
															}
															}",
													),
												));
												?>
								</div>
							</div>-->
							
							<div class="row">
								<div class="col-xs-12 col-sm-8 col-md-10">
									<?php echo $form->hiddenField($model,'phn_contact_id',array('value'=>$cttId));?>
									 <?= $form->textFieldGroup($model, 'phn_phone_no[]' ,array('label' => 'Phone', 'widgetOptions' => array('htmlOptions' => array('value' => "",'placeholder'=>"Phone",'id'=>"phn_phone_no")))); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
                <div class="" style="text-align: center">
					<?php echo CHtml::Button("Submit", array('class' => 'btn btn-primary','id'=>'ajaxSubmitPhone')); ?>
                </div>
            </div>
			<?php $this->endWidget(); ?>
		
		
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    
       $("#ajaxSubmitPhone").click(function (){
		   $("#messagePhone").html("");
		   var phone_address = $("#phn_phone_no").val();
		   var phone_country = '91';
		  // var phone_country = $("#ContactPhone_phn_phone_country_code").val();
		   var filter = /[1-9]{1}[0-9]{9}/;
		   if(phone_country==""){
			   $("#messagePhone").html('<div class="alert alert-block alert-danger"><p>Please select country code!</p>');
			   return false;
			}
            if(phone_address==""){
			   $("#messagePhone").html('<div class="alert alert-block alert-danger"><p>Please provide your phone number!</p>');
			   return false;
			}
		    else if(phone_address.length<10){
				$("#messagePhone").html('<div class="alert alert-block alert-danger"><p>Phone number lenght must be 10 number!</p>');
				return false;
			}
			else if(!filter.test(phone_address)){
				$("#messagePhone").html('<div class="alert alert-block alert-danger"><p>Phone number format is invalid!</p>');
				return false;
			}
			$("#messagePhone").html("");
            var href = '<?= Yii::app()->createUrl("admin/contact/alternatephone", array('ctt_id' => $cttId))?>';
                $.ajax({
                'url': href,
				'type': 'POST',
                'dataType': "json",
                'data': {"phone_address":phone_address,"phone_country":phone_country,'YII_CSRF_TOKEN':"<?= Yii::app()->request->csrfToken?>"},
                "success": function (data) {
					var html="";
					$("#messagePhone").html("");
					if(data.status=="success"){
						html='<div class="alert alert-block alert-success" id><p>'+data.message+'</p></div>';
						$("#messagePhone").html(html);
					}
					else{
						html='<div class="alert alert-block alert-danger" id><p>Please fix the following input errors:</p><ul>'; 
						for (var msg in data.message) {
							html+='<li>'+data.message[msg]+'</li>';
                        }
						html+='</ul></div>';
						$("#messagePhone").html(html);
					}
                }
            });	 
        });
});
</script>