<style>
    .full-width {
        width: 300px
    }
    @media (min-width: 1523px) and (max-width: 1636px) {
        .full-width {
            width: 270px !important;
        }
        @media (min-width: 1388px) and (max-width: 1524px) {
            .full-width {
                width: 250px !important;
            }
            @media (min-width: 768px) and (max-width: 1387px) {
                .full-width {
                    width: 248px !important;
                }
            }
            @media (min-width: 320px) and (max-width: 767px) {
                .full-width {
                    width: 220px !important;
                }
            }
		}
	}
</style>
<?php
/* @var $model BookingRoute */
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true, 'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id', 'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
if ($sourceCity == "")
{
//	$cityList	 = Cities::model()->getJSONServiceCity();
//	$pcityList	 = $cityList;
}
else
{

	$model->pcd_from_city = $sourceCity;
//	$cmodel					 = Cities::model()->getDetails($sourceCity);
//	$sourceCityName			 = $cmodel->cty_name . ', ' . $cmodel->ctyState->stt_name;
//	// $pcityList            = Cities::model()->getJSONNearestAll($previousCity);
//	$pcityList				 = Cities::model()->getJSONServiceCity();
}
if ($model->pcd_from_city != '')
{
	// $cityList = Cities::model()->getJSONNearestAll($model->pcd_from_city);
//	$cityList = Cities::model()->getJSONServiceCity();
}
$rcitiesDiv	 = '';
$rtimeDiv	 = "  col-md-4";
if ($btype == 2)
{
	$rcitiesDiv	 = "  col-md-offset-2";
	$rtimeDiv	 = "  col-md-12";
}
$counter = 0;
$ctr	 = rand(0, 99) . date('mdhis');
?>

<div class="row clsRoute">
	<div class="col-xs-12 col-sm-12 float-none marginauto pb0">
		<div class="panel panel-default panel-border box-shadow1 gray-new-bg">
			<div class="panel-body pb0 pt0">
				<div class="row">
					<div class="col-xs-12   pr5">
						<div class="row">
							<div class="col-xs-6 col-sm-2   ">
								<div class="row">
									<div class="col-xs-3 pl0 ">
										<label class="control-label" for="PackageDetails_pcd_sequence">Sl#</label>
										<input type="text" class="form-control sequence" placeholder="" name="PackageDetails[<?= $ctr ?>][pcd_sequence]" id="PackageDetails_pcd_sequence_<?= $ctr ?>"  value="1" readonly="readonly" >
									</div>
									<div class="col-xs-3 pl0 pr0">
										<label class="control-label" for="PackageDetails_pcd_day_serial">DayNo</label>
										<input type="text" class="form-control noday" placeholder="" name="PackageDetails[<?= $ctr ?>][pcd_day_serial]" id="PackageDetails_pcd_day_serial_<?= $ctr ?>"  value="1" readonly="readonly" >
									</div>
									<div class="col-xs-5 pr0">

										<label class="control-label " for="PackageDetails_pcd_night_serial">Night</label>
										<input type="text" class="form-control nonight" placeholder="#Night" name="PackageDetails[<?= $ctr ?>][pcd_night_serial]" id="PackageDetails_pcd_night_serial_<?= $ctr ?>" value="" >

									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-3  col-lg-2">
								<label class="control-label " id='trslabel'>From City</label>
								<div class="form-group  cityinput">


									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => '[' . $ctr . ']pcd_from_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select Source",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('id' => 'pcd_from_city_' . $ctr, 'class' => 'form-control ctyPickup ctySelect2 pt0 pr0 pl0', 'data-key' => $counter),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                       populateSourceCity(this, '{$model->pcd_from_city}');                            
                                    }",
									'load'			 => "js:function(query, callback){
														loadSourceCity(query, callback);
                                    }",
									'onChange'		 => "js:function(value) {
														changeDestination(value, \$dest_city, '{$model->pcd_to_city}');
														updateDistance('{$ctr}');
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
									<?= CHtml::hiddenField('min_time[]', $minTime, array('id' => 'min_time0')) ?>

								</div> 
							</div>

							<? /* /?>
							  <div class="col-xs-12 col-sm-4 pl0 hide">
							  <label class="control-label" for="PackageDetails_pcd_from_location">From Location</label>

							  <textarea class="form-control" placeholder="From Location" name="PackageDetails[<?= $ctr ?>][pcd_from_location]" id="PackageDetails_pcd_from_location_<?= $ctr ?>"></textarea>

							  </div><?/ */ ?>

							<div class="col-xs-12 col-sm-3 col-lg-2">
								<div class="form-group cityinput">
									<label class="control-label " id='trdlabel'>To City</label>
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => '[' . $ctr . ']pcd_to_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select Destination",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('id' => 'pcd_to_city_' . $ctr, 'class' => 'form-control ctyDrop ctySelect2   pt0 pr0 pl0', 'data-key' => $counter),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                                \$dest_city=this; }",
									'load'			 => "js:function(query, callback){
                                                                    loadSourceCity(query, callback);
                                                            }",
									'onChange'		 => "js:function(value) {
                                                                    changeLocation(value,$ctr);
																		updateDistance('{$ctr}');
                                                            }",
									'render'		 => "js:{
                                                 option: function(item, escape){                      
                                                         return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
                                                 },
                                                 option_create: function(data, escape){
                                                      return '<div>' +'<span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(data.text) + '</span></div>';
                                                }
                                            }",
										),
									));
									?>
								</div>
							</div>
							<? /* /?>
							  <div class="col-xs-12 col-sm-3  pl0">
							  <div class="input-group">
							  <label class="control-label" >To Location</label>
							  <textarea class="form-control" placeholder="To Location" name="PackageDetails[<?= $ctr ?>][pcd_to_location]" id="PackageDetails_pcd_to_location_<?= $ctr ?>"></textarea>
							  </div>
							  </div><?/ */ ?>
							<div class="col-xs-12 col-sm-1    ">
								<div class="  ml15    n">
									<label class="control-label" >Duration</label>
									<input type="number" class="form-control duration" placeholder="Duration" name="PackageDetails[<?= $ctr ?>][pcd_trip_duration]" id="PackageDetails_pcd_trip_duration_<?= $ctr ?>">
								</div>
							</div>
							<div class="col-xs-12 col-sm-1    ">
								<div class="  ml15 mr15  n">
									<label class="control-label" >Distance</label>
									<input type="number" class="form-control distance" placeholder="Distance" name="PackageDetails[<?= $ctr ?>][pcd_trip_distance]" id="PackageDetails_pcd_trip_distance_<?= $ctr ?>">
								</div>
							</div>

							<div class="col-xs-12 col-sm-3">

								<label class="control-label" >Route/Day Description</label>
								<textarea class="form-control" placeholder="Enter Route/Day Description" name="PackageDetails[<?= $ctr ?>][pcd_description]" id="PackageDetails_pcd_description_<?= $ctr ?>"></textarea>

							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>


<script>
	$sourceList = null;
	$loadCityId = 0;

</script>