<style>
    #section2{
        padding-top: 20px;padding-bottom: 20px; background-color: #FFFFFF;
    }
    #section7{
        margin-bottom: 0
    }
    #error-show .error{
        color: #a94442 !important;
    }
</style>

<?
//$cabtype = VehicleTypes::model()->getCarType();
//$rtArr = explode('-', $route);
?>
<div id="rt1" >
    <?
    $this->renderPartial('rtview', ['model' => $model, 'cabratedata' => $cabratedata], true, true);
    ?>

</div>

<script type="text/javascript">
    function opentns() {
        $href = '<?= Yii::app()->createUrl('index/tns') ?>';
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }

    $('#ask_customer').change(function () {
        if ($("#ask_customer").is(':checked'))
        {
            $href = '<?= Yii::app()->createUrl('booking/cabratepartial') ?>';
            jQuery.ajax({type: 'POST', url: $href,
                success: function (data) {
                    box = bootbox.dialog({
                        message: data,
                        title: '',
                        onEscape: function () {
                            box.modal('hide');
                        }
                    });
                }
            });
        }
    });
//    function getRouteName() {
//        fcity = $('#<? //= CHtml::activeId($model, "bkg_from_city_id")          ?>').val();
//        tcity = $('#<? //= CHtml::activeId($model, "bkg_to_city_id")          ?>').val();
//        
//        if (fcity != '' && tcity != '') {
//            $.ajax({
//                "type": "GET",
//                "dataType": "json",
//                async: false,
//                "url": "<? //= CHtml::normalizeUrl(Yii::app()->createUrl('booking/getroutename'))          ?>",
//                "data": {"fcity": fcity, 'tcity': tcity},
//                success: function (data1)
//                {
//                    if (data1.rutname) {
//                        document.getElementById('booking1-form').action = "/" + data1.rutname;
//                    }
//                }
//            });
//        }
//    }
    $('#<?= CHtml::activeId($model, "bkg_from_city_id") ?>').change(function () {
        populateData();
    });

    



</script>
