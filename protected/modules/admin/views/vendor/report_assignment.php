<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<div class="row" >

    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
				<?php
				$checkContactAccess	 = Yii::app()->user->checkAccess("bookingContactAccess");
				$checkExportAccess	 = Yii::app()->user->checkAccess("Export");
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'cityCoverageList', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					// Please note: When you enable ajax validation, make sure the corresponding
					// controller action is handling ajax validation correctly.
					// See class documentation of CActiveForm for details on this,
					// you need to use the performAjaxValidation()-method described there.
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class' => '',
					),
				));
				/* @var $form TbActiveForm */
				?>
                <div class="row">

				</div>

				<div class="col-xs-12">
					<div class="row table table-bordered">
						<?php
						if (!empty($dataProvider))
						{
//                           print_r($dataProvider);
//die;
                           
							$params									 = array_filter($_REQUEST);
							$dataProvider->getPagination()->params	 = $params;
							$dataProvider->getSort()->params		 = $params;
							$this->widget('booster.widgets.TbGridView', array(
								'id'				 => 'vendorListGrid',
								'responsiveTable'	 => true,
								'dataProvider'		 => $dataProvider,
								'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row'><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
								'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
								'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
								//       'ajaxType' => 'POST',
								'columns'			 => array(
									array('name'	 => 'vnd_name', 'value'	 => function($data) {
											echo $data['vnd_name'];
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-3'), 'header'			 => 'Vendor Name'),
									array('name'	 => 'system_assigned_bookings', 'value'	 => function($data) {
											echo ($data['system_assigned_bookings'] > 0) ? $data['system_assigned_bookings'] : 0;
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'System Assigned Bookings (Lifetime)'),
									array('name'	 => 'system_assigned_bookings_30', 'value'	 => function($data) {
											echo ($data['system_assigned_bookings_30'] > 0) ? $data['system_assigned_bookings_30'] : 0;
										}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-2 text-center'), 'htmlOptions'							 => array('class' => 'text-right'), 'header'								 => 'System Assigned Bookings (Last 30days)'),
									array('name'	 => 'manual_assigned_bookings', 'value'	 => function($data) {
											echo ($data['manual_assigned_bookings'] > 0) ? $data['manual_assigned_bookings'] : 0;
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Manual Assigned Bookings (Lifetime)'),
									array('name'	 => 'manual_assigned_bookings_30', 'value'	 => function($data) {
											echo ($data['manual_assigned_bookings_30'] > 0) ? $data['manual_assigned_bookings_30'] : 0;
										}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Manual Assigned Bookings (Last 30days)'),
									array('name' => 'last_login', 'value' => $data['last_login'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Last App Login Date/Time'),
							)));
						}
						?> 
					</div>
				</div>  
			</div>
			<?php $this->endWidget(); ?>

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
        $('#vendorListGrid').yiiGridView('update');
    }

    function viewRating(vndId) {
        //var href2 = $(obj).attr("href");
        $href2 = $adminUrl + "/rating/listbyvendor";
        $vendorId = vndId;
        $.ajax({
            "url": $href2,
            "type": "GET",
            "dataType": "html",
            data: {"vendor_id": $vendorId},
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                if ($('body').hasClass("modal-open"))
                {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }

            }
        });
        return false;
    }
</script>
