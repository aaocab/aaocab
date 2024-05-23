
<?
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
?>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body panel-no-padding">
                    <div id="sentsuccess" class="text-success h4"></div>
                    <div id="pformdiv">
						<?= CHtml::beginForm('', "post", ['id' => "promoForm", 'class' => "form"]); ?>

                        <input type="hidden" id="bk_id" name="bk_id" value="<?= $bkid ?>"/>
                        <input type="hidden" id="useremail" name="useremail" value="<?= $email ?>"/>
                        <input type="hidden" id="userid" name="userid" value="<?= $userid ?>"/>
                        <div class="form-group">
							<?
							//   $data = Promotions::model()->getListJson($userid);
							if ($email != '')
							{
								?>
								<p>Discount code will be sent to <?= $email ?></p>
								<?
							}
							else
							{
								?>
								<p>No email address is provided by user</p>
								<label>Please enter email address to send discount code</label>

								<input type="text" class="form-control" id="email" name="email" value=""/>
								<div id="email-error" class="text-danger"></div>
							<? }
							?>
                            <label>Write Discount code</label> 

                            <label class="control-label" for="type">Booking Type</label>
							<?
							$dataBookType = Promos::model()->getActivePromoCodeJson();

							//$form->dropDownList($model, 'bkg_booking_type', $bookingType, array('empty' => 'Select Booking Type', 'label' => 'Booking Type', 'class' => 'form-control')) 
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $promomodel,
								'attribute'		 => 'prm_code',
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataBookType)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Promo Code')
							));
							?>
							<? /*  <input type="text" class="form-control" id="promocode" name="promocode" value=""/> */ ?>
                            <div id="code-error" class="text-danger"></div>
                        </div>
                        <div class="Submit-button" style="margin-top: 5px;">
                            <button type="submit" class="btn btn-primary" id="sendcode">SUBMIT</button>
                        </div>
						<?= CHtml::endForm() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#sendcode').click(function (e) {
        var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
        $userid = $('#userid').val();
        $bkgid = $('#bk_id').val();
        $email = $('#useremail').val();
        if ($email == '') {
            $email = $('#email').val();
        }

        $code = $('#<?= CHtml::activeId($promomodel, "prm_code") ?>').val();
        $error = 0;
        if (!ck_email.test($email)) {
            $('#email-error').text('Please enter valid email address');
            $error = 1;
        }
        href = '<?= Yii::app()->createUrl('admin/promos/validatecode') ?>';
        if ($code != '' && $email != '' && $error == 0) {
            $.ajax({
                url: href,
                dataType: "json",
                data: {"email": $email, 'code': $code, 'userid': $userid, 'bkgid': $bkgid},
                "success": function (data) {
                    if (data.status == 'true') {
                        $('#pformdiv').hide();
                        $('#sentsuccess').text('Email sent successfully');
                    }
                    if (data.status == 'false') {
                        $('#code-error').text('Please check Discount code');
                    }

                }

            });
        } else {
            if ($code == '') {
                $('#code-error').text('Please enter code');
            }
            if ($email == '') {
                $('#email-error').text('Please enter email address');
            }

        }
        e.preventDefault();
    });

    $('#promocode').change(function () {
        $('#code-error').text('');
    });
    $('#email').change(function () {
        $('#email-error').text('');
    });



</script>
