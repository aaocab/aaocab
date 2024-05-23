<style>
	.modal {
		overflow-y:auto;
	}

</style>
<div class="row">

    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'id'				 => 'contactlist',
				'filter'			 => $model,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'	 => 'Primary Contact Id',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[cmg_ctt_id];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Primary Contact Id'),
					array('name'	 => 'Duplicate Contact Id',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[dupIds];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Duplicate Contact Id'),
					array('name'	 => 'Primary Contact Name',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[primaryName];
						},
						'sortable'								 => true,
						'headerHtmlOptions'						 => array(),
						'header'								 => 'Primary Contact Name'
					),
					array('name'	 => 'Duplicate Contact Name',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[duplicateName];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Duplicate Contact Name'
					),
//					array('name'	 => 'Merged Vendor names',
//						'filter' => true,
//						'value'	 => function($data) {
//							echo $data[mergevndName];
//						},
//						'sortable'			 => true,
//						'headerHtmlOptions'	 => array(),
//						'header'			 => 'Merged Vendor names'
//					),
//					array('name'	 => 'Vendor ids',
//						'filter' => true,
//						'value'	 => function($data) {
//							//$vndCode = ($data[cmg_vnd_ids]) ? $data[cmg_vnd_ids] . " (" . $data[vnd_code] . ") " : "";
//							echo $data[mergeVndIds];
//						},
//						'sortable'			 => true,
//						'headerHtmlOptions'	 => array(),
//						'header'			 => 'Vendor ids'
//					),
//					array('name'	 => 'Merged Driver names',
//						'filter' => true,
//						'value'	 => function($data) {
//							echo $data[mergedrvName];
//						},
//						'sortable'			 => true,
//						'headerHtmlOptions'	 => array(),
//						'header'			 => 'Merged Vendor names'
//					),
//					array('name'	 => 'Driver ids',
//						'filter' => true,
//						'value'	 => function($data) {
//							//$drvCode = ($data[cmg_drv_ids]) ? $data[cmg_drv_ids] . " (" . $data[drv_code] . ")" : "";
//							echo $data[mergedrvIds];
//						},
//						'sortable'			 => true,
//						'headerHtmlOptions'	 => array(),
//						'header'			 => 'Driver ids'
//					),
//					array('name'	 => 'User ids',
//						'filter' => true,
//						'value'	 => function($data) {
//							echo $data[cmg_user_ids];
//						},
//						'sortable'			 => true,
//						'headerHtmlOptions'	 => array(),
//						'header'			 => 'User id'
//					),
//					array('name'	 => 'Agent ids',
//						'filter' => true,
//						'value'	 => function($data) {
//							echo $data[cmg_agt_ids];
//						},
//						'sortable'			 => true,
//						'headerHtmlOptions'	 => array(),
//						'header'			 => 'Agent id'
//					),
					array('name'	 => 'Pan Flag',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[cmg_pan_flag];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Pan Flag'
					),
					array('name'	 => 'Licence Flag',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[cmg_licence_flag];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Licence Flag'
					),
					array('name'	 => 'Voter Flag',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[cmg_voter_flag];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Voter Flag'
					),
					array('name'	 => 'Adhaar Flag',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[cmg_adhaar_flag];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Adhaar Flag'
					),
					array('name'	 => 'Profile Flag',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[cmg_profile_flag];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Profile Flag'
					),
//					array('name'	 => 'Added By',
//						'filter' => true,
//						'value'	 => function($data) {
//							echo $data[cmg_added_by];
//						},
//						'sortable'			 => true,
//						'headerHtmlOptions'	 => array(),
//						'header'			 => 'Added By'
//					),
					array('name'	 => 'Created At',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[cmg_created];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Created By'
					),
//					array('name'	 => 'Modified By',
//						'filter' => true,
//						'value'	 => function($data) {
//							echo $data[cmg_modified];
//						},
//						'sortable'			 => true,
//						'headerHtmlOptions'	 => array(),
//						'header'			 => 'Modified By'
//					),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{merge}',
						'buttons'			 => array(
							'merge'			 => array(
								'visible'	 => '(Yii::app()->request->getParam("ctt_id")==null) ? true : false',
								'url'		 => 'Yii::app()->createUrl("admin/contact/approvedoc", array(\'ctt_id\' => $data[cmg_ctt_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\merge.png',
								'label'		 => 'Approve doc',
								//'options'	 => array('data-toggle' => 'ajaxModal', 'id' => $data[ctt_id], 'class' => 'btn btn-xs ignoreMergeView p0', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs merge p0', 'title' => 'Merge Contact'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>





