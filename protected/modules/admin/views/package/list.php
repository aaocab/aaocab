<style>
    .search-form ul{
        list-style: none ;
        margin-bottom: 20px;
        vertical-align: bottom
    }
    .search-form ul li{
        padding: 0;
    }
    .table{
        margin-bottom: 5px;
    }
    .pagination {
        margin: 0;
    }
</style>

<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'driver-register-form', 'enableClientValidation' => FALSE,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
	),
		));
/* @var $form TbActiveForm */

$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class=" col-xs-12 ">
	<div class="col-xs-3"> 
			<label class="control-label">City</label>
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $model,
				'attribute'			 => 'from_city',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select City",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width' => '100%',
				//  'id' => 'from_city_id1'
				),
				'defaultOptions'	 => $selectizeOptions + array(
			'onInitialize'	 => "js:function(){
				  populateSourceCityPackage(this, '{$model->from_city}');
								}",
			'load'			 => "js:function(query, callback){
				loadSourceCityPackage(query, callback);
				}",
			'render'		 => "js:{
				option: function(item, escape){
				return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
				},
				option_create: function(data, escape){
				return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
				}
				}",
				),
			));
			?>
	</div>
	<div class="col-xs-3 ml10 hide">
          <label class="control-label">Zone</label>
		<?php
		$loc			 = Zones::model()->getZoneList();
		$SubgroupArray	 = CHtml::listData(Zones::model()->getZoneList(), 'zon_id', function($loc) {
					return $loc->zon_name;
				});
		$this->widget('booster.widgets.TbSelect2', array(
			'attribute'		 => 'zoneId',
			'model'			 => $model,
			'data'			 => $SubgroupArray,
			'value'			 => $model->zoneId,
			'options'		 => array('allowClear' => true),
			'htmlOptions'	 => array(
				'placeholder'	 => 'Source Zone',
				'width'			 => '100%',
				'style'			 => 'width:100%',
			),
		));
		?>
	</div>

	<div class="col-xs-2 ml10">
		<? echo $form->numberFieldGroup($model, 'pck_no_of_nights'); ?>
	</div>

	<div class="col-xs-2 ml10">
		<? echo $form->numberFieldGroup($model, 'pck_no_of_days'); ?>
	</div>

	<div class=" col-xs-12 mb20 text-center"><input type="submit" class="btn btn-info" value="SEARCH"></div>
