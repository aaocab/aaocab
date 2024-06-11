<style>
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .top-buffer{padding-top: 10px;}
    .modal-dialog{ width: 95%!important;}
	.light-orang-bg{ background: #ffe1cc!important}
	.light-blue-bg{ background: #dfecf4!important}
	.proceed-new-btn{ font-size: 13px;}
.btn-height{ min-height: 300px;}
</style>

<div class="row pt20" id="joinus">
	<div class="col-xs-12 col-md-8 col-md-offset-2">
		<div class="row" >

			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'agentJoin-form',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error',
					'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                           $.ajax({
							type: "POST",
							url: "ajax.php",
							data: dataString,
							success: function(r) 
						});'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => 'form-horizontal'
				),
			));
			/* @var $form TbActiveForm */
			?>
			<div class="col-xs-12 col-sm-6">
				<div class="row">
					<div id="VendorOuterDiv" class="col-xs-12">
						<h3 style="color: #000000;"><span id="AgentOuterDivText"></span></h3>
					</div>  
					<div id="agentJoin" class="col-xs-12 text-center">
						<div class="Submit-button">
							<button type="button" class = "btn btn-default p50 btn-height light-blue-bg black-color" onclick="validateJoinUs(2)">If you want Gozo to give you business <br> <b>Join as a Taxi operator </b> and attach your taxi <br><br/> अगर आप चाहते हैं कि Gozo आपको बुकिंग दे तो एक <br> टैक्सी ऑपरेटर के रूप में हमसे अपनी टैक्सी जोड़ें 
							
							</button>
						</div>
						</div>
				</div>
			</div>
		

			<div class="col-xs-12 col-sm-6">
				<div class="row">
					<div id="VendorOuterDiv">
						<div class="col-xs-12">
							<h3 style="color: #000000;"><span id="VendorOuterDivText"></span></h3>
						</div>
					</div>  
					<div class="row" id="vendorJoin" style="align: center">
						<div class="col-xs-12 text-center">
							<div class="Submit-button">
								<button type="button" class = "btn btn-default p50 btn-height light-orang-bg black-color" onclick=" validateJoinUs(1);">If you will create bookings <br> for GOZO  <strong>Join US as an AGENT</strong> <br><br/> यदि आप Gozo के लिए बुकिंग बनाएंगे <br> तो एक एजेंट (booking agent) के रूप में हमसे जुड़ें </button>
							</div>
						</div>
					</div>
				</div>
			</div>
	<?php $this->endWidget(); ?>
		</div>
	</div>
</div>

<div  id="info">
<div align="center">
<br><br/><p><i class="fa fa-paper-plane mr10 mb10"  style="font-size:15px; color: #36abe8;"></i> <a href="https://t.me/aaocab" target="_blank" style="color:#000;"> Join the aaocab channel on telegram. All drivers are welcome to the aaocab telegram. We provide bookings to registered drivers on telegram too.</a></p>
</div>
</div>
<script type="text/javascript">
    function validateJoinUs(type) {
		$href = (type == 1) ? "/index/agentjoin" : "/index/vendorjoin";
		$.ajax({'type': 'POST',
            'url': $href,
            'dataType' : "html",
			"async": false,
			'data' : {"YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val()},
            success: function (data) {
                $('#info').html(data);
                $('#joinus').hide();
            }
        });

    }
</script>
