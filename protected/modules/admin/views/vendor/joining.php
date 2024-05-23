<div class="row">
	<div class=" col-xs-12 pt30">
		<div class="projects ">
			<div class="panel panel-default">
				<div class="panel-body">
					<?
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'vendorjoining-grid',
							'responsiveTable'	 => true,
							'filter'			 => $model,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
					<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
					</div></div>
					<div class='panel-body'>{items}</div>
					<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered mb0',
							'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
							//     'ajaxType' => 'POST',
							'columns'			 => array(
								array('name' => 'name', 'filter' => CHtml::activeTextField($model, 'name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('name'))), 'value' => '$data["name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Name'),
								array('name' => 'company', 'filter' => CHtml::activeTextField($model, 'company', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('company'))), 'value' => '$data["company"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Company Name'),
								array('name' => 'phone', 'filter' => CHtml::activeTextField($model, 'phone', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('phone'))), 'value' => '$data["phone"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Phone'),
								array('name' => 'email', 'filter' => CHtml::activeTextField($model, 'email', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('email'))), 'value' => '$data["email"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Email'),
								array('name' => 'city', 'filter' => CHtml::activeTextField($model, 'city', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('city'))), 'value' => '$data["city"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'City'),
								array('name' => 'created', 'filter' => CHtml::activeDateField($model, 'created', array('class' => 'form-control', 'placeholder' => 'Search by Joining Date')), 'value' => 'date("d/m/Y H:i:s",strtotime($data["created"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Joining on'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{approve}',
									'buttons'			 => array(
										'approve'		 => array(
											'click'		 => 'function(e){
					try
					{
					$href = $(this).attr("href");
					jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
					{
					vendorBox = bootbox.dialog({ 
					message: data, 
					title: "Add Vendor",
					className:"bootbox-lg",    
					callback: function(){ 
					vendorBox.hide();
					},
					});
					}}); 
					}
					catch(e)
					{ alert(e); }
					return false;
					}',
											'url'		 => 'Yii::app()->createUrl("admin/vendor/add", array("ajid" => $data["id"]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor_joining\approved.png',
											'label'		 => '<i class="fa fa-long-arrow-up"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs approve p0', 'title' => 'Approve'),
										),
										'htmlOptions'	 => array('class' => 'center'),
									))
						)));
					}
					?>


				</div>
			</div>
		</div>
	</div>
</div>