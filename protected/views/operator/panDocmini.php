<style>

	.bordered {
		border: 0px solid #dfe3e7!important;
	}
</style>
<div id="panDocPanel" role="tabpanel" data-parent="#accordionWrapa1" aria-labelledby="panDoc" class="collapse" style="">
	<a type="button" href="/operator/register" class="col-md-12 font-weight-bold p5"><i class="bx bx-arrow-back float-left "> </i> Go back </a>
	<?php
	$docTypePAN		 = Document::Document_Pan;
	$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'uploadDoc' . $docTypePAN,
		'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error',
			'afterValidate'		 => 'js:function(form,data,hasError){
								if(!hasError){ }	 }'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'action'				 => '/operator/uploaddoc',
		'htmlOptions'			 => array(
			'class'		 => 'form-horizontal',
			'enctype'	 => 'multipart/form-data',
			'onsubmit'	 => "return false;",
		),
	));
	/* @var $form TbActiveForm */
	$type			 = Document::model()->documentType();
	$documentTypePAN = $type[$docTypePAN];
	$PANfieldName	 = Document::getFieldByType($docTypePAN);
	?> 
	<?php echo $form->hiddenField($cttModel, 'ctt_id', array()) ?>
	<input type="hidden" name="formType" value="lic">
	<input type="hidden" name="isDCO" value="<?php echo $isDCO ?>">
	<?php echo $form->hiddenField($docPANModel, 'doc_type', array('value' => $docTypePAN)) ?>


	<div class="row" id="panblock">
		<a type="button" href="/operator/register" class="col-md-12">
			<div class="list-group-item pl10">
				<i class="bx bx-chevrons-left float-left text-success "></i>  PAN Card Info</div> 
		</a>
	</div>
	<div class="card card-body p10 ">			 

		<div class="row mt10">
			<div class="col-sm-12">
				<?php
				if ($docPANModel->doc_status == 1)
				{
					echo '<div class="form-control "><span class="font-weight-bolder  ">' . $cttModel->$PANfieldName . '</span></div>';
				}
				else
				{
					echo $form->textFieldGroup($cttModel, $PANfieldName, array());
				}
				?>
				<span id="errorctyname" style="color:#da4455"></span>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6">
				<?php
				$s3frontdata = $docPANModel->doc_front_s3_data;
				$filePath1	 = $docPANModel->doc_file_front_path;
				$s3FrontArr	 = json_decode($s3frontdata, true);
				$pathfront	 = "";
				$pathfront	 = Document::getDocPathById($docPANModel->doc_id, 1);
				//echo $docFrontLink = ($filePath1 != '' || $s3frontdata != '') ? '<a href="' . $pathfront . '" target="_blank">Attachment Link</a>' : 'Missing';
				if ($pathfront != '')
				{
					?>
					<br>Front Image<img src="<?php echo $pathfront ?>" class="imgHeight imgPANFront">
					<?php
				}

				if ($docPANModel->doc_status == 1)
				{
					echo '<div class="col-xs-12 form-control mt10 p10  text-center"><span class="text-success font-weight-bolder  ">Document approved</span></div>';
				}
				else
				{
					echo $form->fileFieldGroup($docPANModel, "doc_file_front_path", array('label' => 'Front Image Upload', 'widgetOptions' => ['htmlOptions' => ["accept" => "image/*", 'class' => 'docPANFront']]));
				}
				?>
			</div>
		</div>
	</div>


	<div class="col-sm-12">
		<div class=" mt20 mb10" style="text-align: center">
			<?php
			echo CHtml::Button("Submit", array('class' => 'btn btn-primary', 'id' => 'licbtn', 'onclick' => "uploadDoc(" . $docTypePAN . ",'" . $documentTypePAN . "');"));
			?>
		</div>

	</div>
	<?php $this->endWidget(); ?>
</div>  
<script type="text/javascript">
	     
	$('.docPANFront').change(function () {
		fileValidation(this, 'licbtn');
		previewDoc(this, 'imgPANFront');
	});
</script>


