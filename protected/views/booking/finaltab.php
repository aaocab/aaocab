<style>
    #section2{
        padding-top: 20px;padding-bottom: 20px; background-color: #FFFFFF;
    }

    #error-show .error{
        color: #a94442 !important;
    }
</style>

<?
//Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?>
<div id="ls" >
    <?
    $this->renderPartial('conview', ['model' => $model], true, true);
    ?>
</div>


