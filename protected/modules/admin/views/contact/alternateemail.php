<div class="row">
    <div class="col-lg-10 col-md-6 col-sm-8 pb10 new-booking-list" style="float: none; margin: auto">

        
		<div id="message"></div>
		
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
							<div class="row">
								<div class="col-xs-12 col-sm-8 col-md-10">
									<?php echo $form->hiddenField($model,'eml_contact_id',array('value'=>$cttId));?>
									 <?= $form->textFieldGroup($model, 'eml_email_address[]' ,array('label' => 'Email', 'widgetOptions' => array('htmlOptions' => array('value' => "",'placeholder'=>"Email",'id'=>"eml_email_address")))); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
                <div class="" style="text-align: center">
					<?php echo CHtml::Button("Submit", array('class' => 'btn btn-primary','id'=>'ajaxSubmit')); ?>
                </div>
            </div>
			<?php $this->endWidget(); ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
      $("#ajaxSubmit").click(function ()	{ 
			$("#message").html("");
			var email_address = $("#eml_email_address").val();
			var regExpression = /\b[a-zA-Z0-9\u00C0-\u017F._%+-]+@[a-zA-Z0-9\u00C0-\u017F.-]+\.[a-zA-Z]{2,}\b/;
			if(email_address==""){
				$("#message").html('<div class="alert alert-block alert-danger"><p>Please provide your emailId!</p>');
				return false;
			}
			else if(!regExpression.test(email_address)){
				$("#message").html('<div class="alert alert-block alert-danger"><p>Please provide your correct emailId format!</p>');
				return false;
			}
		    $("#message").html("");
		    var href = '<?= Yii::app()->createUrl("admin/contact/alternateemail", array('ctt_id' => $cttId))?>';
                $.ajax({
                'url': href,
				'type': 'POST',
                'dataType': "json",
                'data': {"email_address":email_address,'YII_CSRF_TOKEN':"<?= Yii::app()->request->csrfToken?>"},
                "success": function (data) {
					var html="";
					$("#message").html("");
					if(data.status=="success"){
						html='<div class="alert alert-block alert-success" id><p>'+data.message+'</p></div>';
						$("#message").html(html);
					}
					else{
						html='<div class="alert alert-block alert-danger" id><p>Please fix the following input errors:</p><ul>'; 
						for (var msg in data.message) {
							html+='<li>'+data.message[msg]+'</li>';
                        }
						html+='</ul></div>';
						$("#message").html(html);
					}
                }
            });	 
        });
});
</script>