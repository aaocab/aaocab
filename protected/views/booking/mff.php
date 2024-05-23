
<style type="text/css">
    .panel-gall-box{ 
        -webkit-box-shadow: 1px 1px 3px 0px rgba(201,201,201,1);
        -moz-box-shadow: 1px 1px 3px 0px rgba(201,201,201,1);
        box-shadow: 1px 1px 3px 0px rgba(201,201,201,1);
    }
    .form-group{margin-left: 0!important;margin-right: 0!important;}
    .gall-box{ position: relative;  height: 148px; overflow: hidden;}
    .gall-box-one{ position: relative;}
    .gall-box-one img{ width: 100%; overflow: hidden;}
    .gall-box-two{ position:absolute; top: 60px; left: 0; color: #fff; text-align: center!important; width: 100%;}
    .gall-box-two h3{ font-size: 22px; line-height: normal; text-align: center!important; text-shadow: 2px 2px 2px rgba(0, 0, 0, 1); text-transform: uppercase;}
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


<div class="row register_path p20" id="rutsDiv">
    <!--<div class=" col-xs-12 text-center"><h3> Call <a href="tel:+913366283911" style="text-decoration: none">+91-33-66283911</a></h3></div>-->
	<div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel panel-gall-box p10">            
           <p>
			
			   Travel to Magnetic Fields, Alsisar Mahal, Rajasthan with Gozo Cabs. Book a cab for your private use or Book a seat in a shared cab (we will specify a common pickup location for all travelers).
                 <br/> 6 hours from Delhi / 4 hours from Jaipur.
					<br/>Suggested route from Delhi: Gurgaon > Rewari > Narnol > Singhana > Chirawa > Jhunjhunu > Alsisar.
					<br/>Suggested route from Jaipur: Jaipur > Chomu > Sikar (take a bypass) > Nawalgarh > Jhunjhunu > Alsisar
					<br/>Alsisar Mahal Address: Alsisar Jhunjhunu, Alsisar, Rajasthan 331025, India
			   
			  
		   </p>            
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-gall-box p10">
            
            <div class="gall-box">
                <div class="gall-box-one"><img src="/images/events/alsisar_img1.jpg" alt=""></div>
                <div class="gall-box-two"><h3>Delhi to Alsisar</h3></div>

            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn" 
                                    id="219" fcity="30366" tcity ="30946"
                                    >Book Now</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><img src="/images/events/alsisar_img2.jpg" alt=""></div>
                <div class="gall-box-two"><h3>Jaipur to Alsisar</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn"
                                    id="220" fcity="30741" tcity ="30946"
                                    >Book Now</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
	 <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><img src="/images/events/alsisar_img4.jpg" alt=""></div>
                <div class="gall-box-two"><h3>Sadulpur to Alsisar</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn"
                                    id="221" fcity="30953" tcity ="30946"
                                    >Book Now</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><img src="/images/events/alsisar_img3.jpg" alt=""></div>
                <div class="gall-box-two"><h3>Any City to Alsisar</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn"
                                    id="222" fcity="" tcity ="30946"
                                    >Book Now</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><img src="/images/events/alsisar_img4.jpg" alt=""></div>
                <div class="gall-box-two"><h3>Alsisar to Delhi</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn"
                                    id="216" fcity="30946" tcity ="30366"
                                    >Book Now</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><img src="/images/events/alsisar_img5.jpg" alt=""></div>
                <div class="gall-box-two"><h3>Alsisar to Jaipur</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn"
                                    id="217" fcity="30946" tcity ="30741"
                                    >Book Now</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><img src="/images/events/alsisar_img6.jpg" alt=""></div>
                <div class="gall-box-two"><h3>Alsisar to Sadulpur</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn" 
                                    id="218" fcity="30946" tcity ="30953"
                                    >Book Now</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
	  <div class="col-xs-12 col-sm-6 col-md-3">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><img src="/images/events/alsisar_img6.jpg" alt=""></div>
                <div class="gall-box-two"><h3>Alsisar to Any City</h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn" 
                                    id="218" fcity="30946" tcity =""
                                    >Book Now</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12">
              
           <p>
		     * Please note - we offer doorstep pickup for private vehicles and a common pickup location for all GozoSHARE passengers.
		   </p>            
        
    </div>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">

    $('.bkbtn').click(function (e) {
        rtid = this.id;
        fct = this.getAttribute("fcity");
        tct = this.getAttribute("tcity");		
		
	
		

        $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').val(fct);
        $('#<?= CHtml::activeId($model, "bkg_to_city_id") ?>').val(tct);
        if (fct == '30946') {
            $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val("11/12/2018").change();
        }
        if (tct == '30946') {
            $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val("09/12/2018").change();
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

