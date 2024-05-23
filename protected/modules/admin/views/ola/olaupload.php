<div class="row">
	<?
    $selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false];

	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'booking-form', 'enableClientValidation' => true,
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
		'htmlOptions'			 => array(
			'enctype' => 'multipart/form-data',
		),
	));
	?>

    <div class="col-md-3 col-sm-6 col-xs-12">
				<?php
               
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'obu_partner_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Partner list", 
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				    'onInitialize'	 => "js:function(){
                                  populatePartner(this, '{$model->obu_partner_id}');
                                }",
                 'load'			 => "js:function(query, callback){
                                loadPartner(query, callback);
                                }",
				 'render'		 => "js:{
                                option: function(item, escape){
                                return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                },
                                option_create: function(data, escape){
                                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                }
                                }",
					),
				));
				
				echo $form->error($model, 'obu_partner_id' , ['class' => 'text-danger']); ?>  
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
		        <?= $form->fileFieldGroup($model, 'fileImage', array('label' => '', 'widgetOptions' => array('htmlOptions' => []))) ?>
    </div>

    <div class="col-xs-3 newButtonLine">                           
		<input type="submit" value="Upload"  class="btn btn-primary" >
    </div>

	<?php $this->endWidget(); ?>

    <div class="col-xs-3 newButtonLine ">      <?
		if ($olaData > 0)
		{
			?>                     
			<a  type="button" href="/admpnl/ola/executeuploaded"  style="display:none" class="btn btn-primary">Proceed</a>
<? } ?>        
    </div>


</div>
<div class="text-danger"><?
	echo $errorMsg;

	if ($olaData > 0)
	{
		echo "<br>";
		echo $olaData . " records not executed. Press proceed button.";
	}
	?></div>
<div>
    <?
	if ($msg != '')
	{
		$msg1 = json_decode($msg);
		foreach ($msg1 as $res)
		{
			echo "<br>";
			echo $res;
		}
	}
	?>
</div>
