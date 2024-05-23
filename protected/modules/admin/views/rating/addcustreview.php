
<style>



    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .modal-backdrop{
        height: 650px !important;
    }   

    .review
    {
        margin-top: 20px;color: #f00;font-size: 13px;display: none;text-align: center;
    }
    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;

    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12">
            <div class="panel" >               
                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll1">
                        <div style="width: 100%; padding: 3px; overflow: auto; line-height: 10px; font: normal arial; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
                            <div class="form" >
								<?php
								$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
                                    <div class="form-group pt20 mb0 text-center">
                                        <div class="row">
                                            <div class="col-xs-12 text-center"><?= $model->getAttributeLabel('rtg_customer_recommend') ?></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6 col-xs-offset-4">
												<?
												$this->widget('CStarRating', array(
													'model'		 => $model,
													'attribute'	 => 'rtg_customer_recommend',
													'callback'	 => 'function(){checkRecRating($(this))}',
													'minRating'	 => 1,
													'maxRating'	 => 10,
													'starCount'	 => 10,
												));
												?> 
                                            </div> 
                                        </div>
                                    </div>
                                    <div id="recommendErr" class="review">Please rate how would you like to recommend Gozo to your friends and family.</div>

                                    <div class="form-group pt20">
                                        <div class="col-xs-6 text-right"><?= $model->getAttributeLabel('rtg_customer_overall') ?></div>
                                        <div class="col-xs-6">
											<?
											$this->widget('CStarRating', array(
												'model'		 => $model,
												'attribute'	 => 'rtg_customer_overall',
												'callback'	 => 'function(){checkrating($(this))}',
												'minRating'	 => 1,
												'maxRating'	 => 5,
												'starCount'	 => 5,
											));
											?> 
                                        </div>
                                    </div>
                                    <div id="allErr" class="review">Please rate our Overall Service.</div>  
                                    <div id="otherrating">
                                        <div class="form-group pt20">
                                            <div class="col-xs-6 text-right"><?= $model->getAttributeLabel('rtg_customer_driver') ?></div>
                                            <div class="col-xs-6">
												<?
												$this->widget('CStarRating', array(
													'model'		 => $model,
													'attribute'	 => 'rtg_customer_driver',
													'callback'	 => 'function(){checkdvrrating($(this))}',
													'minRating'	 => 1,
													'maxRating'	 => 5,
													'starCount'	 => 5,
												));
												?> 
                                            </div>

                                        </div>
                                        <div id="dvrErr" class="review">Please rate our Driver.</div>  

                                        <div class="form-group pt20">
                                            <div class="col-xs-6 text-right"><?= $model->getAttributeLabel('rtg_customer_csr') ?></div>
                                            <div class="col-xs-6">
												<?
												$this->widget('CStarRating', array(
													'model'		 => $model,
													'attribute'	 => 'rtg_customer_csr',
													'callback'	 => 'function(){checkcsrrating($(this))}',
													'minRating'	 => 1,
													'maxRating'	 => 5,
													'starCount'	 => 5,
												));
												?> 
                                            </div>

                                        </div>
                                        <div id="csrErr" class="review">Please rate our Customer Support.</div>  


                                        <div class="form-group pt20">
                                            <div class="col-xs-6 text-right"><?= $model->getAttributeLabel('rtg_customer_car') ?></div>
                                            <div class="col-xs-6">
												<?
												$this->widget('CStarRating', array(
													'model'		 => $model,
													'attribute'	 => 'rtg_customer_car',
													'callback'	 => 'function(){checkcarrating($(this))}',
													'minRating'	 => 1,
													'maxRating'	 => 5,
													'starCount'	 => 5,
												));
												?> 
                                            </div>

                                        </div>
                                        <div id="carErr" class="review">Please rate our Car Quality.</div>  

                                    </div>

                                    <div class="form-group pt20">
                                        <div class="col-xs-12 mb5"><?= $model->getAttributeLabel('rtg_customer_review') ?></div>
                                        <div class="col-xs-12">
											<?=
											$form->textAreaGroup($model, 'rtg_customer_review', array('label' => '', 'widgetOptions' => array('htmlOptions' => array())))
											?>
                                            <div>You have <span id="charleftcount">6000 characters left.</span> (Maximum characters: 6000)</div>
                                        </div>
                                        <div id="revErr" class="review">Please give your feedback.</div>
                                        <div id="overErr" class="review mt5">Max 6000 characters.</div>
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
        $('#otherrating').hide();
    });
    $('#rating-form').submit(function (event) {
        var $error = 0;
        if ($('#Ratings_rtg_customer_review').val() == '') {
            //       $error += 1;
        } else {
            $error += 0;
        }
        if (!$('#Ratings_rtg_customer_recommend_0').hasClass('star-rating-on')) {
            $('#recommendErr').show();
            $error += 1;
        } else {
            $('#recommendErr').hide();
            $error += 0;
        }
        if (!$('#Ratings_rtg_customer_overall_0').hasClass('star-rating-on')) {
            $('#allErr').show();
            $error += 1;
        } else {
            $('#allErr').hide();
            $error += 0;
        }
        if (!$('#Ratings_rtg_customer_overall_3').hasClass('star-rating-on')) {
            if ($('#Ratings_rtg_customer_driver_0').hasClass('star-rating-on')) {
                $('#dvrErr').hide();
                $error += 0;
            } else {
                $('#dvrErr').show();
                $error += 1;
            }
            if ($('#Ratings_rtg_customer_csr_0').hasClass('star-rating-on')) {
                $('#csrErr').hide();
                $error += 0;
            } else {
                $('#csrErr').show();
                $error += 1;
            }
            if ($('#Ratings_rtg_customer_car_0').hasClass('star-rating-on')) {
                $('#carErr').hide();
                $error += 0;
            } else {
                $('#carErr').show();
                $error += 1;
            }
        } else {
            $('#dvrErr').hide();
            $('#carErr').hide();
            $('#csrErr').hide();
            $error += 0;
        }


        if ($error == 0) {


            $.ajax({
                "type": 'POST',
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/rating/ajaxcustverify')) ?>",
                data: $('#rating-form').serialize(),
                success: function (data)
                {
                    if (data.result == 'true') {
                        $(".bootbox").remove();
                        $(this).trigger(event);
                    }

                    //  window.close();
//                        if (window.opener) {
//                            if (returnUrl) {
//                                window.opener.location.href = returnUrl;
//                            } else {
//                                window.opener.location.reload();
//                            }
//                            window.close();
//                        } else {
//                            window.location.href = returnUrl ? returnUrl : '/';
//                        }
                }


            });
        }
        event.preventDefault();
    });
    function checkrating(obj) {
        $rate = obj.val();
        if ($rate < 4) {
            $('#otherrating').show();
        } else {
            $('#otherrating').hide();
            $('#dvrErr').hide();
            $('#carErr').hide();
            $('#csrErr').hide();
        }
        if ($rate == '') {
            $('#allErr').show();
        } else {
            $('#allErr').hide();
        }
    }
    function checkcarrating(obj) {
        $rate = obj.val();
        if ($rate == '') {
            $('#carErr').show();
        } else {
            $('#carErr').hide();
        }
    }
    function checkcsrrating(obj) {
        $rate = obj.val();
        if ($rate == '') {
            $('#csrErr').show();
        } else {
            $('#csrErr').hide();
        }
    }


    function checkdvrrating(obj) {
        $rate = obj.val();
        if ($rate == '') {
            $('#dvrErr').show();
        } else {
            $('#dvrErr').hide();
        }
    }
    $('#Ratings_rtg_customer_review').keyup(function () {
        rev = $('#Ratings_rtg_customer_review').val();
        revlength = rev.length;
        if (revlength > 6000) {
            $('#overErr').show();
            $('#charleftcount').text('entered ' + (revlength - 6000) + ' characters  extra.');
        } else {
            $('#overErr').hide();
            $('#charleftcount').text((6000 - revlength) + ' characters  left.');
        }
    });

    $('#Ratings_rtg_customer_review').change(function () {
        rev = $('#Ratings_rtg_customer_review').val();

        if (rev.length > 6000) {
            $('#overErr').show();
        } else {
            $('#overErr').hide();
        }
    });


    function checkRecRating(obj)
    {
    }
</script>