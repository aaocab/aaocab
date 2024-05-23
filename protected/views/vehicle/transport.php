<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
	.selectize-dropdown [data-selectable] {
		cursor: pointer;
		overflow: hidden;
		padding: 5px;
	}

    .upper
    {
        text-transform: uppercase;
    }
</style>
<?php
$vtypeList			 = VehicleTypes::model()->getParentVehicleTypes(2);
$vTypeData			 = VehicleTypes::model()->getJSON($vtypeList);
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>

<div id="transportPanel" role="tabpanel" data-parent="#accordionWrap2" aria-labelledby="trnsport" class="collapse" style="">
	<a type="button" href="/vehicle/info" class="col-md-12 font-weight-bold p5"><i class="bx bx-arrow-back float-left "> </i> Go back </a>

	<div class="row"  >
		<a type="button" href="/vehicle/info" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i>Enter vehicle details </div> 
		</a>
	</div>
	<div class="  card-body p10" >


		<?php
		/* @var $form TbActiveForm */
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'vehicle-form',
			'enableClientValidation' => TRUE,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
					if(!hasError){
						$.ajax({
							"type":"POST",
							"dataType":"json",
							async: false,
							"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('vehicle/validatetransport')) . '",
							"data":form.serialize(),
							"beforeSend": function () {
								ajaxindicatorstart("");
							},
							"complete": function () {                     
								ajaxindicatorstop();
							},
							"success":function(data1){
								if(data1.success){ 				
								location.href = data1.url;					 												 
									 return false;
								} else{			
									var errorstr;
									if (data1.hasOwnProperty("errors")) 
									{	 
				                        errorstr = data1.errors.join("</li><li>");
				                    }                    
									var message = "<div class=\'errorSummary\'><ul style=\'list-style-type: none\'><li>" + errorstr + "</li></ul></div>";
				                    showInfo(message); 
								} 
							},                    
						});
					}									
				}'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal',
			),
		));
		?>  
		<?= $form->hiddenField($vhcModel, 'vhc_id') ?>
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class='form-group cityinput'>
					<label>Vehicle type</label>
					<?php
//					$this->widget('booster.widgets.TbSelect2', array(
//					'model' => $vhcModel,
//					'attribute' => 'vhc_type_id',
//					'val' => $vhcModel->vhc_type_id,
//					'asDropDownList' => FALSE,
//					'options' => array('data' => new CJavaScriptExpression($vTypeData)),
//					'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Type')
//					));

					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $vhcModel,
						'attribute'			 => 'vhc_type_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Model",
						'fullWidth'			 => false,
						'htmlOptions'		 => array('width' => '100%'),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
					populateCabModel(this, '{$vhcModel->vhc_type_id}');
						}",
					'load'			 => "js:function(query, callback){
									loadCabModel(query, callback);
									}",
					'render'		 => "js:{
								option: function(item, escape){
								return '<div><span  class=\"\"><i class=\"fa fa-taxi mr5 ml5\"></i>' + escape(item.text) +'</span></div>';
								},
								option_create: function(data, escape){
								return '<div>' +'<span class=\"mr5 ml5\">' + escape(data.text) + '</span></div>';
								}
								  }",
						),
					));
					?>
					<span class="has-error"><? echo $form->error($vhcModel, 'vhc_type_id'); ?></span>
				</div>
			</div> 
			<div class="col-xs-12 col-md-6">
				<?php echo $form->textFieldGroup($vhcModel, 'vhc_number', array('label' => 'Vehicle number', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'XX XX XX XXXX', 'class' => "upper"]))) ?>
			</div>
			<div class="col-xs-12 col-md-6">
				<?php echo $form->numberFieldGroup($vhcModel, 'vhc_year', array('label' => 'Vehicle year', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Year', 'class' => "upper", "min" => date("Y") - 10, "max" => date("Y")]))) ?>
			</div>
			<div class="col-xs-12 col-lg-6 ">
				<div class='form-group cityinput'>
					<label>Vehicle colour</label> 
					<?php
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
						'#FFA500'	 => 'Orange',
						'#C0C0C0'	 => 'Silver',
						'#000080'	 => 'Navy',
						'#008080'	 => 'Teal',
						'#FFFFFF'	 => 'White'
					];

					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $vhcModel,
						'attribute'			 => 'vhc_color',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select colour",
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
		<div class="" style="text-align: center">
			<?php
			echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary'));
			?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
<script  type="text/javascript">



	$('#Vehicles_vhc_number').mask('AA 0Z YYY 0000', {
		translation: {'Z': {
				pattern: /[0-9]/, optional: true
			}, 'Y': {
				pattern: /[A-Za-z]/, optional: true
			}, 'X': {
				pattern: /[0-9A-Za-z]/, optional: true
			}, 'A': {
				pattern: /[A-Za-z]/, optional: false
			}, '0': {
				pattern: /[0-9]/, optional: false
			},
		},
		greedy: false,
		placeholder: "AA 12 AA 1234",
		clearIfNotMatch: false
	});


	$sourceList = null;

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

	function padZero(str, len) {
		len = len || 2;
		var zeros = new Array(len).join('0');
		return (zeros + str).slice(-len);
	}

	function populateCabModel(obj, vhtId) {

		obj.load(function (callback) {
			var obj = this;
			if ($sourceList == null) {
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allCabModelbyquery', ['id' => ''])) ?>' + vhtId,
					dataType: 'json',
					data: {
						// city: cityId
					},
					//  async: false,
					success: function (results) {
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue(vhtId);
					},
					error: function () {
						callback();
					}
				});
			} else {
				obj.enable();
				callback($sourceList);
				obj.setValue(vhtId);
			}
		});
	}


	function loadCabModel(query, callback) {
		//	if (!query.length) return callback();
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allCabModelbyquery')) ?>?q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			global: false,
			error: function () {
				callback();
			},
			success: function (res) {
				callback(res);
			}
		});
	}
	function showInfo(message) {
		toastr["error"](message, "Failed to proceed !", {
                        closeButton: true,
                        tapToDismiss: false,
                        timeout: 500000
		                    });
	}
</script>
