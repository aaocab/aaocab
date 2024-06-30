<style>
    .list_div{
        background: #fff;
        -webkit-box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);
        -moz-box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);
        box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);
        margin: 5px 0 0 0;
    }
    .list_heading{ background: #EFEFEF; overflow: hidden;}
    .gray-color{ color: #848484;}
    .gozo_green{ background: #48b9a7;}
    .gozo_bluecolor{ color: #0766bb;}
    .gozo_greencolor{ color:#48b9a7;}
    .gozo_red{ background: #f34747;}
    .text_right{ text-align: right;}
    .margin_top{ margin-top: 40px;}
    .car_img{ overflow: hidden;}
    .car_img img{ width: 100%;}
    @media (max-width: 768px) {
        .text_right{ text-align: center;}
        .margin_top{ margin-top: 10px;}
    }
	.btn-open {
		width: 150px;
		background: none;
		padding: 10px 20px;
		color: #042B4A;
		margin: 0 auto;
		border: 1px solid #042B4A;
		font-size: 14px;
		display: block;
		font-weight: bold;
		float: left;
		text-transform: uppercase;
	}
	.btn-open:hover {
		background-color: #042B4A;
		color:#fff;
	}
	#developerReport
	{
		min-height:280px;
	}
</style>
<div class="row" id="developerReport">
<?php
foreach ($model as $key => $val)
{
	$count = $key % 2;
	$divId = "main-div-".$key;
	?>
	
	<div class="col-xs-12 col-sm-8 col-md-6" id="<?php echo $divId;?>">
		<div class="list_div">
			<div class="row">
				<div class="col-xs-6 col-sm-8">
					<h4 class="mt20 pl10">
					   <?= $val ?>
					</h4>
				</div>
				<div class="col-xs-2 col-sm-4 text_right mt10 mb10"> 
					<button type="button" class="btn-open" onclick="openLink('<?php echo $key ?>');">Open</button>
				</div>
			</div>
		</div>
	</div>
	
<?php } ?>
	</div>
<script type="text/javascript">
function openLink(linkId)
{
	if(linkId == 1)
	{
	  var href = '<?= Yii::app()->createUrl("aaohome/developerReport/mmtPushReport"); ?>';
	}
	else if(linkId == 2)
	{
		var href = '<?= Yii::app()->createUrl("aaohome/developerReport/mmtReviewReport"); ?>';
	}
	else if(linkId == 3)
	{
		var href = '<?= Yii::app()->createUrl("aaohome/developerReport/accountMismatchReport"); ?>';
	}
	else if(linkId == 4)
	{
		var href = '<?= Yii::app()->createUrl("aaohome/developerReport/advanceMismatchReport"); ?>';
	}
	else if(linkId == 5)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/mmtReport"); ?>';
	}
	else if(linkId == 6)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/tripPurchaseMissing"); ?>';
	}
	else if(linkId == 7)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/tripPurchaseMultipleEntry"); ?>';
	}
	else if(linkId == 8)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/cashCollectedMissing"); ?>';
	}
	else if(linkId == 9)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/cashCollectedMultipleEntries"); ?>';
	}
	else if(linkId == 10)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/advanceMultipleEntries"); ?>';
	}
	else if(linkId == 11)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/partnerCommMultipleEntries"); ?>';
	}
	else if(linkId == 12)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/partnerCommMissing"); ?>';
	}
	else if(linkId == 13)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/partnerReceivableReports"); ?>';
	}
	else if(linkId == 14)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/penaltyTypeReports"); ?>';
	}
	else if(linkId == 15)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/bookingAmountMismatchReports"); ?>';
	}
	else if(linkId == 16)
	{
		var href = '<?= Yii::app()->createUrl("/aaohome/developerReport/driverCollectionMismatchReports"); ?>';
	}
	window.open(href, '_blank');
}
<?php
foreach ($model as $key => $val)
{
	$count = 1;
	$count = $key % 2;
	?>
	<?php if($count == 1){ ?>
		$(document).ready(function(){
			$('#main-div-1').css("float", "left");
			$('#main-div-3').css("float", "left");
			$('#main-div-5').css("float", "left");
		});
    <?php } ?>	
	<?php if($count == 0){ ?>	
		$(document).ready(function(){
			$('#main-div-2').css("float", "right");
			$('#main-div-4').css("float", "right");
		});
	<?php } ?>
<?php } ?>
</script>