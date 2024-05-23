<?php
$callback = Yii::app()->request->getParam('callback', 'loadList');
$title = ($model->isNewRecord) ? "Add" : "Edit";
$js = "window.$callback();";
//$carRate = Rate::model()->getCabRateDetailsbyRutJSON($model->bkg_route_id);
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
/* @var $model Booking */

if (Yii::app()->request->isAjaxRequest) {
    $cls = "";
}
else {
    $cls = "col-lg-4 col-md-6 col-sm-8 pb10";
}
?>

<style>
    .form-horizontal .form-group{
        margin: 0;
    }
    .datepicker.datepicker-dropdown.dropdown-menu ,
    .bootstrap-timepicker-widget.dropdown-menu,
    .yii-selectize.selectize-dropdown
    {z-index: 9999 !important;}

    .selectize-input {
        min-width: 0px !important; 
        width: 100% !important;
    }


</style>


<div class="row">
    <div class="<?= $cls ?>" style="float: none; margin: auto">
        
    </div>
</div>

<?php echo CHtml::endForm(); ?>

<script type="text/javascript">
    $(document).ready(function ()
    {

        $('.bootbox').removeAttr('tabindex');
        //  populateData();

    });


    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });
</script>
