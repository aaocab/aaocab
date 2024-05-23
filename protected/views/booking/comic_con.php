
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
<?php
$arrRoute = array();
$arrRoute[] = array('img'=>'mumbai-pune.jpg', 'cName'=>'Pune to Mumbai', 'fcity'=>30611, 'tcity'=>30595);
$arrRoute[] = array('img'=>'goa-mumbai.jpg', 'cName'=>'Goa to Mumbai', 'fcity'=>31022, 'tcity'=>30595);
$arrRoute[] = array('img'=>'nashik-pune.jpg', 'cName'=>'Nashik to Mumbai', 'fcity'=>30582, 'tcity'=>30595);
$arrRoute[] = array('img'=>'indore-mumbai.jpg', 'cName'=>'Indore to Mumbai', 'fcity'=>30556, 'tcity'=>30595);
$arrRoute[] = array('img'=>'surat-mumbai.jpg', 'cName'=>'Surat to Mumbai', 'fcity'=>30389, 'tcity'=>30595);
$arrRoute[] = array('img'=>'comic_con_mumbai.jpg', 'cName'=>'Your city to Mumbai', 'fcity'=>0, 'tcity'=>30595);
$arrRoute[] = array('img'=>'mumbai-pune.jpg', 'cName'=>'Mumbai to Pune', 'fcity'=>30595, 'tcity'=>30611);
$arrRoute[] = array('img'=>'goa-pune.jpg', 'cName'=>'Mumbai to Goa', 'fcity'=>30595, 'tcity'=>31022);
$arrRoute[] = array('img'=>'nashik-pune.jpg', 'cName'=>'Mumbai to Nashik', 'fcity'=>30595, 'tcity'=>30582);
$arrRoute[] = array('img'=>'indore-mumbai.jpg', 'cName'=>'Mumbai to Indore', 'fcity'=>30595, 'tcity'=>30556);
$arrRoute[] = array('img'=>'surat-mumbai.jpg', 'cName'=>'Mumbai to Surat', 'fcity'=>30595, 'tcity'=>30389);
$arrRoute[] = array('img'=>'comic_con_mumbai.jpg', 'cName'=>' Mumbai to Your city', 'fcity'=>30595, 'tcity'=>0);

?>

<div class="row register_path p20" id="rutsDiv">
    <!--<div class=" col-xs-12 text-center"><h3> Call <a href="tel:+913366283911" style="text-decoration: none">+91-33-66283911</a></h3></div>-->
	<div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel panel-gall-box p10">            
           <p>
			Get your Cosplay on at Mumbai Comic Con! Mumbai’s biggest pop culture event is back with its 8th edition and Gozo cabs is ready to take you there!
			Go see features by the best of comics, movies, television, gaming, and cosplay.<br>

			Dates: December 22nd->23rd, 2018<br>

			Book a private cab for you and your companions or take a GozoSHARE ride for a lesser cost and make friends en route the event.
		   </p>            
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
                                    id="219" fcity="<?=$value['fcity']?>" tcity ="<?=$value['tcity']?>"
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
        if (fct == '30595') {
            $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val("23/12/2018").change();
        }
        if (tct == '30595') {
            $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val("21/12/2018").change();
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

