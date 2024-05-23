<?
$js = "if($.isFunction(window.refreshAdmin))
{
window.refreshAdmin();
}
else
{
window.location.reload();
}

";
?>

<div id="admin-content" class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">
            <div class="panel">


                <div class="panel-body panel-no-padding">
                    <div class="well p5 m0">
                        <?php
                        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                            'id' => 'adminform',
                            'enableClientValidation' => true,
                            'clientOptions' => array(
                                'validateOnSubmit' => true,
                                'errorCssClass' => 'has-error',
                                'afterValidate' => 'js:function(form,data,hasError){
                        if(!hasError){
                                $.ajax({
                                "type":"POST",
                                "dataType":"html",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                "data":form.serialize(),
                                "success":function(data1){                               
                                        $("#admin-content").parent().html(data1);
                                    
                                    },
                                });
                                
                                }
                        }'
                            ),
                            // Please note: When you enable ajax validation, make sure the corresponding
                            // controller action is handling ajax validation correctly.
                            // See class documentation of CActiveForm for details on this,
                            // you need to use the performAjaxValidation()-method described there.
                            'enableAjaxValidation' => false,
                            'errorMessageCssClass' => 'help-block',
                            'htmlOptions' => array(
                                'class' => '',
                            ),
                        ));
                        /* @var $form TbActiveForm */
                        ?>
                        <div class="row">
                            <div class="col-xs-7">
                                <?= $form->textFieldGroup($model, 'adm_fname', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'search by name')))) ?>
                            </div>

                            <div class="col-xs-5 pr5">
                                <button class="btn btn-primary" type="submit" name="searchAdmin" id="searchAdmin">Search</button>
                            </div>
                        </div>

                        <?php $this->endWidget(); ?>

                    </div>
                    <?
                    if (!empty($dataProvider)) {
                        $this->widget('booster.widgets.TbGridView', array(
                            'id' => 'assinvendorgrid',
                            'responsiveTable' => true,
                            'dataProvider' => $dataProvider,
                            'template' => "<div class='panel-heading'><div class='row m0'>
					<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
					</div></div>
					<div class='panel-body'>{items}</div>
					<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                            'itemsCssClass' => 'table table-striped table-bordered mb0',
                            'htmlOptions' => array('class' => 'table-responsive panel panel-primary  compact'),
                            //  'ajaxType' => 'POST',
                            'columns' => array(
                                array('name' => 'adm_fname', 'value' => '$data->adm_fname', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'First Name'),
                                array('name' => 'adm_fname', 'value' => '$data->adm_fname', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Last Name'),
                                array(
                                    'header' => 'Action',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('style' => 'white-space:nowrap;text-align: center'),
                                    'headerHtmlOptions' => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
                                    'template' => '{assign}',
                                    'buttons' => array(
                                        'assign' => array(
                                            'url' => 'Yii::app()->createUrl("rcsr/booking/assigncsr", array(\'csrid\' => $data->adm_id,\'bkid\'=>' . $bkid . '))',
                                            'imageUrl' => false,
                                            'label' => 'Assign',
                                            'options' => array('data-toggle' => 'ajaxModal', 'onclick' => 'return assignCSR(this)', 'class' => 'btn btn-xs btn-info vbtn', 'id' => 'btn_' . $bkid, 'title' => 'Assign'),
                                        ),
                                        'htmlOptions' => array('class' => 'center'),
                                    ))
                        )));
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

//    function assignCSR(bkid) {
//        $('#btn_' + bkid).removeClass('btn-info');
//        $('#btn_' + bkid).addClass('btn-success');
//        $('.vbtn').addClass('disabled');
//    }

//
    


</script>
