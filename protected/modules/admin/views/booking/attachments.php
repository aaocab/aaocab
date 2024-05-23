
<div class="panel">
    <div class="panel-body">
        <div class="col-xs-12">
            <div class="row">
                <div class="panel-body">
                    <?php
                    if (count($recordset) == 0) {
                        echo "No document uploaded. ";
                    }
                    foreach ($recordset as $value) {

                        $arr = BookingPayDocs::model()->docTypeArr;
                       /// $baseURL = Yii::app()->params['fullBaseURL'];
						
                        if (strpos($value['bpay_image'], 'attachments') !== false) {
                            $url = Yii::app()->request->baseUrl;
                            $ImagePath = $url . $value['bpay_image'];
                            $hrefUrl = "";
                        } else {
                            $DS = DIRECTORY_SEPARATOR;
                            $Url = AttachmentProcessing::ImagePath($DS . $value['bpay_image']);
                            $spiltPath = explode("/assets", $Url);
                            $ImagePath = "/assets" . $spiltPath[1];
                            $hrefUrl =  Yii::app()->createUrl('track/file', ['id' => $value['bpay_id'], 'hash' => Yii::app()->shortHash->hash($value['bpay_id'])]);
                        }
                        ?>
                        <div class="col-xs-3 mt30">
                            <div class="text-center">
                                <b><?php echo $arr[$value['bpay_type']]; ?></b>
                                <br>
                                <a href="<?php echo $hrefUrl; ?>" target="blank">
									<img src="<?php echo $hrefUrl; ?>" class="pic-bordered pic btn p0 pt10">
	                                </a>
                            </div>
                       </div>
<?php } ?>     </div>
            </div>


            <div class="row">
                <div class="panel-body">
<?php
if (count($boostDocs) == 0) {
    echo "No verification document uploaded. ";
}
#print_r($boostDocs);
foreach ($boostDocs as $value) {
$url = Yii::app()->request->hostInfo;
    $arr = VehicleDocs::model()->doctypeTxt;
    $baseURL = Yii::app()->params['fullBaseURL'];
    if (strpos($value['bpay_image'], 'attachments') !== false) {
        $url = Yii::app()->request->hostInfo;
        $ImagePath = $url . $value['bpay_image'];
    } else {
        $DS = DIRECTORY_SEPARATOR;
		$boostImage = $value['bpay_image'];
       // $ImagePath = AttachmentProcessing::ImagePath($boostImage);
		$ImagePath = BookingPayDocs::getDocPathById($value['bpay_id']);
        
        //$spiltPath = explode("/assets", $Url);
        //$ImagePath = "/assets" . $spiltPath[1];
    }
    ?>
                        <div class="col-xs-3 mt30">
                            <div class="text-center">
                                <b><?php echo $arr[$value['bpay_type']]; ?></b>
                                <br>
                                <img src="<?php echo $ImagePath; ?>"  class="pic-bordered pic btn p0 pt10">                         
                            </div>
                        </div>

                    <?php } ?>    
                </div>
            </div>
        </div>
    </div></div>

<style type="text/css">
    .pic{
        max-width: 175px;
        max-height: 175px;
    }
</style>