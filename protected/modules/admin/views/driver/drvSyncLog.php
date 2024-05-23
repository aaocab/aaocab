
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
               <?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'syncFail', 'enableClientValidation' => true,
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
                
               <div class="row mt10" >
                    <div class="col-xs-12 col-sm-6 col-md-4"> 
                        <?= $form->textFieldGroup($model, 'search', array('label' => 'Booking Id:', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Search By Booking Id')))) ?>
                    </div>
				   
				    <div class="col-xs-12 col-sm-6 col-md-4"> 
                        <?= $form->textFieldGroup($model, 'searchDrvName', array('label' => 'Driver Name', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Driver Name')))) ?>
                    </div>
                    
                   <div class="col-xs-12 col-sm-6 col-md-2"><br>
                    <button class="btn btn-primary full-width mt5" type="submit"  name="synFailSearch">Search</button>
                   </div>
                </div>  
				
		<?php $this->endWidget(); ?>
                <div class="row"> 
					
					<?php
$pageno				 = Yii::app()->request->getParam('page');

if (!empty($dataProvider))
{
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'selectableRows'	 => 2,
		'id'				 => 'driverListGrid',
		'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt2'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		//'ajaxType' => 'POST',
		'columns'			 => array(
			array('name'	 => 'drv_name',
				// 'value' => '$data->drv_name', 
				'value'	 => function ($data)
				{
					echo $data["drv_name"];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('style' => 'word-break: break-all;min-width:90px'), 'header'			 => 'Driver Name'),
			array('name' => 'dul_bkg_id',
				'value'	 => '$data["dul_bkg_id"]',
				'value'	 => function ($data)
				{
					echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['dul_bkg_id'], 'viewType' => 'driver']), ["class" => ""]);
				},
				'sortable'			 => true, 'header'			 => "booking Id"),
			array('name' => 'dul_event_date', 'value' => '$data[dul_event_date]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Hit time'),
			array('name' => 'dul_event_id', 'value' => '$data[dul_event_id]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Event'),
			array('name' => 'dul_drv_appToken', 'value' => '$data[dul_drv_appToken]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Token'),
			array('name' => 'dul_data', 'value' => 
function ($data){
echo CHtml::link("Info", Yii::app()->createUrl("admin/driver/errorView", ["id" => $data['dul_id']]), ["class" => "", "onclick" => "return viewDetail(this)"]);
}, 
'sortable' => true, 'headerHtmlOptions' => array(),'htmlOptions'		 => array('style' => 'word-break: break-all;min-width:90px'), 'header' => 'Data'),
			////array('name' => 'dul_url', 'value' => '$data[dul_url]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Hit url'),
			array('name' => 'dul_error', 'value' => '$data[dul_error]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Error'),
		
						array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
							      
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{checkProceed}{Proceed}{NoNeedProcess}',
									'buttons'			 => array(
									'checkProceed'		 => array(
											'click'		 => 'function(){
											var con = confirm("Are you sure you want to Proceed?");
											return con;
										}',
												'url'		 => 'Yii::app()->createUrl("admin/driver/checkProceed", array(\'dulId\' => $data["dul_id"]))',
												'imageUrl'	 => false,
										        'visible'	 => '$data["dul_status"]==0?true:false;',
												'label'		 => '<i class="fa fa-check-circle">Process</i>',
												'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger', 'title' => 'Check Proceed'),
									),
'Proceed'		 => array(
											'click'		 => '',
											
												'imageUrl'	 => false,
										        'visible'	 => '$data["dul_status"]==1?true:false;',
												'label'		 => '<i class="fa fa-check-circle">Synced</i>',
												'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-success', 'title' => 'Synced'),
									),
'NoNeedProcess'		 => array(
											'click'		 => '',
											
												'imageUrl'	 => false,
										        'visible'	 => '$data["dul_status"]==5?true:false;',
												'label'		 => '<i class="fa fa-block-circle">Blocked</i>',
												'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-warning', 'title' => 'Blocked'),
									),

								   'htmlOptions'	 => array('class' => 'center')))
						
		)
			)
	);
}
  else
		{
				echo '<div class="col-xs-12"><div class="table-responsive panel panel-primary compact" id="synctlist"><div class="panel-heading"><div class="row m0"><div class="col-xs-12 col-sm-6 pt5"></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="panel-body"><table class="table table-striped table-bordered mb0 table"><thead><tr><th id="synclist_c0">Driver Name</th><th id=synclist_c1">Booking Id</th><th id="synclist_c2">Hit Time</th><th id="synclist_c3">Event</th><th id="synclist_c4">Token</th><th class="col-xs-1 text-center" style="min-width: 100px;" id="synclist_c5">Data</th></tr></thead><tbody><tr><td colspan="6" class="empty"><span class="empty">No results found.</span></td></tr></tbody></table></div><div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="keys" style="display:none" title="/admpnl/driver/syncFail"></div></div></div>';
		}
?>	
                </div> 
            </div>  

        </div>  
    </div>
</div>
<script type="text/javascript">
function viewDetail(obj) {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Sync Details',
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

function checkProceed(obj, dulId, chkProceed)
{
	  bootbox.confirm({
			title: "Sync Proceed",
            message: "Are you sure you want to Proceed?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-info'
                },
                cancel: {
                    label: 'CANCEL',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result==true) {
                    var href = '<?= Yii::app()->createUrl("admin/driver/checkProceed"); ?>';
                    $.ajax({'type': 'GET', 
						'url': href,
                        'data': {'dulId,': dulId,"status": obj},
                        success: function (data)
                        {
                            bootbox.hideAll()
                            window.location.reload(true);
                        }
                    });
                }
            }
        });
	
}
</script>