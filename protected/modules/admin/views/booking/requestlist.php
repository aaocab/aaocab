<?
/* @var $bkgmodel Booking */
$js		 = "if($.isFunction(window.refreshVendor))
{
window.refreshVendor();
}
else
{
window.location.reload();
}
";
?>
<?php
/* @var $bkgmodel Booking */
?>
<style type="text/css">
    .checkbox{
        margin-top: 0;margin-bottom: 0;
    }
</style>
<div id="unregvendor-content" class="panel-advancedoptions" >
	<div class="errorSummary alert alert-block alert-danger" style="display: none"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body panel-no-padding">
					<div class="row">
                        <div class="col-xs-5 col-md-3"><label>Booking ID:&nbsp;</label><strong><?= $bkgmodel->bkg_booking_id ?></strong></div>
                        <div class="col-xs-7 col-md-3"><label>Pickup Date:&nbsp;</label><strong><?= DateTimeFormat::DateTimeToLocale($bkgmodel->bkg_pickup_date) ?></strong></div>
                        <div class="col-xs-12 col-md-3"><label>Route:&nbsp;</label><strong><?= $bkgmodel->bkgFromCity->cty_name ?> - <?= $bkgmodel->bkgToCity->cty_name ?></strong></div>
						<div class="col-xs-12 col-md-3"><label>Cab Type Requested:&nbsp;</label><strong><?= SvcClassVhcCat::getVctSvcList("string", 0, $vehicleType->vct_id ); ?></strong></div>
					</div>
					<?php
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'requestVendorGrid',
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-5 pt5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body'>{items}</div>
							<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-5 p5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									//'ajaxType' => 'POST',
									'columns'			 => array(
										array('name' => 'uvr_vnd_name', 'value' => '$data["uvr_vnd_name"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Name'),
										//	array('name' => 'vnd_email', 'value' => '$data["vnd_email"]', 'sortable' => true, 'headerHtmlOptions' => array('style' => 'max-width:90px'), 'htmlOptions' => array('style' => 'word-break: break-all'), 'header' => 'Email'),
										array('name' => 'uvr_vnd_phone', 'value' => '$data["uvr_vnd_phone"]', 'sortable' => true, 'headerHtmlOptions' => array('style' => 'max-width:90px'), 'htmlOptions' => array('style' => 'word-break: break-all', 'class' => 'text-center'), 'header' => 'Phone No'),
										array('name'				 => 'uvr_vnd_address', 'value'				 => '$data["uvr_vnd_address"]',
											'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'tScore text-center'), 'header'			 => 'Address'),
										array('name'				 => 'cty_name', 'value'				 => '$data["cty_name"]',
											'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'text-center'), 'header'			 => 'City'),
										array('name'				 => 'uvr_bid_amount', 'value'				 => '$data["buv_bid_amount"]',
											'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'text-center'), 'header'			 => 'Bid Amount'),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{accept}',
											'buttons'			 => array(
												'accept' => array(
													'url'		 => 'Yii::app()->createUrl("admin/booking/unregvndaccept", array("buv_id" => $data["buv_id"]))',
													'imageUrl'	 => false,
													//'visible'	 => true,
													'label'		 => '<i class="fa fa-check"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'target' => '_blank', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-warning approve', 'title' => 'View'),
												),
												'htmlOptions' => array('class' => 'center'),
									)),
						)));
					}
					Logger::create("7");
					?>

				</div>	
            </div>  

        </div>  
    </div>
</div>