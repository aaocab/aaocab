<style type="text/css">
    .yii-selectize ,.selectize-input  {
        min-width: 100px!important;   
	}
</style>

<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="panel-advancedoptions" >
    <div class="row">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'pricesurgesearch-form', 'enableClientValidation' => true,
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
		<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
			<div class="form-group  ">
				<label class="control-label">Dynamic Table List</label>
				<select name="table_name" id ="table_name">
					<?php
					
					foreach ($getTableList as $val)
					{
					?>
						<option value="<?php echo $val['dps_name']; ?>"   <?php if($_REQUEST['table_name']!='') echo"selected"; ?>  ><?php echo str_replace('dynprice_', ' ', $val['dps_name']); ?></option>
					<?php
					}
					?>
				</select>
			</div>
		</div>
        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
			<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary full-width')); ?>
		</div>
		<?php $this->endWidget(); ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$arr = [];
								if (is_array($dataProvider->getPagination()->params))
								{
									$arr = $dataProvider->getPagination()->params;
								}
								$params1							 = $arr + array_filter($_GET + $_POST);
								/* @var $dataProvider CActiveDataProvider */
								$dataProvider->pagination->pageSize	 = 50;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'dynamicpricesurgelist',
									'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/dynamicpricesurge/list', $params1)),
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'filter'			 => $model,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name' => 'additional_surge', 'filter' => false, 'value' => '$data[additional_surge]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Additional Surge'),
										array('name' => 'base_capacity', 'filter' => false, 'value' => '$data[base_capacity]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Base Capacity'),
										array('name' => 'count_booking', 'filter' => false, 'value' => '$data[count_booking]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Count Booking'),
										array('name' => 'count_quotation', 'filter' => false, 'value' => '$data[count_quotation]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Count Quotation'),
										array('name' => 'Date', 'filter' => false, 'value' => '$data[Date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Date'),
										array('name' => 'forecast_act', 'filter' => false, 'value' => '$data[forecast_act]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Forecast Act'),
										array('name' => 'M_000', 'filter' => false, 'value' => '$data[M_000]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 000'),
										array('name' => 'M_010', 'filter' => false, 'value' => '$data[M_010]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 010'),
										array('name' => 'M_020', 'filter' => false, 'value' => '$data[M_020]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 020'),
										array('name' => 'M_030', 'filter' => false, 'value' => '$data[M_030]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 030'),
										array('name' => 'M_040', 'filter' => false, 'value' => '$data[M_040]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 040'),
										array('name' => 'M_050', 'filter' => false, 'value' => '$data[M_050]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 050'),
										array('name' => 'M_060', 'filter' => false, 'value' => '$data[M_060]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 060'),
										array('name' => 'M_070', 'filter' => false, 'value' => '$data[M_070]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 070'),
										array('name' => 'M_080', 'filter' => false, 'value' => '$data[M_080]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 080'),
										array('name' => 'M_090', 'filter' => false, 'value' => '$data[M_090]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 090'),
										array('name' => 'M_100', 'filter' => false, 'value' => '$data[M_100]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 100'),
										array('name' => 'M_120', 'filter' => false, 'value' => '$data[M_120]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 120'),
										array('name' => 'M_140', 'filter' => false, 'value' => '$data[M_140]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 140'),
										array('name' => 'M_170', 'filter' => false, 'value' => '$data[M_170]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 170'),
										array('name' => 'M_200', 'filter' => false, 'value' => '$data[M_200]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 200'),
										array('name' => 'M_250', 'filter' => false, 'value' => '$data[M_250]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 250'),
										array('name' => 'M_300', 'filter' => false, 'value' => '$data[M_300]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'M 300'),
										array('name' => 'total_DP', 'filter' => false, 'value' => '$data[total_DP]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total DP'),
										array('name' => 'total_SP', 'filter' => false, 'value' => '$data[total_SP]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Total SP'),
										array('name' => 'Weekday', 'filter' => false, 'value' => '$data[Weekday]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Weekday'),
										//array('name' => 'Yield', 'filter' => false, 'value' => '$data[Yield]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Yield'),
										
//										array(
//											'header'			 => 'Action',
//											'class'				 => 'CButtonColumn',
//											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => ''),
//											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
//											'template'			 => '{edit}',
//											'buttons'			 => array(
//												'edit'			 => array(
//													'url'		 => 'Yii::app()->createUrl("admin/dynamicpricesurge/edit", array(\'id\' => $data[dps_id]))',
//													'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/city/edit_booking.png',
//													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'btn btn-xs surgeedit p0', 'onclick' => 'return openModal(this, "Modify Surge")', 'title' => 'Edit'),
//												),
//												'htmlOptions'	 => array('class' => 'center'),
//											))
								)));
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function refreshApprovalList() {
        $('#dynamicpricesurgelist').yiiGridView('update');
    }
    function openModal(obj, title)
    {
        try
        {
            $href = $(obj).attr("href");
            jQuery.ajax({type: "GET", "dataType": "html", url: $href, success: function (data)
                {
                    bootbox.dialog({
                        message: data,
                        title: title,
                        size: 'large',
                        callback: function () {
                        },
                    });
                }});
        } catch (e)
        {
            alert(e);
        }
        return false;
    }

</script>