<div class="row register_path p20">
    <div class="col-xs-12 col-md-5 p0 col-md-offset-2">
		<div class="panel panel-default">
			<div class="panel-heading font18"><b>Avail attractive offers and discounts!</b></div>
			<div class="panel-body">
        <?php
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'calander-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
			'class' => '',
				),
			));
		/* @var $form TbActiveForm */
		?>
			<div class="form-group">
				<?= $form->textFieldGroup($userModel, 'search_name', array('label' => 'Name','widgetOptions' => ['htmlOptions' => ['placeholder' => 'Name','required' => true,]])) ?>	
			</div>
			<div class="form-group">
				<?= $form->textFieldGroup($userModel, 'search_email', array('label' => 'Email ID','widgetOptions' => ['htmlOptions' => ['placeholder' =>'Email ID','required' => true,]])) ?>
			</div>
			 <div class="col-xs-12 col-md-6 pl0">
                <button class="btn btn-success" name="submit" id="downloadBtn">Submit</button>
            </div>
			<button id="skipBtn" type="submit" class="btn btn-default pull-right">Skip</button>
		<?php $this->endWidget(); ?>
		</div>
    </div>
	</div>
	<div class="col-md-3">
		<img src="/images/gozoCalendar.jpg" alt="Gozo Calendar" class="img-responsive">
	</div>
</div>
<script type="text/javascript">
    $("#skipBtn").click(function () {
			
			location.href = "https://www.gozocabs.com/images/2020/2/Long_Weekend_Calendar.pdf";
		
		});
	$("#calander-form").submit(function () {
			
		var name =$('#Users_search_name').val();
		var email =	$('#Users_search_email').val();
		$href = "<?= Yii::app()->createUrl('index/gozoCalendarData') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			data: {"user_name": name, "user_email": email},
			success: function (data)
			{
				var obj = $.parseJSON(data);
				if (obj.success == true)
				{
				    location.href = "https://www.gozocabs.com/images/2020/2/Long_Weekend_Calendar.pdf";
				}
				else
				{
					alert('Please Retry');
				}
			}
		});
		});
</script>
