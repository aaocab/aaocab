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
</style>
<?php $eventId = Yii::app()->getRequest()->getParam('eventId'); ?>

<?php
$bookingLogEvent = BookingLog::mapEvents();
$oldEventId		 = (array_keys($bookingLogEvent, $eventId));
$payDocData		 = BookingPayDocs::model()->getVoucherByBkgId($model['bkg_id'], $oldEventId[0]);
$imageUrl		 = $payDocData['bpay_image'];

//if (strpos($imageUrl, 'attachments') !== false) {
//    $url			 = Yii::app()->request->baseUrl;
//    $pageURL		 .= $url . $imageUrl;
//}else{
//$Url		 = AttachmentProcessing::ImagePath(DIRECTORY_SEPARATOR . $imageUrl);
//			$spiltPath	 = explode("/assets", $Url);
//			$pageURL	 = "/assets" . $spiltPath[1];
//}
$pageURL = BookingPayDocs::getDocPathById($payDocData['bpay_id']);


if (isset($model['bkg_id']) && $model['bkg_id'] <> '')
{
?>
<div class="row">
	<div class="col-xs-12 mb20">
		<div style="text-align:center" class="below-buttons">
			<div class="btn-group1">
				<?php if(isset($imageUrl) != ''){?>
				    <img src="<?php echo $pageURL?>" alt="View Voucher Details"></img>
				<?php }else{ ?>
					<p>No Voucher Found.</p>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php }else{ ?>
    <div class="row">
		<div class="col-xs-12 mb20">
			<div style="text-align:center" class="below-buttons">
				<div class="btn-group1">
                  <p>No Booking Found.</p>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
