<div class="row">
	<div class="col-xs-12 ">
		<?php
		$checkExportAccess = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			?>
			<?= CHtml::beginForm(Yii::app()->createUrl('report/scq/Audioreport'), "post", ['style' => "margin-bottom: 10px;"]); ?>
			<input type="hidden" id="export1" name="export1" value="true"/>
			<input type="hidden" id="cst_id" name="cst_id" value="<?= $model->cst_id ?>"/>
			<input type="hidden" id="cst_lead_id" name="cst_lead_id" value="<?= $model->cst_lead_id ?>"/>
			<input type="hidden" id="cst_phone_code" name="cst_phone_code" value="<?= $model->cst_phone_code ?>"/>
			<input type="hidden" id="cst_phone" name="cst_phone" value="<?= $model->cst_phone ?>"/>
			<input type="hidden" id="cst_did" name="cst_did" value="<?= $model->cst_did ?>"/>
			<input type="hidden" id="cst_agent_name" name="cst_agent_name" value="<?= $model->cst_agent_name ?>"/>
			<input type="hidden" id="cst_recording_file_name" name="cst_recording_file_name" value="<?= $model->cst_recording_file_name ?>"/>
			<input type="hidden" id="cst_status" name="cst_status" value="<?= $model->cst_status ?>"/>
			<input type="hidden" id="cst_created" name="cst_created" value="<?= $model->cst_created ?>"/>
			<input type="hidden" id="cst_modified" name="cst_modified" value="<?= $model->cst_modified ?>"/>
			<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
			<?php
			echo CHtml::endForm();
		}

		if (!empty($dataProvider))
		{
			$arr = [];
			if (is_array($dataProvider->getPagination()->params))
			{
				$arr = $dataProvider->getPagination()->params;
			}
			$params1			 = $arr + array_filter($_GET + $_POST);
			$this->widget('booster.widgets.TbGridView', array(
				'id'				 => 'call-grid',
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
					array('name'				 => 'cst_id', 'value'				 => '$data[cst_id]',
						'filter'			 => CHtml::activeTextField($model, 'cst_id', array('class' => 'form-control')),
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'CST ID'),
					array('name'				 => 'cst_lead_id', 'value'				 => '$data[cst_lead_id]',
						'filter'			 => CHtml::activeTextField($model, 'cst_lead_id', array('class' => 'form-control')),
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Lead ID'),
					array('name'				 => 'cst_phone_code', 'value'				 => '$data[cst_phone_code]',
						'filter'			 => CHtml::activeTextField($model, 'cst_phone_code', array('class' => 'form-control')),
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Phone Code'),
					array('name'				 => 'cst_phone', 'value'				 => '$data[cst_phone]',
						'filter'			 => CHtml::activeTextField($model, 'cst_phone', array('class' => 'form-control')),
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Phone No.'),
					array('name'	 => 'cst_did', 'value'	 => function ($data) {
							echo $data['cst_did'] . "min";
						},
						'filter'			 => CHtml::activeTextField($model, 'cst_did', array('class' => 'form-control')),
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'DID'),
					array('name'				 => 'cst_agent_name', 'value'				 => '$data[cst_agent_name]',
						'filter'			 => CHtml::activeTextField($model, 'cst_agent_name', array('class' => 'form-control')),
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Agent Name'),
					array('name'	 => 'cst_recording_file_name',
						'value'	 => function ($data) {
							if ($data['cst_recording_file_name'] != '')
							{
								$basePath	 = yii::app()->basePath;
								$file		 = $basePath . DIRECTORY_SEPARATOR . 'doc' . DIRECTORY_SEPARATOR . $data['cst_recording_file_name'];
								echo "<span id='play" . $data['cst_call_id'] . "'>" . CHtml::link("PLAY", "javascript:void(0)", ["onclick" => "return play('{$data['cst_call_id']}')"]) . "</span>";
							}
						},
						'filter'			 => CHtml::activeTextField($model, 'cst_recording_file_name', array('class' => 'form-control')),
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Recording File'),
					array('name'	 => 'cst_status', //'value' => '$data[cst_status]', 
						'value'	 => function ($data) {
							$arrv = CallStatus::callstatus;
							echo $arrv[$data['cst_status']];
						},
						'filter'			 => CHtml::activeDropDownList($model, 'cst_status', $callStatus, array('class' => 'form-control')),
						'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Status'),
					array('name'				 => 'cst_created', 'value'				 => '$data[cst_created]',
						'filter'			 => CHtml::activeTextField($model, 'cst_created', array('class' => 'form-control')),
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Created at'),
					array('name'				 => 'cst_modified', 'value'				 => '$data[cst_modified]',
						'filter'			 => CHtml::activeTextField($model, 'cst_modified', array('class' => 'form-control')),
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Last Modified'),
			)));
		}
		?>
	</div>
</div>
<script type="text/javascript">
    $(document).on('change', ':input', function (e) {
        $("#cst_id").val($("#CallStatus_cst_id").val());
        $("#cst_lead_id").val($("#CallStatus_cst_lead_id").val());
        $("#cst_phone_code").val($("#CallStatus_cst_phone_code").val());
        $("#cst_phone").val($("#CallStatus_cst_phone").val());
        $("#cst_agent_name").val($("#CallStatus_cst_agent_name").val());
        $("#cst_recording_file_name").val($("#CallStatus_cst_recording_file_name").val());
        $("#cst_status").val($("#CallStatus_cst_status").val());
        $("#cst_created").val($("#CallStatus_cst_created").val());
        $("#cst_modified").val($("#CallStatus_cst_modified").val());
    });

    function play(id)
    {
        var url = '/admpnl/dialer/downloadFile?id=' + id;
        $("#play" + id).html("<audio controls><source src='" + url + "'>Your browser does not support the audio element.</audio>");
    }
</script>