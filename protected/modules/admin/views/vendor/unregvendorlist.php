<script>
    //$(document).ready(function () {
    //    $('#Vendors_vnd_agreement_date').datepicker({
    //       format: 'dd/mm/yyyy'
    //  });
    //});
</script>
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
								array('name' => 'uvr_vnd_name', 'filter' => CHtml::activeTextField($model, 'uvr_vnd_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('uvr_vnd_name'))), 'value' => '$data["uvr_vnd_name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'First Name'),
								array('name' => 'uvr_vnd_lname', 'filter' => CHtml::activeTextField($model, 'uvr_vnd_lname', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('uvr_vnd_lname'))), 'value' => '$data["uvr_vnd_lname"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Last Name'),
								array('name' => 'uvr_vnd_phone', 'filter' => CHtml::activeTextField($model, 'uvr_vnd_phone', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('uvr_vnd_phone'))), 'value' => '$data["uvr_vnd_phone"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Phone'),
								array('name' => 'uvr_vnd_email', 'filter' => CHtml::activeTextField($model, 'uvr_vnd_email', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('uvr_vnd_email'))), 'value' => '$data["uvr_vnd_email"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Email'),
								array('name' => 'uvr_vnd_city_id', 'filter' => CHtml::activeTextField($model, 'uvr_vnd_city_id', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('uvr_vnd_city_id'))), 'value' => '$data["cty_name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'City'),
								//array('name' => 'uvr_modified_date', 'value' => 'date("d/m/Y H:iA",strtotime($data["uvr_modified_date"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Biding Date'),
								array('name' => 'uvr_modified_date', 'filter' => CHtml::activeDateField($model, 'uvr_modified_date', array('class' => 'form-control', 'placeholder' => 'Search by Biding Date')), 'value' => 'date("d/m/Y H:iA",strtotime($data["uvr_modified_date"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Biding Date'),
								
								array('name' => 'uvr_vnd_is_driver', 'filter' => CHtml::activeCheckBoxList($model, 'uvr_vnd_is_driver', array('0' => 'No', '1' => 'Yes'), array('placeholder' => 'Search by ' . $model->getAttributeLabel('uvr_vnd_is_driver'))), 
									'value'	 => function($data) {
										$dco = $data["uvr_vnd_is_driver"];
										if ($dco == 0)
										{
											$dco = "No";
										}
										else
										{
											$dco = "Yes";
										}
										return $dco;
									},
									'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'DCO'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{approve}{delete}',
									'buttons'			 => array(
										'approve'		 => array(
//                                            'click' => 'function(e){
//                                                try
//                                                {
//                                                    $href = $(this).attr("href");
//                                                    jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
//                                                    {
//                                                        vendorBox = bootbox.dialog({ 
//                                                        message: data, 
//                                                        title: "Edit Vendor",
//                                                        className:"bootbox-lg",    
//                                                        callback: function(){ 
//                                                        vendorBox.hide();
//                                                        },
//                                                        });
//                                                    }}); 
//                                                    }
//                                                    catch(e)
//                                                    { alert(e); }
//                                                    return false;
//                                                 }',
											'url'		 => 'Yii::app()->createUrl("admin/vendor/add", array("newvendor" => $data["uvr_id"],"type"=>unreg))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-check"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-warning approve', 'title' => 'Approve'),
										),
										'delete'		 => array(
											'click'		 => 'function(){
					var con = confirm("Are you sure you to delete this vendor?");
					return con;
					}',
											'url'		 => 'Yii::app()->createUrl("admin/vendor/uvrdelete", array("uvr_id" => $data["uvr_id"],"status"=>0))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-remove"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger delete', 'title' => 'Delete'),
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
