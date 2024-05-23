
<style>

    .review
    {
        margin-top: 20px;color: #f00;font-size: 13px;display: none;text-align: center;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12">

			<?
			$error		 = '';
			$errorshow	 = ($error == '') ? 'hide' : '';
			?>
            <div class="panel" >                
                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll1">

                        <div style="width: 100%; padding: 3px; overflow: auto; line-height: 10px; font: normal arial; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
                            <div class="form" >
								<?php
								$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'rating-form',
									'enableAjaxValidation'	 => true,
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'afterValidate'		 => 'js:function(form,data,hasError){
                                            
                                      
                                        }'
									),
								));
								?>
								<?= $form->errorSummary($model); ?>
								<?= $form->hiddenField($model, 'rtg_booking_id') ?>

                                <div class="col-xs-12" style="min-height:100px">
                                    <div class="form-group pt30">
                                        <div class="col-xs-7 text-right"><?= $model->getAttributeLabel('rtg_csr_customer') ?></div>
                                        <div class="col-xs-5">
											<?
											$this->widget('CStarRating', array(
												'model'		 => $model,
												'attribute'	 => 'rtg_csr_customer',
												//   'callback' => 'function(){checkrating($(this))}',
												'minRating'	 => 1,
												'maxRating'	 => 5,
												'starCount'	 => 5,
											));
											?> 
                                        </div>
                                    </div>
                                    <div id="custErr" class="review">Please rate our Customer Experience.</div>  

                                    <div class="form-group pt20">
                                        <div class="col-xs-7 text-right"><?= $model->getAttributeLabel('rtg_csr_vendor') ?></div>
                                        <div class="col-xs-5">
											<?
											$this->widget('CStarRating', array(
												'model'		 => $model,
												'attribute'	 => 'rtg_csr_vendor',
												// 'callback' => 'function(){checkdvrrating($(this))}',
												'minRating'	 => 1,
												'maxRating'	 => 5,
												'starCount'	 => 5,
											));
											?> 
                                        </div>

                                    </div>
                                    <div id="vndErr" class="review">Please rate our Vendor.</div>  



                                    <div class="form-group pt20">
                                        <div class="col-xs-12">
											<?= $form->textAreaGroup($model, 'rtg_csr_review', array('widgetOptions' => array('htmlOptions' => array()))) ?>
                                            <div>You have <span id="charleftcount">1000 characters left.</span> (Maximum characters: 1000)</div>
                                        </div>
                                        <div id="revErr" class="review">Please give your feedback.</div>
                                        <div id="overErr" class="review mt5">Max 1000 characters.</div>
                                    </div> 
                                    <div class="col-xs-12 p15 text-center ">
                                        <button class="btn btn-primary" type="submit" value="Rate" tabindex="2" >Rate</button>
                                    </div>
                                </div>
								<?php $this->endWidget(); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.rating-cancel').addClass('hide');

    });

    $('#rating-form').submit(function (event) {
        var $error = 0;

        if (!$('#Ratings_rtg_csr_customer_0').hasClass('star-rating-on')) {
            $('#custErr').show();
            $error += 1;
        } else {
            $('#custErr').hide();
            $error += 0;
        }
        if (!$('#Ratings_rtg_csr_vendor_0').hasClass('star-rating-on')) {
            $('#vndErr').show();
            $error += 1;
        } else {
            $('#vndErr').hide();
            $error += 0;
        }

        if ($error == 0) {


            $.ajax({
                type: 'POST',
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/rating/ajaxverify')) ?>",
                "data": $('#rating-form').serialize(),
                success: function (data)
                {
                    if (data.result == 'true') {
                        $(".bootbox").hide();
                    }


//                            bootbox.hideAll();
//                            var returnUrl = <? //= CJavaScript::encode(Yii::app()->createUrl('admin/booking/list'))         ?>;
//                            if (window.opener) {
//                                if (returnUrl) {
//                                    window.opener.location.href = returnUrl;
//                                } else {
//                                    window.opener.location.reload();
//                                }
//                                window.close();
//                            } else {
//                                window.location.href = returnUrl ? returnUrl : '/';
//                            }


                }

            });
        }
        event.preventDefault();
    });
    $('#Ratings_rtg_csr_review').keyup(function () {
        rev = $('#Ratings_rtg_csr_review').val();
        revlength = rev.length;
        if (revlength > 1000) {
            $('#overErr').show();
            $('#charleftcount').text('entered ' + (revlength - 1000) + ' characters  extra.');
        } else {
            $('#overErr').hide();
            $('#charleftcount').text((1000 - revlength) + ' characters  left.');
        }
    });

    $('#Ratings_rtg_csr_review').change(function () {
        rev = $('#Ratings_rtg_csr_review').val();

        if (rev.length > 1000) {
            $('#overErr').show();
        } else {
            $('#overErr').hide();
        }
    });





</script>