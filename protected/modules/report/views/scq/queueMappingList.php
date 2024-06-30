<style>
    .table-flex {
        display: flex;
        flex-direction: column;
    }
    .tr-flex {
        display: flex;
    }
    .th-flex, .td-flex{
        flex-basis: 35%;
    }
    .thead-flex, .tbody-flex {
        overflow-y: scroll;
    }
    .tbody-flex {
        max-height: 250px;
    }
</style>
<?php
if ($checkEditAccess)
{
    ?>
    <div class="row">
        <div class="col-xs-6 pb10 pr10 ml20">
            <a class="btn btn-primary mb10" onclick="addQueueMapping()">Add Queue</a>
        </div>
    </div>
<?php } ?> 
<div class="col-xs-12 text-center">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success" style="padding: 10px">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php endif; ?>
</div>
<?php
$form      = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'                     => 'team-mapping-form',
    'enableClientValidation' => true,
    'method'                 => 'get',
    'clientOptions'          => array(
        'validateOnSubmit' => true,
        'errorCssClass'    => 'has-error'
    ),
    'enableAjaxValidation'   => false,
    'errorMessageCssClass'   => 'help-block',
    'htmlOptions'            => array(
        'class' => '',
    ),
        ));
/* @var $form TbActiveForm */
?>
<div class='row p20'>
    <div class="col-xs-12 col-sm-2 col-md-4">
        <label class="control-label">CSR</label>
        <?php
        $csr       = Admins::model()->csrList();
        $this->widget('booster.widgets.TbSelect2', array(
            'model'       => $model,
            'attribute'   => 'csrList',
            'val'         => $model->csrList,
            'data'        => [-1 => 'All'] + $csr,
            'htmlOptions' => array('style'       => 'width:100%',
                'placeholder' => 'CSR list')
        ));
        ?> 
    </div>
    <div class="col-xs-12 col-sm-2 col-md-4">
        <div class="form-group">
            <label class="control-label">Teams</label>
            <?php
            $fetchList = Teams::getList();
            $this->widget('booster.widgets.TbSelect2', array(
                'model'       => $model,
                'attribute'   => 'teamList',
                'val'         => $model->teamList,
                'data'        => [-1 => 'All'] + $fetchList,
                'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Teams')
            ));
            ?>
        </div> 
    </div>
    <div class="col-xs-12 col-sm-2 col-md-2">   
        <label class="control-label"></label>
        <?php echo CHtml::button('Submit', array('class' => 'btn btn-primary full-width submitData')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>
</div>
<?php
if (!empty($dataProvider))
{
    $checkEditAccess    = Yii::app()->user->checkAccess("editTeamsQueueMapping");
    $this->widget('booster.widgets.TbGridView', array(
        'responsiveTable'   => true,
        'dataProvider'      => $dataProvider,
        'template'          => "<div class='panel-heading'><div class='row m0'>
													<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
											</div></div>
											<div class='panel-body table-responsive'>{items}</div>
											<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
        'itemsCssClass'     => 'table table-striped table-bordered dataTable mb0',
        'htmlOptions'       => array('class' => 'panel panel-primary  compact'),
        'columns'           =>
        array
            (
            array('name'  => 'tqm_tea_name', 'value' => function ($data) {
                    echo $data['tqm_tea_name'];
                }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Team Name'),
            array('name'  => 'tqm_priority', 'value' => function ($data) {
                    echo $data['tqm_priority'];
                }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Priority'),
            array('name'  => 'tqm_queue_weight', 'value' => function ($data) {
                    echo $data['tqm_queue_weight'];
                }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Queue Weight'),
            array('name'  => 'tqm_queue_name', 'value' => function ($data) {
                    echo $data['tqm_queue_name'];
                }, 'sortable'          => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Queue Name'),
            array('name'  => 'tqm_active', 'value' => function ($data) {
                    echo ($data['tqm_active'] == 1) ? 'Activated' : 'Deactivated';
                }, 'sortable'          => false, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header'            => 'Status(Activated/Deactivated)'),
            array(
                'header'            => 'Action',
                'class'             => 'CButtonColumn',
                'htmlOptions'       => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
                'headerHtmlOptions' => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
                'template'          => '{modify}',
                'visible'           => $checkEditAccess,
                'buttons'           => array(
                    'modify' => array(
                        'click'    => 'function(e){
                                                    $href = $(this).attr(\'href\');
                                                    $.ajax({ 
                                                    url: $href,
                                                    success: function (data)
                                                    {
                                                        var box = bootbox.dialog({
							                                 message: data,
                                                            title: "Set Priority",
															size: "large",
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
                        'url'      => 'Yii::app()->createUrl("admin/Scq/modifyPriority", array("Id" => $data["tqm_id"]))',
                        'imageUrl' => false,
                        'label'    => '<i class="fa fa-edit"></i>',
                        'options'  => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs  p0', 'title' => 'Modify'),
                    )
                )
            ))
    ));
}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.submitData', function () {
            var csr = $("#TeamQueueMapping_csrList").val();
            var teams = $("#TeamQueueMapping_teamList").val();
            window.location.href = '/aaohome/Scq/FetchList/?csr=' + csr + '&teams=' + teams;
        });
    });

    function addQueueMapping()
    {
        var href = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/Scq/ModifyPriority')) ?>';
        jQuery.ajax({type: 'GET', url: href,
            success: function (data) {
                bootbox = bootbox.dialog({
                    message: data,
                    size: 'large',
                    title: 'Add Queue Mapping',
                    onEscape: function () {
                        bootbox.hide();
                        bootbox.remove();
                        location.reload();
                    },
                });
            }
        });
    }
</script>












