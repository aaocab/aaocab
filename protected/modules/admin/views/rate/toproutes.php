<div class="row">
	<?
	$date	 = date('Y-m-d', strtotime('next saturday'));
	$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'routerate', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
	    if(!hasError){
		 
	       return true;
	    }
	}'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		//'action'		 => Yii::app()->createUrl('booking/summary'),
		'htmlOptions'			 => array(
//	    'enctype' => 'multipart/form-data',
		// 'class' => 'form-horizontal',
		),
	));
	?>

    <div class="col-xs-3 newButtonLine ">   
        Number of routes
        <input type="number" value="<?= $limit ?>" id="limitval" name="Booking[limitval]" class="form-control">  
    </div>

    <div class="col-xs-3 newButtonLine ">   
        Date of pickup
		<?=
		$form->datePickerGroup($model, 'bkg_pickup_date', array('label'			 => '',
			'widgetOptions'	 => array('options'		 => array('autoclose'	 => true,
					'format'	 => 'dd/mm/yyyy'), 'htmlOptions'	 => array('required'		 => true, 'placeholder'	 => 'Followup Date',
					'value'			 => date('d/m/Y', strtotime($date)), 'id'			 => 'followup_date',
					'class'			 => 'datepicker')),
			'prepend'		 => '<i class="fa fa-calendar"></i>'));
		?>

    </div>
    <div class="col-xs-3 newButtonLine mt20">                           
        <input type="submit"   class="btn btn-primary">
    </div>



	<?php $this->endWidget(); ?>

    <div class="col-xs-3 newButtonLine ">      <?
		if ($dataLoaded > 0)
		{
			?>                     
			<a type="button" href="/aaohome/rate/exporttoproute"  class="btn btn-primary mt20">Export <?= $dataLoaded ?> records</a>
		<? } ?>        
    </div>

</div>
<div class="text-danger"><?
	if ($msg == '')
	{

		echo $dataLoaded . " records exist in the table.";
	}
	if ($dataLoaded == 0)
	{
		echo " No record  in the table.";
	}
	?>
</div>
<div class="text-primary"><?
	if ($msg != '')
	{
		echo $msg;
	}
	?>

</div>

