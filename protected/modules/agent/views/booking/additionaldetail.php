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
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>
<div id="ad1" >
    <?
    $this->renderPartial('conview', ['model' => $model], true, true);
    ?>

</div>

