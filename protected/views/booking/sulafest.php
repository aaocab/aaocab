
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
	.banner-gallery img{ width: 100%;}
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
<?php $arrRoute = array();
$arrRoute[] = array('img'=>'Nashik-Mumbai.png', 'cName'=>'Mumbai to Nashik', 'fcity'=>30595, 'tcity'=>30582);
$arrRoute[] = array('img'=>'Nashik-Pune.png', 'cName'=>'Pune to Nashik ', 'fcity'=>30611, 'tcity'=>30582);
$arrRoute[] = array('img'=>'Nashik-Goa.png', 'cName'=>'Goa to Nashik', 'fcity'=>31022, 'tcity'=>30582);
$arrRoute[] = array('img'=>'Nashik-Ahmedabad.png', 'cName'=>'Ahmedabad to Nashik', 'fcity'=>30372, 'tcity'=>30582);
$arrRoute[] = array('img'=>'Nashik-any.png', 'cName'=>'Any to Nashik', 'fcity'=>0, 'tcity'=>30582);
$arrRoute[] = array('img'=>'Nashik-Mumbai.png', 'cName'=>'Nashik to Mumbai', 'fcity'=>30582, 'tcity'=>30595);
$arrRoute[] = array('img'=>'Nashik-Pune.png', 'cName'=>'Nashik to Pune', 'fcity'=>30582, 'tcity'=>30611);
$arrRoute[] = array('img'=>'Nashik-Goa.png', 'cName'=>'Nashik to Goa', 'fcity'=>30582, 'tcity'=>31022);
$arrRoute[] = array('img'=>'Nashik-Ahmedabad.png', 'cName'=>'Nashik to Ahmedabad', 'fcity'=>30582, 'tcity'=>30372);
$arrRoute[] = array('img'=>'Nashik-any.png', 'cName'=>'Nashik to Any', 'fcity'=>30582, 'tcity'=>0);
?>


<div class="row" id="rutsDiv">
<!--    <div class=" col-xs-12 text-center"><h3> Call <a href="tel:+913366283911" style="text-decoration: none">+91-33-66283911</a></h3></div>-->
<div class="col-xs-12 text-center"><div class="banner-gallery"><img src="/images/sulafest_2019.jpg" alt="Sula Vineyards, Nashik"></div></div>	
	<div class="col-xs-12 col-sm-12 col-md-12">
        <div class="p10 mt10" style="font-size:16px; line-height: 28px;">            
           <p>
			Head to India’s coolest Wine and Music Fest! The 12th edition of Sula Fest is here and Gozo Cabs proud to be the official travel partner.<br>
			Bringing you the lowest prices and the best service - we’re open for bookings for Private and Shared cabs. </br>
			Pack your bags and get ready for a weekend of merriment with wine, music, dance, camping and shopping at <b style="color:#000;">Sula Vineyards, Nashik.</b><br>
			Dates: <b class="orange-color">February 2-3, 2019</b><br>
            Book a private cab for you and your companions or take a GozoSHARE ride for a lesser cost and make friends en route the event. </p>            
		   <b>Use code <b class="orange-color">GOSULA</b> to Get Rs.300/- off on your booking of a Personal cab</b>
		</div>
    </div>
	<?	
	foreach($arrRoute as $key=>$value)
	{
	 
	?>
	<div class="col-xs-12 col-sm-6 col-md-4">
        <div class="panel panel-gall-box p10">
            <div class="gall-box">
                <div class="gall-box-one"><img src="/images/events/<?=$value['img']?>" alt=""></div>
                <div class="gall-box-two"><h3><?=$value['cName']?></h3></div>
            </div>
            <div class="gall-text">
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 mt15 mb10">
                            <button type="submit" class="btn btn-primary col-xs-12 btn-lg bkbtn"
                                    id="222" fcity="<?=$value['fcity']?>" tcity ="<?=$value['tcity']?>"
                                    >Book Now</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
	<?}?>
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
        if (fct == '30582') {
            $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val("04/02/2019").change();
        }
        if (tct == '30582') {
            $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val("02/02/2019").change();
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




