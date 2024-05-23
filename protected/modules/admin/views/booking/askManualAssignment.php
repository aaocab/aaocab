<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row">
    <div class="col-xs-12">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'manualAssignment', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => '',
			),
		));
		/* @var $form TbActiveForm */
		?>

		<div class="row" >
			<div class="col-xs-12 col-sm-12">
				<div class="form-group">
					<label class="control-label" for="">Vendor:</label>
					<?php
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'bkg_vendor_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Vendor",
						'fullWidth'			 => false,
						'options'			 => array('allowClear' => true),
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->bkg_vendor_id}');
                                                }",
					'load'			 => "js:function(query, callback){
                        loadVendor(query, callback);
                        }",
					'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
						),
					));
					?>
				</div>
			</div>
		</div>

		<?php echo $form->hiddenField($model, 'bkg_id'); ?>
		<div class="row" >
			<div class="col-xs-12 col-sm-12"> 
				<div class="form-group">
					<?= $form->textAreaGroup($model, 'bkg_remark', array('label' => 'Remark:', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Enter remark", 'rows' => 5, 'cols' => 120]))) ?>
				</div>
			</div>

		</div>

		<div class="row " >
			<div class="col-xs-6 col-sm-4">
				<button class="btn btn-info" onclick="ManualAssignment()" type="button"  name="Search" style="width: 185px;">submit</button>
			</div>
			<div class="col-xs-6 col-sm-4">
				&nbsp;
			</div>
		</div>
		<?php $this->endWidget(); ?>

    </div>
</div>

<script>
    function ManualAssignment()
    {
        if ($("#Booking_bkg_vendor_id").val() == "")
        {
            bootbox.alert("Please select vendor from dropdown");
			return false;
        } else
        {
            $('#manualAssignment').submit();
        }
    }

</script>