<div class="row" >
    <div class="col-xs-12">


        <div class="row">
            <div class="col-xs-12">
                <div class="  table table-bordered">
                    <?php
                    if (!empty($dataProvider))
                    {
                        $params                                = array_filter($_REQUEST);
                        $dataProvider->getPagination()->params = $params;
                        $dataProvider->getSort()->params       = $params;
                        $this->widget('booster.widgets.TbExtendedGridView', array(
                            'id'                => 'vendorListGrid1',
                            'responsiveTable'   => true,
                            'dataProvider'      => $dataProvider,
                            'template'          => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row'><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                            'itemsCssClass'     => 'table  table-bordered dataTable mb0',
                            'htmlOptions'       => array('class' => 'panel panel-primary compact'),
                            'columns'           => array(
                                array('name'  => 'CreateDate', 'value' => function ($data) {
                                        echo date("d/M/Y", strtotime($data['CreateDate'])) . "<br>" . date("h:i A", strtotime($data['CreateDate']));
                                    }, 'sortable'          => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions'       => array('class' => 'text-right'), 'header'            => 'Attendance Date'),
                                array('name'  => 'totalHrs', 'value' => function ($data) {
                                        echo round($data['totalHrs'], 2);
                                    }, 'sortable'          => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions'       => array('class' => 'text-right'), 'header'            => 'Total Hours'),
                                array(
                                    'header'            => 'Action',
                                    'class'             => 'CButtonColumn',
                                    'htmlOptions'       => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
                                    'headerHtmlOptions' => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
                                    'template'          => '{log}',
                                    'buttons'           => array(
                                        'log'         => array(
                                            'click'    => 'function(){
                                            $href = $(this).attr(\'href\');
                                            jQuery.ajax({type: \'GET\',
                                            url: $href,
                                            success: function (data)
                                            {
                                                var box = bootbox.dialog({
                                                    message: data,
                                                    title: \'Admin On/Off Log\',
                                                    size: \'medium\',
                                                    onEscape: function () {

                                                        // user pressed escape
                                                    }
                                                });
                                            }
                                        });
                                    return false;
                                }',
                                            'url'      => 'Yii::app()->createUrl("aaohome/admin/adminLogTime", array("csrId" => $data[adm_id],"date"=>date("d/m/Y", strtotime($data["CreateDate"]))))',
                                            'imageUrl' => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
                                            'label'    => '<i class="fa fa-list"></i>',
                                            'options'  => array('data-toggle' => 'ajaxModal',
                                                'style'       => '',
                                                'class'       => 'btn btn-xs conshowlog p0',
                                                'title'       => 'Admin Log'),
                                        ),
                                        'htmlOptions' => array('class' => 'center'),
                                    )
                                )
                        )));
                    }
                    ?> 
                </div>
            </div>  
        </div> 
    </div>  
</div>  

<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script>
    function refreshVendorGrid()
    {
        $('#vendorListGrid1').yiiGridView('update');
    }

</script>


