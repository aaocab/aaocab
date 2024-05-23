<style>
    .rating-cancel{
        display: none !important;
        visibility: hidden !important;
    }
    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;

    }

    .review
    {
        margin-top: 20px;color: #f00;font-size: 13px;display: none;text-align: center;
    }
</style>
<section id="section1">
    <div class="container">
        <div class="row">
            <h3 class="text-uppercase text-center m0 mb10 weight400">Review</h3>
            <div class="col-xs-11 col-sm-10 col-md-8 col-lg-6 float-none marginauto  p5">

                <div class="panel" >

                    <div class="panel-body p0">
                        <div class="panel-scroll1">


							<?php
							if ($ifReviewExist)
							{
								?>                                   
								<div class="row">
									<div class="col-xs-12" style="color:#666666">
										<div class="p10 pl15"><label>We have already received the review for this booking id.</label>
										</div>
										<?
										if ($model->rtg_customer_recommend)
										{
											?> <div class='col-xs-12 mt10'>
												<?= $model->getAttributeLabel('rtg_customer_recommend') ?><br>
												<?
												$this->widget('CStarRating', array(
													'model'		 => $model,
													'attribute'	 => 'rtg_customer_recommend',
													'minRating'	 => 1,
													'maxRating'	 => 10,
													'starCount'	 => 10,
													'value'		 => $model->rtg_customer_recommend,
													'readOnly'	 => true,
												));
												?>
											</div><?
										}
										if ($model->rtg_customer_overall)
										{
											?> <div class='col-xs-6 mt20'>

												<?= $model->getAttributeLabel('rtg_customer_overall') ?><br>
												<?
												$this->widget('CStarRating', array(
													'model'		 => $model,
													'attribute'	 => 'rtg_customer_overall',
													'minRating'	 => 1,
													'maxRating'	 => 5,
													'starCount'	 => 5,
													'value'		 => $model->rtg_customer_overall,
													'readOnly'	 => true,
												));
												?></div><?
										}
										if ($model->rtg_customer_driver)
										{
											?> <div class='col-xs-6 mt20'>
												<?= $model->getAttributeLabel('rtg_customer_driver') ?><br>
												<?
												$this->widget('CStarRating', array(
													'model'		 => $model,
													'attribute'	 => 'rtg_customer_driver',
													'minRating'	 => 1,
													'maxRating'	 => 5,
													'starCount'	 => 5,
													'value'		 => $model->rtg_customer_driver,
													'readOnly'	 => true,
												));
												?></div><?
										}
										if ($model->rtg_customer_csr)
										{
											?> <div class='col-xs-6 mt20'>
												<?= $model->getAttributeLabel('rtg_customer_csr') ?><br>
												<?
												$this->widget('CStarRating', array(
													'model'		 => $model,
													'attribute'	 => 'rtg_customer_csr',
													'minRating'	 => 1,
													'maxRating'	 => 5,
													'starCount'	 => 5,
													'value'		 => $model->rtg_customer_csr,
													'readOnly'	 => true,
												));
												?></div><?
										}
										if ($model->rtg_customer_car)
										{
											?> <div class='col-xs-6 mt20'>
												<?= $model->getAttributeLabel('rtg_customer_car') ?><br>
												<?
												$this->widget('CStarRating', array(
													'model'		 => $model,
													'attribute'	 => 'rtg_customer_car',
													'minRating'	 => 1,
													'maxRating'	 => 5,
													'starCount'	 => 5,
													'value'		 => $model->rtg_customer_car,
													'readOnly'	 => true,
												));
												?></div>
											<?
										}
										if ($model->rtg_customer_review)
										{
											?> <div class='col-xs-12 mt20 pb5'>
												<?= $model->getAttributeLabel('rtg_customer_review') ?> </div>
											<div class="col-xs-12 mt0 mb20 ">
												<div class="p15 rounded">
													<?= $model->rtg_customer_review;
													?>

												</div>
											</div>
											<?
										}
										?> 
									</div>
								</div>
								<?
							}
							else
							{



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

								<div class="col-xs-12" style="min-height:100px;color:#666666">
									<div class="form-group pt20 text-center">
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
									<div class="form-group pt30">
										<div class="col-xs-7 text-right"><?= $model->getAttributeLabel('rtg_customer_overall') ?></div>
										<div class="col-xs-5">
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
											<div class="col-xs-7 text-right"><?= $model->getAttributeLabel('rtg_customer_driver') ?></div>
											<div class="col-xs-5">
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
											<div class="col-xs-7 text-right"><?= $model->getAttributeLabel('rtg_customer_csr') ?></div>
											<div class="col-xs-5">
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
											<div class="col-xs-7 text-right"><?= $model->getAttributeLabel('rtg_customer_car') ?></div>
											<div class="col-xs-5">
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

										<div class="col-xs-12">
											<?= $form->textAreaGroup($model, 'rtg_customer_review', array('widgetOptions' => array('htmlOptions' => array()))) ?>
										</div>
										<div id="revErr" class="review">Please give your feedback.</div>  
									</div> 

									<div class="col-xs-12 p15 text-center ">
										<button class="btn btn-primary" type="submit" value="Rate" tabindex="2" >Rate</button>
									</div>
								</div>
								<?php
								$this->endWidget();
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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

            $(function (event)
            {
                $.ajax({
                    type: 'POST',
                    "dataType": "json",
                    "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('rating/ajaxverify')) ?>",
                    "data": $('#rating-form').serialize(),
                    success: function (data)
                    {
                        if (data.result == 'true') {
                            var returnUrl = <?= CJavaScript::encode(Yii::app()->createUrl('booking/list')) ?>;
                            if (window.opener) {
                                if (returnUrl) {
                                    window.opener.location.href = returnUrl;
                                } else {
                                    window.opener.location.reload();
                                }
                                window.close();
                            } else {
                                window.location.href = returnUrl ? returnUrl : '/';
                            }
                        }

                    }
                });
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


</script>