

<div class="panel" >
    <div class="panel-body pt0">

        <?php
                    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                        'id' => 'add-remark', 'enableClientValidation' => true,
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
            <? if(!$success){
                if($type == 1){
                    print_r($logModel->getErrors());
                }
                if($type == 2){
                    print_r($model->getErrors());
                }
            }
            ?>
            <?= $form->hiddenField($logModel, 'blg_booking_id', ['value' => $model->bkg_id]); ?>
            <div class="col-xs-12 p0">
                <?= $form->textAreaGroup($logModel, 'blg_desc', array('label' => '', 'rows' => 10, 'cols' => 50)) ?>
                <div class="col-xs-12 text-center pb20">
                    <button class="btn btn-primary mt5" type="submit" style="width: 140px;">Submit</button>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>

		<div class="row">
		<header>Remarks List</header>
<?
		  if (!empty($dataProvider)) {
                    $params = array_filter($_REQUEST);
                    $dataProvider->getPagination()->params = $params;
                    $dataProvider->getSort()->params = $params;
                    $this->widget('booster.widgets.TbGridView', array(
                        'responsiveTable' => true,
                        'dataProvider' => $dataProvider,
                        'template' => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive p0'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                        'itemsCssClass' => 'table table-striped table-bordered dataTable mb0',
                        'htmlOptions' => array('class' => 'panel panel-primary  compact'),
                        'columns' => array(
                            array('name' => 'remarks', 'value' => '$data[remarks]', 'sortable' => true,
                                'headerHtmlOptions' => array('class' => 'col-xs-1'),
                                'header' => 'Remarks'),
                            array('name' => 'createdate', 'value' => '$data[createdate]', 'sortable' => true,
                                'headerHtmlOptions' => array('class' => 'col-xs-1'),
                                'header' => 'Created Date'),
                    )));
                }
?>
		</div>

    </div>
</div>