</div>
<div class=" col-xs-12 ">
	<a class="btn btn-primary mb10" href="<?= Yii::app()->createUrl('admin/package/form') ?>" style="text-decoration: none; ">Add new</a>
	<?php
	//$accmanagername = (Admins::model()->getAdminList());
	//$dd = Admins::model()->getAdminById($data->agtAdmin->adm_fname);
	//echo "<pre>"; print_r($accmanagername);
	if (!empty($dataProvider))
	{
		$this->widget('booster.widgets.TbGridView', array(
			'id'				 => 'packageListGrid',
			'responsiveTable'	 => true,
			'filter'			 => $model,
			'dataProvider'		 => $dataProvider,
			'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
			'itemsCssClass'		 => 'table table-striped table-bordered mb0',
			'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary'),
			'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
			'columns'			 => array(
				array('name'				 => 'pck_name', 'value'				 => '$data[pck_name]', 'filter'			 => CHtml::activeTextField($model, 'pck_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('pck_name'))),
					'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-left'),
					'header'			 => 'Package Name'),
				array('name'				 => 'pck_auto_name', 'value'				 => '$data[pck_auto_name]', 'filter'			 => CHtml::activeTextField($model, 'pck_auto_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('pck_auto_name'))),
					'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-left'),
					'header'			 => 'Details'),
				array('name'				 => 'pck_desc', 'value'				 => '$data[pck_desc]', 'filter'			 => CHtml::activeTextField($model, 'pck_auto_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('pck_auto_name'))),
					'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-left'),
					'header'			 => 'Package Description'),
				array('name'				 => 'pck_no_of_days', 'value'				 => '$data[pck_no_of_days]." ".Days', 'filter'			 => FALSE,
					'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'No Of Days'),
				array('name'				 => 'pck_no_of_nights', 'value'				 => '$data[pck_no_of_nights]." ".Nights', 'filter'			 => FALSE,
					'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'No Of Nights'),
//				array('name'				 => 'pck_route', 'value'				 => '$data[route_detail]', 'filter'			 => FALSE,
//					'sortable'			 => true,
//					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
//					'htmlOptions'		 => array('class' => 'text-center'),
//					'header'			 => 'Route Detail'),
				array('name'				 => 'pck_created_by', 'value'				 => '$data[createdbyname]', 'filter'			 => FALSE,
					'sortable'			 => false,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'Created By'),
				array('name'				 => 'pck_approved_by', 'value'				 => '$data[pckapprovedby ]', 'filter'			 => FALSE,
					'sortable'			 => true,
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
					'htmlOptions'		 => array('class' => 'text-center'),
					'header'			 => 'Approved By'),
				array('name'	 => 'pck_active', 'filter' => FALSE, 'value'	 => function($data) {
						if ($data['pck_active'] == 1)
						{
							echo "Active";
						}
						else if ($data['pck_active'] == 2)
						{
							echo "Inactive";
						}
					}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Status'),
				array('name'	 => 'prt_status', 'filter' => FALSE, 'value'	 => function($data) {
						if ($data['prt_status'] == 1)
						{
							echo "Active";
						}
						else
						{
							echo "Inactive";
						}
					}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Rate Status'),
				array(
					'header'			 => 'Action',
					'class'				 => 'CButtonColumn',
					'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
					'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
					'template'			 => '{image}{editName}{editRouteDesc}{list}<br>{rate}{pending}{active}{inactive}{delete}{book}',
					'buttons'			 => array(
						'editName'		 => array(
							'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {
                                                        
                                                        var box = bootbox.dialog({ 
                                                           message: data,
                                                            title: \'Edit Package Name and Description\',
                                                            size: \'large\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
							'url'		 => 'Yii::app()->createUrl("admin/package/editdesc", array("pck_id" => $data[pck_id]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\package\edit_booking.png',
							'label'		 => '<i class="fa fa-list"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs editdesc p0', 'title' => 'Edit Package Name and Description'),
						),
						'editRouteDesc'	 => array(
							'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {
                                                        
                                                        var box = bootbox.dialog({ 
                                                           message: data,
                                                            title: \'Edit Package Route/Day Description\',
                                                            size: \'large\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
							'url'		 => 'Yii::app()->createUrl("admin/package/editroutedesc", array("pck_id" => $data[pck_id]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\package\editimg.png',
							'label'		 => '<i class="fa fa-list"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs editroutedesc p0', 'title' => 'Edit Package Route/Day Description'),
						),
						'image'			 => array(
							'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {
                                                        
                                                        var box = bootbox.dialog({ 
                                                           message: data,
                                                            title: \'Upload Image\',
                                                            size: \'large\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
							'url'		 => 'Yii::app()->createUrl("admin/package/uploadpic", array("pck_id" => $data[pck_id]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\package\uploads.png',
							'label'		 => '<i class="fa fa-list"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs upl p0', 'title' => 'Upload Package Cover and Route Image'),
						),
						'list'			 => array(
							'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {
                                                        
                                                        var box = bootbox.dialog({ 
                                                           message: data,
                                                            title: \'Package Details\',
                                                            size: \'large\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
							'url'		 => 'Yii::app()->createUrl("admin/package/showlist", array("pck_id" => $data[pck_id]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\package\show_log.png',
							'label'		 => '<i class="fa fa-list"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show List'),
						),
						'rate'			 => array('url'		 => 'Yii::app()->createUrl("admin/package/addRate", array("pck_id" => $data[pck_id]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\package\add_credits.png',
							'label'		 => '<i class="fa fa-type"></i>',
							'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Add Rate'),
						),
						'pending'		 => array(
							'click'		 => 'function(){
                                    var cons = confirm("Are you sure you want to active this package?"); 
                                        if(cons){
                                            $href = $(this).attr(\'href\');
                                            $.ajax({
                                                url: $href,
                                                dataType: "json",
                                                success: function(result){
                                                    if(result.success){
                                                        refreshPackageGrid();
                                                    }else{
                                                        
                                                    }
                                                },
                                                error: function(xhr, status, error){
                                                    
                                                }
                                            });
                                            }
                                        return false;
                                    }',
							'url'		 => 'Yii::app()->createUrl("admpnl/package/changestatus", array("pck_id" => $data[pck_id],"pck_active"=>3))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\package\pending.png',
							'visible'	 => '($data[pck_active] == 3 && Yii::app()->user->checkAccess("agentChangestatus"))',
							'label'		 => '<i class="fa fa-toggle-off"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs conPending p0', 'title' => 'Activate'),
						),
						'active'		 => array(
							'click'		 => 'function(e){
                                         var con = confirm("Are you sure you want to deactivate this package?"); 
                                          if(con){
                                            $href = $(this).attr(\'href\');
                                            $.ajax({
                                                url: $href,
                                                dataType: "json",
                                                success: function(result){
                                                    if(result.success){
                                                        refreshPackageGrid();
                                                    }else{
                                                        
                                                    }
                                                },
                                                error: function(xhr, status, error){
                                                    
                                                }
                                            });
                                            }
                                        return false;
                                    }',
							'url'		 => 'Yii::app()->createUrl("admpnl/package/block", array("pck_id" => $data[pck_id],"pck_active"=>1))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\package\active.png',
							'visible'	 => '($data[pck_active] == 1 && Yii::app()->user->checkAccess("agentChangestatus"))',
							'label'		 => '<i class="fa fa-toggle-on"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example1', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs conEnable11 p0', 'title' => 'Block')
						),
						'inactive'		 => array(
							'click'		 => 'function(){
                                    var con = confirm("Are you sure you want to active this package?"); 
                                        if(con){
                                            $href = $(this).attr(\'href\');
                                            $.ajax({
                                                url: $href,
                                                dataType: "json",
                                                success: function(result){
                                                    if(result.success){
                                                        refreshPackageGrid();
                                                    }else{
                                                        
                                                    }
                                                },
                                                error: function(xhr, status, error){
                                                    
                                                }
                                            });
                                            }
                                        return false;
                                    }',
							'url'		 => 'Yii::app()->createUrl("admpnl/package/changestatus", array("pck_id" => $data[pck_id],"pck_active"=>2))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\package\inactive.png',
							'visible'	 => '($data[pck_active] == 2 && Yii::app()->user->checkAccess("agentChangestatus"))',
							'label'		 => '<i class="fa fa-toggle-off"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs conInactive p0', 'title' => 'Activate'),
						),
						'delete'		 => array(
							'click'		 => 'function(){
                                                        var con = confirm("Are you sure you want to delete this model?");
                                                        return con;
                                                    }',
							'url'		 => 'Yii::app()->createUrl("admin/package/del", array("pck_id" => $data[pck_id]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\package\customer_cancel.png',
							'label'		 => '<i class="fa fa-remove"></i>',
							'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Delete Package'),
						),
						'book'			 => array(
							'url'		 => 'Yii::app()->createUrl("admin/booking/createnew", array("pck_id" => $data[pck_id]))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\package\car_booking.png',
							'label'		 => '<i class="fa fa-edit"></i>',
							'visible'	 => '$data[pck_active]==1',
							'options'	 => array('style' => '', 'class' => 'btn btn-xs conEdit p0', 'title' => 'Book Package'),
						),
						'htmlOptions'	 => array('class' => 'center'),
					))
		)));
	}
	?>
</div>

	<?php $this->endWidget(); ?>
<script type="text/javascript">
//    $(document).ready(function(){
//        $("#packageListGrid").click(function(){
//            location.reload(true);
//        });
//    });

    function refreshPackageGrid() {
        $('#packageListGrid').yiiGridView('update');
    }
	
	
	        $sourceList = null;
            function populateSourceCityPackage(obj, cityId) {
                obj.load(function (callback) {
                    var obj = this;
                    if ($sourceList == null) {
                        xhr = $.ajax({
                            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                            dataType: 'json',
                            data: {
                                // city: cityId
                            },
                            //  async: false,
                            success: function (results) {
                                $sourceList = results;
                                obj.enable();
                                callback($sourceList);
                                obj.setValue(cityId);
                            },
                            error: function () {
                                callback();
                            }
                        });
                    } else {
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    }
                });
            }
            function loadSourceCityPackage(query, callback) {
                //	if (!query.length) return callback();
                $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1')) ?>?apshow=1&q=' + encodeURIComponent(query),
                    type: 'GET',
                    dataType: 'json',
                    global: false,
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            }
</script>