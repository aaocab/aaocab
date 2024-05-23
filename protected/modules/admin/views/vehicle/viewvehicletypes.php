
        <?php
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'vhtlistform', 'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error'
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
    
    <div class="col-xs-12">

        <?php
        if (!empty($dataProvider)) {
            $this->widget('booster.widgets.TbExtendedGridView', array(
                'id' => 'vhtlist',
                'responsiveTable' => true,
                'dataProvider' => $dataProvider,
                'filter' => $model,
                'template' => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                'itemsCssClass' => 'table table-striped table-bordered mb0',
                'htmlOptions' => array('class' => 'table-responsive panel panel-primary  compact'),
                //     'ajaxType' => 'POST',
                'columns' => array(
                    array('name' => 'vht_make', 'value' => '$data[vht_make]',
                        'sortable' => true,
                        'filter' => CHtml::activeTextField($model, 'vht_make', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vht_make'))),
                        'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'),
                        'htmlOptions' => array('class' => 'text-center'),
                        'header' => 'Make'),
                    array('name' => 'vht_model', 'value' => '$data[vht_model]',
                        'sortable' => true,
                        'filter' => CHtml::activeTextField($model, 'vht_model', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vht_model'))),
                        'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'),
                        'htmlOptions' => array('class' => 'text-center'),
                        'header' => 'Model'),
                    array('name' => 'vht_active', 'value' => '$data[vht_active]== 1?"Active":"Inactive"',
                        'sortable' => true,
                        'filter' => false,
                        'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'),
                        'htmlOptions' => array('class' => 'text-center'),
                        'header' => 'Status'),
                    
            )));
        }
        ?>
    </div>
</div>

   <?php $this->endWidget(); ?>