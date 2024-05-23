<style type="text/css">
    .form-group {
        margin-bottom: 0;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .modal-backdrop{
        height: 650px !important;
    }   

    .error{
        color:#ff0000;
    }

    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;
    }

    .bg-warning{
        color: #333333;
    }


    .bordered {
        border:1px solid #ddd;
        min-height: 45px;
        line-height: 1.2;
        text-align: center;
    }

    .form-control{
        border: 1px solid #a5a5a5;
        text-align: center;

    }

    .modal-title{
        text-align: center;
        font-size: 1.5em;
        font-weight: 400;
    }
</style>
<div class="row">
    <div class="col-xs-12 text-center h3 mt0">
        <label for="type" class="control-label"><span style="font-weight: normal; font-size: 15px;">Booking Id: <b><?= $model->bkg_booking_id ?></b></span></label>
    </div>
    <div class="col-xs-12 text-center h3 mt0">
	    <label for="type" class="control-label"><span style="font-weight: normal;font-size: 20px;">Pickup time can only be modified by +/- 2hours</span></label>
	</div>
</div>
<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'pickuptime-form',
					'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error',
						'afterValidate'		 => 'js:function(form,data,hasError){
                    if(!hasError){				
								if(!checkValidation())
								{
								   return false;
								}
								
							}
                    }'
					),
					'enableAjaxValidation' => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal'
					),
				));
		/* @var $form TbActiveForm */
		?>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12 p5">
				<label  class="col-xs-12 col-sm-4 control-label ">Current Pickup Time:</label>
				<div class="col-xs-6 col-sm-7 "> Date:<?= date("d-m-Y",strtotime($model->bkg_pickup_date));?></div>
				<div class="col-xs-6 col-sm-7 "> Time:<?= date("h:i A",strtotime($model->bkg_pickup_date));?></div>
			</div>
            <div class="col-xs-12"> 
				<div class="form-group">
					<label  class="col-xs-4 col-sm-4 control-label">Change By:</label>
						<div class="form-group col-xs-4 col-sm-4 ">
                               <?=$form->hiddenField($model, 'bkg_id')?>
							   <?php
									$timeSchedulePrePost		 = Filter::scheduleTimePrePost();
									$jsonPrePostData			 = Filter::getJSON($timeSchedulePrePost);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'timePrePost',
										'val'			 => $model->timePrePost,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($jsonPrePostData)),
										'htmlOptions'	 => array('placeholder' => 'Select Pre Or Post')
									));
								?>
						</div>	
						<div class="form-group col-xs-4 col-sm-4 ">
								<?php
									$timeSchedule		 = Filter::scheduleTimeInterval();
									$jsonData			 = Filter::getJSON($timeSchedule);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'timeSchedule',
										'val'			 => $model->timeSchedule,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($jsonData)),
										'htmlOptions'	 => array('placeholder' => 'Select Time')
									));
								?>
						</div>	
				</div>
			</div>
		</div>
	</div>
    <div class="row">
		<div class="col-xs-12 text-center pb10">
			<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30','onclick'=>'return savetime();')); ?>
		</div>
    </div>
</div> 
<?php $this->endWidget(); ?>
<script>

	function savetime()
	{
		var isMatched = '<?= $model->bkgBcb->bcb_trip_type; ?>';
		if(isMatched == 1)
		{
			if( !confirm('Are you sure that you want to change time as this booking has match booking.')){
			 event.preventDefault();
			 $("#pickuptime-form").modal("hide");
			 bootbox.hideAll();
			 return false;
			}
			else{
				saveReschedulePickupTime();
			}
		}
		else{
			saveReschedulePickupTime();
		}
		return false;
	}
	
	function saveReschedulePickupTime()
	{
		var href = '<?= Yii::app()->createUrl("admin/booking/savepickuptime"); ?>';
		$.ajax({
			"url": href,
			"type": "GET",
			"dataType": "json",
			"data": {"bkg_id": $('#Booking_bkg_id').val(), "timePrePost": $('#Booking_timePrePost').val(), "timeSchedule":$('#Booking_timeSchedule').val()},
			"success": function (data1)
			{

				if(data1.success)
				{
					alert("Pickup time updated successfully.");	
					location.reload();					 
				}
				else
				{
					alert(data1.message);	
				}

			}
		});
		return false;
	}

</script>