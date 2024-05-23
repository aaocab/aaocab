<?
$fcity = Cities::getName($model->bkg_from_city_id);
$tcity = Cities::getName($model->bkg_to_city_id);
if ($model->bkg_promo_code != '') {
    $formdiv = 'hide';
    $chpromo = 'show';
    $divprmamt = 'show';
    $divoldamt = 'hide';
}
?>
<div class="thumbnail p10 border-radius">
    <div class="panel panel-default <?= $formdiv ?>" id='formdiv'>
        <div class="panel-heading">
            <h4 class="panel-title">Got discount code</h4>
        </div>
        <div class="panel-body">

            <?php
            $form1 = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id' => 'discount-form', 'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                    'errorCssClass' => 'has-error'
                ),
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // See class documentation of CActiveForm for details on this,
                // you need to use the performAjaxValidation()-method described there.
                //  'enableAjaxValidation' => false,
                'enableAjaxValidation' => true,
                'errorMessageCssClass' => 'help-block',
                //  'action' => Yii::app()->createUrl('index/confirm'),
                'htmlOptions' => array(
                    'class' => 'form-inline',
                ),
            ));
            /* @var $form TbActiveForm */
            ?>


            <div class="form-group">
                <label class="sr-only" for="exampleInputEmail2">Email address</label>
<!--                <input type="email" class="form-control border-radius" id="exampleInputEmail2" placeholder="Enter code">-->
                <?= $form1->textFieldGroup($model, 'bkg_promo_code', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['required' => TRUE, 'class' => 'form-control border-radius', 'placeholder' => 'Enter Code']))) ?>
            </div>
            <button type="submit" class="btn btn-primary border-radius" onclick="skipPopup()">Apply</button>
            <div class="ml10 hide" id="spanwrongcode" style="font-weight: bold;color: #FF0000">Invalid Promo Code</div>
            <?php $this->endWidget(); ?>
        </div>
    </div>



    <figure><img src="<?= Yii::app()->baseUrl . '/' . $vmodel->vct_image ?>" alt="Car Image, Gozocabs" class="border-black" style="width: 200px;"></figure>
    <div class="caption pl0 pr0">
        <div class="row m0 mb10">
            <div class="col-xs-6 p0"><b>Car:</b></div>
            <div class="col-xs-6 pr0 text-right"><?= $vmodel->vct_label . ' ' . $vmodel->vct_desc ?></div>
        </div>
        <div class="row m0 mb10">
            <div class="col-xs-6 p0"><b>Location:</b></div>
            <div class="col-xs-6 pr0 text-right"><?= $fcity ?></div>
        </div>
        <div class="row m0 mb10">
            <div class="col-xs-6 p0"><b>Destination:</b></div>
            <div class="col-xs-6 pr0 text-right"><?= $tcity ?></div>
        </div>
        <div class="row m0 mb10">
            <div class="col-xs-6 p0"><b>Pickup Date:</b></div>
            <div class="col-xs-6 pr0 text-right"><?= date('jS M Y (l)', strtotime($model->bkg_pickup_date)) ?></div>
        </div>
        <div class="row m0 mb10">
            <div class="col-xs-6 p0"><b>Pickup Time:</b></div>
            <div class="col-xs-6 pr0 text-right"><?= date('h:i A', strtotime($model->bkg_pickup_date)) ?></div>
        </div>
        <div class="row m0 mb10">
            <div class="col-xs-6 p0"><b>Journey Type:</b></div>
            <div class="col-xs-6 pr0 text-right">One Way</div>
        </div>
        <div id="divoldamt" class="<?= $divoldamt ?>">
            <div class="row m0 mb10">
                <div class="col-xs-6 p0"><b>Amount:</b></div>
                <div class="col-xs-6 pr0 text-right"><i class=" fa fa-inr"></i>  <?= $model->bkg_amount ?></div>
            </div></div>
        <div id="divprmamt" class='<?= $divprmamt ?>'>

            <div class="row m0 mb10">
                <div class="col-xs-6 p0"><b>Total Fare:</b></div>
                <div class="col-xs-6 pr0 text-right"> <b><i class=" fa fa-inr"></i></b> <strike><span id="oldamt"><?= $model->bkg_net_charge ?></span></strike></div>
            </div>
            <div class="row m0 mb10">
                <div class="col-xs-6 p0"><b>Discount:</b></div>
                <div class="col-xs-6 pr0 text-right"><b><i class=" fa fa-inr"></i> <span id="discount"><?= $model->bkg_discount ?></span></b></div>
            </div>
            <div class="row m0 mb10">
                <div class="col-xs-6 p0"><b>Amount Payable:</b></div>
                <div class="col-xs-6 pr0 text-right"><b><i class=" fa fa-inr"></i> <span id="bkgamt"><?= $model->bkg_amount ?></span></b></div>
            </div>
        </div>
        <div class="row m0 mb10">
            <hr class="m0 mb5">
            Extra charges applicable incase of drop/pickup in areas beyond South Delhi. 
        </div>
    </div>
    <? $hide = ($model->bkg_promo_code != '') ? '' : 'hide'; ?>
    <div id="prmappld" class='<?= $hide ?>'>
        <div class="row m0 mb10">
            <hr class="m0 mb5">
            Applied discount code : <span id="txtpromo"><?= $model->bkg_promo_code ?> </span>
            <button class="btn btn-primary" id='btnapplied'>Remove Code</button>

        </div>      

    </div>
</div>
<script>

    $(document).ready(function () {
        $("#divprmamt").addClass('hide');
        // $("#prmappld").addClass('hide');
    });
    $("#btnapplied").click(function (e) {
        var bkg_id = $('#Booking_bkg_id').val();
        $(function (e)
        {
            $.ajax({
                "type": "POST",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/promoremove')) ?>",
                "data": {"bkgid": bkg_id},
                success: function (data)
                {
                    $("#divprmamt").addClass('hide');
                    $("#divoldamt").removeClass('hide');
                    $("#formdiv").removeClass('hide');
                    $("#prmappld").addClass('hide');
                }
            });
        });
        event.preventDefault();
    });
//            });
//            $("#formdiv").removeClass('hide');
//            $("#prmappld").addClass('hide');
//            $("#prmappld").removeClass('show');
//            e.preventDefault();
//        });
    $("#discount-form").submit(function (event) {
        var $bkg_id = $('#Booking_bkg_id').val();
        var $bkgpcode = $('#Booking_bkg_promo_code').val();
        $href = '<?= Yii::app()->createUrl('index/promoapply') ?>';
        $(function (e)
        {
            $.ajax({
                "type": "POST",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/promoapply')) ?>",
                "data": {"bkg_id": $bkg_id, 'bkg_pcode': $bkgpcode},
                success: function (data)
                {
                    if (data.discount > 0) {

                        $("#divprmamt").removeClass('hide');
                        $("#divoldamt").addClass('hide');
                        $("#formdiv").addClass('hide');
                        $("#prmappld").removeClass('hide');
                        $("#oldamt").text(data.oldtotal);
                        $("#discount").text(data.discount);
                        $("#bkgamt").text(data.newtotal);
                        $("#txtpromo").text(data.promocode);
                    } else {
                        $("#spanwrongcode").removeClass('hide');
                    }//                        
                }
            });
        });
        event.preventDefault();
    });



</script>