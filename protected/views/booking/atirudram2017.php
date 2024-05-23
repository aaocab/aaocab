<style type="text/css">
    .panel-gall-box{ 
        -webkit-box-shadow: 1px 1px 3px 0px rgba(201,201,201,1);
        -moz-box-shadow: 1px 1px 3px 0px rgba(201,201,201,1);
        box-shadow: 1px 1px 3px 0px rgba(201,201,201,1);
    }
    .form-group{margin-left: 0!important;margin-right: 0!important;}
    .gall-box{ position: relative;  height: 190px; overflow: hidden;}
    .gall-box-one{ position: relative;}
    .gall-box-one img{ width: 100%; overflow: hidden;}
    .gall-box-two{ position:absolute; top: 60px; left: 0; color: #fff; text-align: center!important; width: 100%;}
    .gall-box-two h3{ font-size: 28px; line-height: normal; text-align: center!important; text-shadow: 2px 2px 2px rgba(0, 0, 0, 1); text-transform: uppercase;}
    .gall-text{ position: relative;}
    .bakbtn{ font-size: 15px;color: #0081c2}
    .gall-image{ background: #f7f7f7; min-height: 280px; padding-top: 15px;}
    .gall-image img{ width: 90%; margin: 15px;}
</style>

<?
$ccode = Countries::model()->getCodeList();
?>

<?
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'bookingSform',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'errorCssClass' => 'has-error',
        'afterValidate' => 'js:function(form,data,hasError){
                            if(!hasError){
                            return true;
                                
                            }
                        }'
    ),
    'enableAjaxValidation' => false,
    'errorMessageCssClass' => 'help-block',
    'action' => Yii::app()->createUrl('booking/new'),
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    ),
        ));
/* @var $form TbActiveForm */
?>
<?= $form->errorSummary($model); ?>
<?= CHtml::errorSummary($model); ?>
<input type="hidden" id="step" name="step" value="0">
<?= $form->hiddenField($model, "bkg_from_city_id"); ?>
<?= $form->hiddenField($model, "bkg_to_city_id"); ?>
<?= $form->hiddenField($model, "bkg_booking_type", ['value' => 1]); ?>
<?= $form->hiddenField($model, "bkg_pickup_date_date") ?>
<?= $form->hiddenField($model, "bkg_pickup_date_time", ['value' => date('h:i A', strtotime('6 AM'))]); ?>

<div class="row">
    <div class="col-xs-12 p0">
        <div class="inner_banner">
            <figure><img src="/images/atirudram2017.jpg" alt="Atirudram 2017"></figure>
        </div>
    </div>
</div>

<div class="row pt10 pb10 register_path">
    <div class="col-xs-12 mt20 mb20 marginauto ">
<!--        <p class="text-center">Arunachala Siva!<br>Om Gam Ganapathaye Namaha! Om Namo Bhagavate Rudraya! Om Namo Narayanaya!<br>Sri Sayeeswaraya NamaH, HariH OM!</p>
        <h1 class="text-center"><u>Ati Rudra Maha Yajna, Tiruvannamalai</u></h1>
                <h3 class="text-center m0">July 26 to Aug 6, 2017</h3>-->
        <h2 class="text-uppercase" style="text-align: center"><u>One-way Drops</u></h2> 
    </div>
</div>

<div class="row register_path p20" id="rutsDiv">
    <!--    <div class=" col-xs-12 text-center"><h3> Call <a href="tel:+913366283911" style="text-decoration: none">+91-33-66283911</a></h3></div>-->
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-gall-box p10">

            <div class="gall-box">
                <div class="gall-box-one"><figure><img src="/images/atirudram_1.png" alt="Bangalore to Tiruvannamalai"></figure></div>
                <div class="gall-box-two"><h3>Bangalore to Tiruvannamalai</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn" 
                                    id="219" fcity="30474" tcity ="30760"
                                    >Book Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><figure><img src="/images/atirudram_2.png" alt="Chennai to Tiruvannamalai"></figure></div>
                <div class="gall-box-two"><h3>Chennai to Tiruvannamalai</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn"
                                    id="220" fcity="30758" tcity ="30760"
                                    >Book Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><figure><img src="/images/atirudram_3.png" alt="Puttaparthi to Tiruvannamalai"></figure></div>
                <div class="gall-box-two"><h3>Puttaparthi to Tiruvannamalai</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn"
                                    id="221" fcity="32062" tcity ="30760"
                                    >Book Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><figure><img src="/images/atirudram_4.png" alt="Tiruvannamalai to Chennai"></figure></div>
                <div class="gall-box-two"><h3>Tiruvannamalai to Chennai</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn"
                                    id="6374" fcity="30760" tcity ="30758"
                                    >Book Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><figure><img src="/images/atirudram_5.png" alt="Tiruvannamalai to Bangalore"></figure></div>
                <div class="gall-box-two"><h3>Tiruvannamalai to Bangalore</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn"
                                    id="217" fcity="30760" tcity ="30474"
                                    >Book Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><figure><img src="/images/atirudram_6.png" alt="Other (could be one-way, roundtrip, any city etc)"></figure></div>
                <div class="gall-box-two"><h3>Other (could be one-way, roundtrip, any city etc)</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <a href="/" class="btn btn-primary col-xs-12 btn-lg bkbtn" 
                               >Book Now</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row text-center">
    <h3>NOTES: <a href="http://atirudram.us/" target="_blank">www.atirudram.us</a> has much more details about the event </h3>
</div>
<?php $this->endWidget(); ?>

<script>

    $('.bkbtn').click(function (e) {
//        rtid = this.id;
        fct = this.getAttribute("fcity");
        tct = this.getAttribute("tcity");

        $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').val(fct);
        $('#<?= CHtml::activeId($model, "bkg_to_city_id") ?>').val(tct);
        if (fct == '30760') {
            $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val("25/07/2017").change();
        }
        if (tct == '30760') {
            $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val("06/08/2017").change();
        }
    });

    $('#btnBack').click(function (e1) {
        $('#rutsDiv').fadeIn();
        $('#cabDiv').fadeOut();
        $('#rutName').text('');

    });
    function validateForm1(obj) {
        var vht = $(obj).attr("value");
        if (vht > 0) {
            $('#Booking_bkg_vehicle_type_id').val(vht);
        }
    }



</script>
