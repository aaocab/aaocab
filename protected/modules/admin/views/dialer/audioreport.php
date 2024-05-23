<div class="row">
	<div class="col-xs-12 ">
		<?php
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

    function play(id)
    {
        var url = '/admpnl/dialer/downloadFile?id='+id;
        $("#play" + id).html("<audio controls><source src='" + url  + "'>Your browser does not support the audio element.</audio>");
    }
</script>