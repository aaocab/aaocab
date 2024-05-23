<style>

	.form-horizontal .form-group{
		margin-left: 0;
		margin-right: 0;
	}


</style>

<div class="row">
    <div class="col-lg-8 col-md-6 col-sm-8 pb10" style="float: none; margin: auto">
		<div class="row">   
			<div class="col-xs-12">
				<div class="panel panel-default panel-border">
					<div class="panel-heading h3 mt0">Add Tag</div>
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'add_tag',
						'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error'
						),
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => 'form-horizontal',
						),
					));
					?>		
					<div class="panel-body">			 
						<div class="row">   
							<div class="col-xs-12 col-sm-6 col-md-6">
								<?= $form->textFieldGroup($model, 'tag_name', array('widgetOptions' => array('htmlOptions' => ['style' => 'text-transform:uppercase']))) ?>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<?= $form->textAreaGroup($model, 'tag_desc', array()) ?>
							</div>
						</div>
						<div class="row">   
							<div class="col-xs-12 col-sm-6 col-md-4">
								<?= $form->checkboxGroup($model, 'tag_booking', array('widgetOptions' => array('htmlOptions' => []))) ?>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">
								<?= $form->checkboxGroup($model, 'tag_user', array('widgetOptions' => array('htmlOptions' => []))) ?>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">	
								<?= $form->checkboxGroup($model, 'tag_partner', array('widgetOptions' => array('htmlOptions' => []))) ?>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">	
								<?= $form->checkboxGroup($model, 'tag_vendor', array('widgetOptions' => array('htmlOptions' => []))) ?>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">	
								<?= $form->checkboxGroup($model, 'tag_driver', array('widgetOptions' => array('htmlOptions' => []))) ?>
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4">	
								<?= $form->checkboxGroup($model, 'tag_cab', array('widgetOptions' => array('htmlOptions' => []))) ?>
							</div>
						</div>
						<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-4">	
							<?php
							$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
								
								
			$colorArr = [
						'#00FFFF'	 => 'Aqua',
						'#000000'	 => 'Black',
						'#0000FF'	 => 'Blue',
						'#CD7F32'	 => 'Bronze',
						'#E8D9C4'	 => 'Beast150',
						'#8F4B0C'	 => 'Brown',
						'#9E1900'	 => 'Burgundy',
						'#A9A9A9'	 => 'Dark gray',
						'#D6C985'	 => 'Gold',
						'#CCCCCC'	 => 'Gray',
						'#3B9128'	 => 'Green',
						'#00FF00'	 => 'Lime',
						'#7777FF'	 => 'Light blue',
						'#800000'    =>'Maroon',
						'#ff00ff'    =>'Magenta',
						'#000080'	 => 'Navy',
						'#FFA500'	 => 'Orange',
						'#800080'    =>'Purple',
						'#C70039'    => 'Red',
						'#C0C0C0'	 => 'Silver',
						'#008080'	 => 'Teal',
						'#FFFFFF'	 => 'White',
						'#FFFF00'    =>'Yellow',
					];
$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'tag_color',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select color",
						'fullWidth'			 => false,
						'data'				 => $colorArr,
						'defaultOptions'	 => $selectizeOptions + array(
					'render' => "js:{
								option: function(item, escape){
								return '<div style=\" text-shadow: 0.5px 0.5px ' + invertHex(escape(item.id))+'\ ;color: ' + invertHex(escape(item.id))+'\ ;background:' + escape(item.id)+'\"><span class=\"\" > ' + escape(item.text) +'</span></div>';
								},
								option_create: function(data, escape){
								return '<div>' +'<span class=\"mr5 ml5\">' + escape(data.text) + '</span></div>';
								} }",
						)
					));
							?>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div class="" style="text-align: center">
							<?php
							echo CHtml::submitButton("Add", array('class' => 'btn btn-primary'));
							?>
						</div>
					</div>
					<?php $this->endWidget(); ?>
				</div>
			</div>
		</div>
	</div>		
</div>   
<script>
function invertHex(hex) {
		if (hex.indexOf('#') === 0) {
			hex = hex.slice(1);
		}
		// convert 3-digit hex to 6-digits.
		if (hex.length === 3) {
			hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
		}
		if (hex.length !== 6) {
			throw new Error('Invalid HEX color.');
		}
		var r = parseInt(hex.slice(0, 2), 16),
				g = parseInt(hex.slice(2, 4), 16),
				b = parseInt(hex.slice(4, 6), 16);

		return (r * 0.299 + g * 0.787 + b * 0.514) > 186
				? '#000000'
				: '#FFFFFF';

	}
</script>