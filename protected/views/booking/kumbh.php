
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
	table, th, td {
					 border: 1px solid black;
				  }
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
$arrRoute[] = array('img'=>'lucknow-prayagraj.jpg', 'cName'=>'Lucknow to Prayagraj', 'fcity'=>30874, 'tcity'=>30819);
$arrRoute[] = array('img'=>'kanpur-prayagraj.jpg', 'cName'=>'Kanpur to Prayagraj', 'fcity'=>30866, 'tcity'=>30819);
$arrRoute[] = array('img'=>'varanasi-prayagraj.jpg', 'cName'=>'Varanasi to Prayagraj', 'fcity'=>30826, 'tcity'=>30819);
$arrRoute[] = array('img'=>'any-prayagraj.jpg', 'cName'=>'Your City to Prayagraj', 'fcity'=>0, 'tcity'=>30819);
$arrRoute[] = array('img'=>'prayagraj-lucknow.jpg', 'cName'=>'Prayagraj to Lucknow', 'fcity'=>30819, 'tcity'=>30874);
$arrRoute[] = array('img'=>'prayagraj-kanpur.jpg', 'cName'=>'Prayagraj to Kanpur', 'fcity'=>30819, 'tcity'=>30866);
$arrRoute[] = array('img'=>'prayagraj-varanasi.jpg', 'cName'=>'Prayagraj to Varanasi', 'fcity'=>30819, 'tcity'=>30826);
$arrRoute[] = array('img'=>'any-prayagraj.jpg', 'cName'=>'Prayagraj to Your City', 'fcity'=>30819, 'tcity'=>0);
?>
<div class="row" id="rutsDiv">
    <!--<div class=" col-xs-12 text-center"><h3> Call <a href="tel:+913366283911" style="text-decoration: none">+91-33-66283911</a></h3></div>-->
	<div class="col-xs-12 col-sm-12 col-md-12">
        <div class="panel panel-gall-box p10">            
           <p>
			   Gozo Cabs is ready to take you to India's largest religious gathering for KUMBH at Prayagraj (Allahabad). Travel hassle-free with Gozo Cabs to take a dip in the sacred confluence of Ganges, Yamuna, and Saraswati.<br> 
			   Experience tourist-walk starting from Shankar Viman Mandapam towards Ramghat; covering Allahabad Fort, Akshay Vat, Patalpuri temple en route on your Kumbh Mela visit.<br>
               Dates: January 14th, 2019 to March 4th, 2019
		   </p>            
        </div>
    </div>
	<div class="col-xs-12 col-sm-9 col-md-8 marginauto float-none">
		<table style="width:100%" class="table table-bordered">
			<caption class="h3 m0 mb10">Auspicious days during Kumbh</caption>
				<tr>
				  <th>Festival</th> 
				  <th>Date(Day)</th>
				</tr>
				<tr>
				  <td>Makar Sankranti</td> 
				  <td>15-01-2019(Tuesday)</td>
				</tr>
				<tr>
				  <td>Paush Purnima</td> 
				  <td>21-01-2019(Monday)</td>
				</tr>
				<tr>
				  <td>Mauni Amavasya (Somvati)</td> 
				  <td>04-02-2019(Monday)</td>
				</tr>
				<tr>
				  <td>Basant Panchami</td> 
				  <td>10-02-2019(Sunday)</td>
				</tr>
				<tr>
				  <td>Maghi Purnima</td> 
				  <td>19-02-2019(Tuesday)</td>
				</tr>
				<tr>
				  <td>Mahashivratri</td> 
				  <td>04-03-2019(Monday)</td>
				</tr>
			  </table>
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
        if (fct == '30819') {
            $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val("05/03/2019").change();
        }
        if (tct == '30819') {
            $('#<?= CHtml::activeId($model, "bkg_pickup_date_date") ?>').val("14/01/2019").change();
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

