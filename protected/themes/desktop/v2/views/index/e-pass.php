<div class="row title-widget">
    <div class="col-12">
        <div class="container">
            <?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="row pt20">
        <div class="col-12 col-md-12">
            <div class="row" >
                <div class="col-12 col-md-7 mb20">
                    <div class="pt20 bg-white-box">
                        <?php if ($model['bkg_status'] != 5) {
                            echo "<h4 style='color:red; margin-left:20px'>Cab and Driver not assigned yet.</h4>";
                        } ?> 
                        
                        <div class="row">
                            <div class="col-11 col-md-8 ml20">
                                <h4 class="font-18"><b>Driver Details</b></h4>
                                <span><p>Driver name :  <?php echo $model['drv_name']; ?></p></span>					

                                <span><p>Driver license :  <?php echo $model['ctt_license_no']; ?></p></span>						

                                <span><p>Driver state :</b>  <?php echo $model['stt_name']; ?></p></span>

                                <h4 class="font-18 mt30"><b>Cab Details</b></h4>

                                <span><p>Cab Registration number :  <?php echo $model['vhc_number']; ?></p></span>	

                                <span><p>Vehicle model :  <?php echo $model['vht_model']; ?></p></span>						

                                <span><p>Vehicle year : <?php echo $model['vhc_year']; ?></p></span>						

                                <span><p>Vehicle owner name :  <?php echo $model['vhc_reg_owner']; ?></p></span>	
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-md-5 mb20">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'upload-form', 'enableClientValidation' => FALSE,
                    'clientOptions' => array(
                        'validateOnSubmit' => true
                    ),
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    'htmlOptions' => array(
                        'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'autocomplete' => "off",
                    ),
                ));
                /* @var $form CActiveForm */
                ?>

                
                    <div class="pt20 bg-white-box">
                        <div class="row">
                        <div class="col-12 mb10 color-green"><h4>UPLOAD E-PASS</h4></div>
                        <div class="col-12 col-md-10">
                        <?php
                        if ($success) {
                            echo $msg;
                            //echo $error;
                        }
                        ?>
                        </div>
                            <div class="col-12 col-md-10">

<!--							<input type="file" name="bcb_epass" value="" class="form-control" enctype="multipart/form-data">-->
<?= $form->fileField($modeltrail, 'btr_epass', array('class' => 'form-control mb10', 'placeholder' => 'E-PASS')); ?>
                                <span>(*)Note: Formate should be like (png,jpg,jpeg).</span>
                            </div>
                            <div class="col-12 top-buffer mt10">
                                <div class="Submit-button">
                                    <!--					<button type="button" class = "btn btn-primary btn-lg pl40 pr40 proceed-new-btn">Submit</button>-->

<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>

                                </div>
                            </div>
                        <div class="col-12 mt20">
                        <? if ($modeltrail->btr_epass != '') {
                            ?>
                        <img src="<?= Yii::app()->baseUrl ?><?= $modeltrail->btr_epass ?>" width="150" height="150" class="img-fluid">
<? } ?>
                    </div>
                        </div>
                    </div>
                </div>
<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

