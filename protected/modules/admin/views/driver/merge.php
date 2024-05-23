<style>
    
   
	.modal {
  overflow-y:auto;
}
	
</style>
<div class="row">
    <div class="col-md-12">
        <div class="panel" >
            <div class="panel-body panel-no-padding p0 pt10">

                <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
					<?php
					if (!empty($dataProvider))
					{
						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->pageSize = 20;
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'mergeform',
							'responsiveTable'	 => true,
							'selectableRows'	 => 2,
							'emptyText' => $active==11?'<p align="center">Search driver with name,email and phone</p>':"No results found",
							'dataProvider'		 => $dataProvider,
							'filter'			 => $model,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-5 pt5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered mb0',
							'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
							'columns'			 => array(
//								array('class' => 'CCheckBoxColumn',
//									'id' => 'drv_mchecked[]', 
//									'value' => '$data["drv_id"]', 
//									'selectableRows' => '{items}',
//									'header'		 => 'html',
//									'selectableRows' => 2,
//									'headerTemplate' => '<label>{item}<span></span></label>'.$data["drv_id"],
//									'htmlOptions'	 => array('style' => 'width: 20px'),
//									),
								
								
								
								 array(
				'class'			 => 'CCheckBoxColumn',
				'header'		 => 'html',
				'id'			 => 'drv_id',
				'selectableRows' => '{items}',
				'selectableRows' => 2,
				'value'			 => '$data["drv_id"]',
				'headerTemplate' => '<label>{item}<span></span></label>'.$data["drv_id"],
				'htmlOptions'	 => array('style' => 'width: 20px'),
			),
								array('name'	 => 'drv_name',
									'filter' => CHtml::activeTextField($model, 'drv_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('drv_name'))),
									'value'	 => function ($data) {
										echo $data['drv_name'] . ' (' . $data['vendor_names'] . ')';
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Name'),
								array('name'	 => 'drv_phone', 
									'filter' => CHtml::activeTextField($model, 'drv_phone', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('drv_phone'))), 'value'	 => function($data) {
										echo  $data['drv_phone'];
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Phone'),
											
											
											
											array('name'	 => 'drv_email', 
									'filter' => CHtml::activeTextField($model, 'drv_email', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('drv_email'))), 'value'	 => function($data) {
										echo  $data['drv_email'];
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => 'Email'),
						)));
					}
					?>
                </div>
            </div>
            <div class="panel-footer">
                <div class="col-xs-12 pl0 ">
                    <button type="button" class="btn btn-primary" onclick="mergeProcess()">Merge</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
		$("#drv_id_all").click(function (){
		if (this.checked){
			$('#mergeform .checker span').addClass('checked');
			$('#mergeform input[name="drv_id[]"]').attr('checked', 'true');
		}
		else{
			$('#mergeform .checker span').removeClass('checked');
			$('#mergeform input[name="drvd_id[]"]').attr('checked', 'false');
		}
	 });
   });

    function mergeProcess() {
        var $keys = [];
        $('[name="drv_id[]"]').each(function () {
            if ($(this).prop('checked') == true) {
                $keys.push($(this).val());
            }
        });
        var numrows = $keys.length;

        if (numrows > 1){
            bootbox.alert("Select Only 1 driver to merge");
        }
		else if (numrows < 1){
            bootbox.alert("Please select atleast 1 driver");
        } 
		else {
            $strDrvKeys = $keys.join();
            window.location.href = '<?php echo Yii::app()->createUrl('admin/driver/mergedriver'); ?>?drvid=' +<?= $drvId ?> + '&mdrvid=' + $strDrvKeys;
        }
    }

</script>
