<style type="text/css">
    .modal {  overflow-y:auto;}
    .flex {
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		flex-wrap: wrap;
	}
    .rounded-margin{ margin: 0 15px;}
    @media (min-width: 992px){
        .modal-lg {
            width: calc(56.55% - 10px)!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .control-label{
        font-weight: bold
    }   
    .box-design1{ background: #8DCF8A; color: #000; padding: 10px;}
    .box-design1a{ background: #ccffcc; color: #000;}
    .box-design2{ background: #F8A6AC; color: #000;  padding: 10px;}
    .box-design2a{ background: #ffcccc; color: #000; }
    .label-tab label{ margin:0 17%!important}
    .label-tab .form-group{ margin-bottom: 0;}
    .bordered {
        border: 1px solid #ddd;
        min-height: 45px;
        line-height: 1.2em;
        margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10px;
        padding-bottom: 10px;
    }
</style>

<?php

$version = Yii::app()->params['customVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);

$time = Filter::getExecutionTime();
$docType = VehicleDocs::model()->doctypeTxt;
$vdocmodel	 = VehicleDocs::model()->findByVhcId($model['vvhc_vhc_id']);


$GLOBALS['time'][9] = $time;
$vehicleData = VendorVehicle::model()->getLouInfoById($model['vvhc_vnd_id'], $model['vvhc_vhc_id']); 
$panDocData  = Document::model()->findByPk($model['vvhc_owner_pan_id']); 
$url			 = Yii::app()->request->hostInfo;
$licenseDocData =Document::model()->findByPk($model['vvhc_owner_license_id']);
$panImage		 = $panDocData['doc_file_front_path'];
$panImageUrl		 = $url ."/". $panImage;
if (substr_count($panImageUrl, "attachments") > 0)
{
    $panImageUrl = $url ."/". $panImage;
}
else
{
     $panImageUrl = AttachmentProcessing::ImagePath($panImage);
}

$licenseImage		 = $licenseDocData['doc_file_front_path'];
$licenseImageUrl	 = $url."/".$licenseImage;
if (substr_count($licenseImageUrl, "attachments") > 0)
{
    $licenseImageUrl	 = $url . "/".$licenseImage;  
}
else
{
    $licenseImageUrl = AttachmentProcessing::ImagePath($licenseImage);
}
?>
<div class="row bordered">
    <div class="col-xs-12 col-sm-12 col-md-12">
	<?php if (Yii::app()->user->hasFlash('success'))
	{ ?>
    	<div class="alert alert-block alert-success">
	    <?php echo Yii::app()->user->getFlash('success'); ?>
    	</div>
	<?php } ?>

	<?php
	$selectizeOptions	 = ['create'		 => false, 'persist'		 => true, 'selectOnTab'		 => true,
	    'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	    'optgroupValueField'	 => 'id', 'optgroupLabelField'	 => 'text', 'optgroupField'		 => 'id',
	    'openOnFocus'		 => true, 'preload'		 => false,
	    'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	    'addPrecedence'		 => false,];
	$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	    'id'			 => 'lou-popup-form',
	    'enableClientValidation' => true,
	    'clientOptions'		 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	    ),
	    'enableAjaxValidation'	 => false,
	    'errorMessageCssClass'	 => 'help-block',
	    'htmlOptions'		 => array(
		'class' => '',
	    ),
	));
	/* @var $form TbActiveForm */
	?>
        <div class="col-xs-12  pb20">
	    <div class="col-xs-12  col-md-6 pt10">
		  <div class="col-xs-12 border">
	             <b>Vehicle Number:</b>
		      <?php if($vehicleData['vhc_number'] != '')
		    { ?>
    		      <?php echo $vehicleData['vhc_number']; ?>
		    <?php }
		    else
		    { ?>
    		    <p>No Vehicle Number Found.</p>
                   <?php } ?>
		  </div>
	    </div> 
	    <div class="col-xs-12   col-md-6 pt10">
		  <div class="col-xs-12 border">
	             <b>Vehicle Owner:</b>
		     <?php if($vehicleData['vhc_owner'] != '')
		    { ?>
    		    <?php echo $vehicleData['vhc_owner']; ?>
		    <?php }
		    else
		    { ?>
    		    <p>No Vehicle Owner Found.</p>
                   <?php } ?>
		  </div>
	    </div> 
	</div>
	<div class="col-xs-12  pb20">
            <div class="col-xs-6 col-sm-6 col-md-6 pt10">
                <div class="col-xs-12 border">
		     <b>Licence Number:</b> 
		     <?php if($vehicleData['ctt_license_no'] != '')
		    { ?>
    		    <?php echo $vehicleData['ctt_license_no']; ?>
		    <?php }
		    else
		    { ?>
    		    <p>No Licence Number Found.</p>
                   <?php } ?>
		</div>
	    </div> 
	    <div class="col-xs-6 col-sm-6 col-md-6 pt10">
                <div class="col-xs-12 border">
		     <b>Pan Number:</b> 
		     <?php if($vehicleData['ctt_pan_no'] != '')
		    { ?>
    		    <?php echo $vehicleData['ctt_pan_no']; ?>
		    <?php }
		    else
		    { ?>
    		    <p>No Pan Number Found.</p>
                   <?php } ?>
		</div>
	    </div> 
	</div>   
		<div class="col-xs-12  pb20">
            <div class=" col-md-4 pt10">
                <div class="col-xs-12 border">
					<b>License: </b><br>

					<?php if ($licenseDocData['doc_file_front_path'] != '')
					{
						?>
						<img src="<?php echo $licenseImageUrl ? $licenseImageUrl : "/images/no-image.png" ?>" alt="View License Details" style="padding-top:10px;width:150px;height:100px;"
 onclick="showpic('<?= $model['vvhc_owner_license_id']; ?>','License')"></img>

					<?php
					}
					else
					{
						?>
						<p>No License Found.</p>
<?php } ?>
				</div>
            </div>
			<div class=" col-md-4 pt10 ">
                <div class="col-xs-12 border">
					<b>Pan: </b><br>
					<?php if ($panDocData['doc_file_front_path'] != '')
					{
						?>
						<img src="<?php echo $panImageUrl ? $panImageUrl : "/images/no-image.png" ?>" alt="View Pan Details"  style="padding-top:10px;width:150px;height:100px;"
 onclick="showpic('<?= $model['vvhc_owner_pan_id']; ?>','Pan')"></img>
					<?php
					}
					else
					{
						?>
						<p>No Pan Found.</p>
<?php } ?>
				</div>
            </div>

		</div>
</div>
</div>



<div class="row bordered">
	<div >

		<?php
		foreach ($vdocmodel as $value)
		{
			?>
<div class="col-xs-4 ">
	<?php
	$picid = $value["vhd_id"];
	echo $docType[$value['vhd_type']];
        $filePath = VehicleDocs::getDocPathById($value['vhd_id']);
	echo "<br />";
	?>
	<img src="<?= $filePath ?>" style="padding-top:10px;width:150px;height:100px;" onclick="openpic('<?= $picid ?>','<?= $docType[$value['vhd_type']] ?>')">

				</div>

		<?php } ?>


	</div>
</div>

<div class="row">
	<?php if($model['vvhc_lou_approved'] != 1)
		    { ?>
	<div class="col-xs-4 col-md-4 mt20 pt5 mb10 text-center">
               <a class="btn btn-success btn-sm mb5 mr5" id="btnApproved" onclick="statusChange(1)" title="Approved" style="">Approved</a>	
        </div>
	<?php } ?>
	
	<?php if($model['vvhc_lou_approved'] != 2)
		    { ?>
	<div class="col-xs-4 col-md-4 mt20 pt5 mb10 text-center">
               <a class="btn btn-danger btn-sm mb5 mr5" id="btnReject" onclick="statusChange(2);" title="Rejected" style="">Rejected</a>	
        </div>
	<?php } ?>
	
<?php $this->endWidget(); ?>
    </div>


<script>
    function statusChange(obj)
    {
	vvhc_id = '<?= $model->vvhc_id ?>';
	vvhc_owner_license_id = '<?= $model->vvhc_owner_license_id ?>';
	vvhc_owner_pan_id = '<?= $model->vvhc_owner_pan_id ?>';
	var href = '<?= Yii::app()->createUrl("admin/vendor/viewloudetails"); ?>';
	var loadPageUrl = '<?= Yii::app()->createUrl("admin/vendor/loulist"); ?>';
	
	$.ajax({
            "url": href,
            "type": "GET",
           "dataType": "text",
            "data": {"vvhc_id": vvhc_id, "vvhc_lou_approved": obj},
	        "async": false,
        "success": function (data) {
	       bootbox.alert("Lou status  Updated sucessfully."),
               location.href = loadPageUrl; 
	    },
	    error: function (data) {
		if(obj==1)
		{
		    if(vvhc_owner_license_id == "" || vvhc_owner_pan_id =="")
		    {
			    alert("You need PAN or DL for approval.");
		    }
		    alert('Sorry error occured');
		}
		else{
		    alert('Unable to Reject.');  
		}
        }
	})
    }
     
    function openpic(pid, doctype) {

        $.ajax({
            "type": "GET",
            "dataType": "html",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/showdoc')) ?>",
            "data": {"vhdid": pid},

            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    className: "bootbox-xs",
                    title: "<span class='text-center'>" + doctype + "</span>",
                    size: "large",
                    onEscape: function () {
                        box.modal('hide');
                    }
                }).on('shown.bs.modal', function () {
					box.removeAttr("tabindex");
                });
            }
        });
    }
function showpic(id,doctype) {
        $.ajax({
            "type": "GET",
            "dataType": "html",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/document/showdocument')) ?>",
            "data": {"docid": id},

            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    className: "bootbox-xs",
                    title: "<span class='text-center'>" + doctype + "</span>",
                    size: "large",
                    onEscape: function () {
                        box.modal('hide');
                    }
                }).on('shown.bs.modal', function () {
					box.removeAttr("tabindex");
                });
            }
        });
    }
</script>
