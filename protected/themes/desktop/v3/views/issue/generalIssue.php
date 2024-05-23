<?php 
$form	 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'reportissueDetails',
	'action'				 => 'issue/reportIssue',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => 'form-horizontal',
		'onsubmit'	 => 'return reportIssueDetails(this);'
	),
		));
/* @var $form CActiveForm */
    
?>
<input type="hidden" name="booking_id" id="booking_id" value="<?= $bkgId ?>">
<input type="hidden" name="rpi_id" id="rpi_id" value="<?= $model->rpi_id ?>">
<input type="hidden" name="rpi_type" id="rpi_type" value="<?= $rpiType ?>">
<input type="hidden" name="is_issue_desc" id="is_issue_desc" value="1">
<div class="container p0">
    <div class="col-12">
    <div class="alert alert-danger mb-2 text-center alertcabclass hide" role="alert"></div>
    <div class="row">
					<div class="col-sm-12 mb5">
						
						<div class="row mt10">
							<div class="col-12 mb5">
								<label><?php echo $model->rpi_name; ?> :</label>
							</div>
						</div>
						<div class="row">
							<div class="col-12 mb5">
								<?php echo $form->textArea($model, 'report_issue_desc', ['class' => "form-control", 'placeholder' => "Enter Details", "cols" => "50", "rows" => "3"]) ?>
							</div>
						</div>
                        <div class="row mt10">
							<div class="col-12 mb5 text-center">  
                            <button type="submit" class="btn btn-lg btn-primary btn-sm">Submit</button>
							</div> 
						</div>	 
					</div>

	</div>
   </div>
</div>
 <?php   
    $this->endWidget();
?>

<script type="text/javascript">
   
    function reportIssueDetails()
	{   //debugger;
		var form = $("form#reportissueDetails");
		$.ajax({
				"type": "POST",
				"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('issue/ReportIssue')) ?>",
				"data": form.serialize(),
//				"beforeSend": function()
//				{
//					blockForm(form);
//				},
//				"complete": function()
//				{
//				},
				"success": function(data2)
				{   //debugger;
					var data = "";
                    var isJSON = false;

                    try
                    {
                        data = JSON.parse(data2);
                        isJSON = true;
                    }
                    catch(exception)
                    {

                    }

                    if(!isJSON)
                    {
                      $('#reportIssueModal').removeClass('fade');
                      $('#reportIssueModal').css('display', 'block');
                      $('#reportIssueModelContent').html(data2);
                      $('#reportIssueModelContent').removeClass("hide");
                      $('#reportIssueModal').modal('show');
                    }
                    else
                    {

                       if(data.success)
                       {
//                           $('#reportIssueModal').addClass('fade');
//                           $('#reportIssueModal').css('display', 'none');
//                           $('#reportIssueModal').modal('hide');
//                           message = "Issue reported successfully. You will receive a call back shortly.";
//                           toastr['info'](message, {
//                            closeButton: true,
//                            tapToDismiss: false,
//                            timeout: 1000000
//                           });
                            if(!$('.alertcabclass').hasClass('hide'))
                            {
                               $('.alertcabclass').html('');
                               $('.alertcabclass').addClass('hide');
                            }
                       }
                       else
                       {
                           var error = data.errors;
//                           message = error;
//                           toastr['error'](message, 'Failed to process!', {
//                               closeButton: true,
//                               tapToDismiss: false,
//                               timeout: 500000
//                          });
                            if($('.alertcabclass').hasClass('hide'))
                            {
                               $('.alertcabclass').html(error);
                               $('.alertcabclass').removeClass('hide');
                            }

                       }
                       return false;
                    }
                },
                error: function(xhr, ajaxOptions, thrownError)
                {
                    if(xhr.status == "403")
                    {
                        handleException(xhr, function()
                        {

                        });
                    }
                }
		});
		return false;
	}
</script>