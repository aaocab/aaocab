<!--<div class="row title-widget">
    <div class="col-12">
        <div class="container">
            <?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>-->
<div class="container-fluid mt15 n">
<div class="row bg-gray justify-center">
    <div class="col-12">
        <div class="container pt30 pb30" id="joinus">

            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id'                     => 'agentJoin-form',
                'enableClientValidation' => true,
                'clientOptions'          => array(
                    'validateOnSubmit' => true,
                    'errorCssClass'    => 'has-error',
                    'afterValidate'    => 'js:function(form,data,hasError){
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
                'enableAjaxValidation'   => false,
                'errorMessageCssClass'   => 'help-block',
                'htmlOptions'            => array(
                    'class' => 'form-horizontal'
                ),
            ));
            /* @var $form CActiveForm */
            ?>
                <div class="row mt40">
                    <div class="col-12 col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div id="VendorOuterDiv" class="col-12 text-center">
										<h3><span id="AgentOuterDivText"></span></h3>
									</div>  
									<div id="agentJoin" class="col-12 text-center p0 font-16">
<p><img src="/images/img-2022/car.png" class="mb10" alt="Join as a Taxi operator" title="Join as a Taxi operator"><br>If you want Gozo to give you business <br> <b>Join as a Taxi operator </b> and attach your taxi <br><br/> अगर आप चाहते हैं कि Gozo आपको बुकिंग दे तो एक <br> टैक्सी ऑपरेटर के रूप में हमसे अपनी टैक्सी जोड़ें </p>
										<div class="Submit-button text-center">
											<button type="button" class="btn btn-lg btn-primary mb-1 text-uppercase" onclick="validateJoinUs(2)">Attach your taxi</button>
										</div>
									</div>
								</div>
							</div>
						</div>
                    </div>

                    <div class="col-12 col-md-6">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div id="VendorOuterDiv" class="col-12">
											<h3 style="color: #000000;"><span id="VendorOuterDivText"></span></h3>
									</div>  
									<div id="vendorJoin" class="col-12 text-center font-16">
<p><img src="/images/img-2022/icon24.png" class="mb10" alt="Join US as an AGENT" title="Join US as an AGENT"><br>If you will create bookings <br> for GOZO  <strong>Join US as an AGENT</strong> <br><br/> यदि आप Gozo के लिए बुकिंग बनाएंगे <br> तो एक एजेंट (booking agent) के रूप में हमसे जुड़ें </p>
										<div class="Submit-button text-center">
											<button type="button" class = "btn btn-lg btn-primary mb-1 text-uppercase" onclick=" validateJoinUs(1);">Become a reseller</button>
										</div>
									</div>
								</div>
							</div>
						</div>
                    </div>
                </div>
            <?php $this->endWidget(); ?>
        </div>
        <div id="info">
            <div align="center">
<!--                <br><br/><p><img src="/images/bx-paper-plane.svg" alt="img" width="20" height="20" class="mb10"> <a href="https://t.me/gozocabs" target="_blank" style="color:#000;"> Join the GozoCabs channel on telegram. All drivers are welcome to the GozoCabs telegram. We provide bookings to registered drivers on telegram too.</a></p>-->
				<br>            
			</div>
        </div>
        <div class="clear"></div>
    </div>
</div>
</div>
<script type="text/javascript">
    function validateJoinUs(type) {
        $href = (type == 1) ? "/index/agentjoin" : "/index/vendorjoin";
        $.ajax({'type': 'POST',
            'url': $href,
            'dataType': "html",
            "async": false,
            'data': {"YII_CSRF_TOKEN": $('input[name="YII_CSRF_TOKEN"]').val(), "telegramId": "<?= $_REQUEST['telegramId']; ?>"},
            success: function (data) {

                $('#info').html(data);
                $('#joinus').hide();
            }
        });
    }
</script>
