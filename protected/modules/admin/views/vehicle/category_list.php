
        <?php
        $stateList = CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'sms-form', 'enableClientValidation' => true,
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
    <a class="btn btn-primary mb10" href="<?= Yii::app()->createUrl('admin/vehicle/addcategory') ?>" style="text-decoration: none;margin-right: 15px;float: right;">Add new</a>
    <div class="col-xs-12">

        <?php
        if (!empty($dataProvider)) {
            $this->widget('booster.widgets.TbExtendedGridView', array(
                'id' => 'vehicle_category_grid',
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
                    array('name' => 'vct_label', 'value' => '$data[vct_label]',
                        'sortable' => true,
                        'filter' => CHtml::activeTextField($model, 'vct_label', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vct_label'))),
                        'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'),
                        'htmlOptions' => array('class' => 'text-center'),
                        'header' => 'Vehicle Category Label'), //Modified the table header name
                    array('name' => 'vct_desc', 'value' => '$data[vct_desc]',
                        'sortable' => true,
                        'filter' => CHtml::activeTextField($model, 'vct_desc', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vct_desc'))),
                        'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'),
                        'htmlOptions' => array('class' => 'text-center'),
                        'header' => 'Description'),
                    array('name' => 'vct_active', 'value' => '$data[vct_active]== 1?"Active":"Inactive"',
                        'sortable' => true,
                        'filter' => false,
                        'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'),
                        'htmlOptions' => array('class' => 'text-center'),
                        'header' => 'Status'),
                    array(
                        'header' => 'Action',
                        'class' => 'CButtonColumn',
                        'htmlOptions' => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
                        'headerHtmlOptions' => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
                        'template' => '{edit}{activecategory}{deactivecategory}{vehicle_type}',
                        'buttons' => array(
                            'edit' => array(
                                'url' => 'Yii::app()->createUrl("admin/vehicle/addcategory", array(\'category_id\' => $data[vct_id]))',
                                'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\city\edit_booking.png',
                                'label' => '<i class="fa fa-edit"></i>',
                                'options' => array('style' => '', 'class' => 'btn btn-xs editBtn p0', 'title' => 'Edit'),
                            ),
                            'activecategory' => array(
                                "click" => "function(e){   var con = confirm('are you sure want to activate vehicle category?'); 
                                                        if(con){change_status(this);}}",
                                'url' => 'Yii::app()->createUrl("admpnl/vehicle/changevctstatus", array("vct_id" => $data[vct_id],"vct_active"=>$data[vct_active]))',
                                'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
                                'visible' => '($data[vct_active] == 0)',
                                'label' => '<i class="fa fa-toggle-on"></i>',
                                'options' => array('data-toggle' => '', 'id' => '', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs activateCat p0', 'title' => 'Activate Category')
                            ),
                            'deactivecategory' => array(
                                "click" => "function(e){   var con = confirm('Are you sure want to deactivate vehicle category?');
                                                        if(con){change_status(this);}}",
                                'url' => 'Yii::app()->createUrl("admpnl/vehicle/changevctstatus", array("vct_id" => $data[vct_id],"vct_active"=>$data[vct_active]))',
                                'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\active.png',
                                'visible' => '($data[vct_active] == 1)',
                                'label' => '<i class="fa fa-toggle-on"></i>',
                                'options' => array('data-toggle' => '', 'id' => '', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs deactivateCat p0', 'title' => 'Deactive Category')
                            ),
                            'vehicle_type' => array(
                                "click" => "function(e){   view_vehicle_type(this);}",
                                'url' => 'Yii::app()->createUrl("admpnl/vehicle/viewtypes", array("vct_id" => $data[vct_id]))',
                                'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
                                
                                'label' => '<i class="fa fa-toggle-on"></i>',
                                'options' => array('data-toggle' => 'ajaxModal', 'id' => '', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs showVehicleTypes  p0', 'title' => 'View Type')
                            ),
                            'htmlOptions' => array('class' => 'center'),
                        ))
            )));
        }
        ?>
    </div>
</div>
   <?php $this->endWidget(); ?>
<script>
    function refreshCategoryGrid() {
        $('#vehicle_category_grid').yiiGridView('update');
    }
    function change_status(obj) {
        event.preventDefault();
        //alert('hello');
        $href = $(obj).attr("href");
        //alert($href);
        $.ajax({
            type: "GET",
            url: $href,
            success: function (data)
            {
                if (data)
                {
                    refreshCategoryGrid();
                } else
                {
                    alert('Sorry error occured');
                }

            }, error: function (xhr, status, error) {
                alert('Sorry error occured');
            }
        });
        return false;
    }
    function view_vehicle_type(obj) {
        event.preventDefault();
        //alert('hello');
        $href = $(obj).attr("href");
        //alert($href);
        $.ajax({
            type: "GET",
            url: $href,
            success: function (data)
            {
                var box = bootbox.dialog({
                                            message: data,
                                            title: 'Vehicle Types',
                                            size: 'large',
                                            onEscape: function () {

                                                // user pressed escape
                                            }
                                        });
            }, error: function (xhr, status, error) {
                alert('Sorry error occured');
            }
        });
        
        return false;
    }





</script>