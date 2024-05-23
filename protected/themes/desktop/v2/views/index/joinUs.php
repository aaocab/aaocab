<style>
    .btn-2s{ background: #ff6801!important; color: #fff; padding: 50px 80px; border-radius: 25px 100px 25px 25px;}
    .btn-2s:hover{ background: #e96002!important; color: #ffb98d;}
</style>
<div class="row title-widget">
    <div class="col-12">
        <div class="container">
            <?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>
<div class="row bg-gray">
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
            <div class="bg-white-box pt30 pb30">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div id="VendorOuterDiv" class="col-12">
                                <h3 style="color: #000000;"><span id="AgentOuterDivText"></span></h3>
                            </div>  
                            <div id="agentJoin" class="col-12 text-center">
                                <div class="Submit-button">
                                    <button type="button" class = "btn btn-default btn-height btn-1" onclick="validateJoinUs(2)">If you want Gozo to give you business <br> <b>Join as a Taxi operator </b> and attach your taxi <br><br/> अगर आप चाहते हैं कि Gozo आपको बुकिंग दे तो एक <br> टैक्सी ऑपरेटर के रूप में हमसे अपनी टैक्सी जोड़ें </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="row">
                            <div id="VendorOuterDiv">
                                <div class="col-12">
                                    <h3 style="color: #000000;"><span id="VendorOuterDivText"></span></h3>
                                </div>
                            </div>  
                            <div id="vendorJoin" class="col-12 text-center">
                                <div class="Submit-button">
                                    <button type="button" class = "btn btn-default btn-height btn-2s" onclick=" validateJoinUs(1);">If you will create bookings <br> for GOZO  <strong>Join US as an AGENT</strong> <br><br/> यदि आप Gozo के लिए बुकिंग बनाएंगे <br> तो एक एजेंट (booking agent) के रूप में हमसे जुड़ें </button>
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
                <br><br/><p><i class="fa fa-paper-plane mr10 mb10"  style="font-size:15px; color: #36abe8;"></i> <a href="https://t.me/gozocabs" target="_blank" style="color:#000;"> Join the GozoCabs channel on telegram. All drivers are welcome to the GozoCabs telegram. We provide bookings to registered drivers on telegram too.</a></p>
            </div>
        </div>
        <div class="clear"></div>
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
