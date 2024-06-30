<div class="panel">
    <div class="panel-heading"></div>
    <div class="panel-body">
<div class="col-12 mb30">
<?php 
$api			 = Config::getGoogleApiKey('browserapikey');
?>
<script>
    $('#collapseExample').on('hidden.bs.collapse', function () {
        // do something…
    })
</script>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>
<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'newAddressForm',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => 'form-horizontal',
	),
		));
$requiredFields	 = [];
$user = UserInfo::getUserId();
?>
<div class="row">
    <div class="col-md-6">
        <div>Provide Street Address</div>
        <?php
        $requiredFields[]	 = CHtml::activeId($model, "from_place");
//        $this->widget('application.widgets.PlaceAutoComplete',
//          ['model' => $model, 'attribute' => "from_place",  "user" => $user]);
            $this->widget('application.widgets.PlaceAutoComplete', ['model'			 => $model, 'attribute'		 => 'from_place',
											'onPlaceChange'	 => "function(event, pacObject){ pacObject.validateAddress(event);}",
											'enableOnLoad'	 => true,
											'htmlOptions'	 => ['class' => "form-control", "autocomplete" => "section-new", 'placeholder' => "Location"]
										]);
        ?>
        <div class="col-3 heading-part mb10">
            <button type="button" id="saveNewAddreses" class="btn btn-effect-ripple btn-success p5 mt10" name="searchaddress" onclick="searchByAddress();">Submit</button>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

</div>
</div>
</div>

<script type="text/javascript">
function searchByAddress()
    {
                var reqFields = <?= CJavaScript::encode($requiredFields) ?>;
                
                $.each(reqFields, function (key, value)
                {
                   
                    var frmcityid = $('#BookingRoute_from_place').val();
                    var data = JSON.parse(frmcityid); 
                    var lat = data.coordinates.latitude;
                    var long = data.coordinates.longitude;
                    var href = '<?= Yii::app()->createUrl("aaohome/city/searchRouteByAddress"); ?>';
                   $.ajax({type: 'GET',
                       "url": href,
                       "data": {"latitude": lat, "longitude": long},
                       "success": function (data)
                       {
                           acctbox = bootbox.dialog({
                           message: data,
                           title: 'Address Details',
                           size: 'medium',
                           onEscape: function ()
                           {

                           }
                           });
                           acctbox.on('hidden.bs.modal', function (e)
                           {
                               $('body').addClass('modal-open');
                           });

                       }
                   });
                });
        }
        
</script>