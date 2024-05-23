<?
/* @var $bkModel Booking */
$js	 = "if($.isFunction(window.refreshVendor))
{
window.refreshVendor();
}
else
{
window.location.reload();
}
";
?>
<script>
    if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['assinvendorgrid'] != undefined) {
        $(document).off('click.yiiGridView', $.fn.yiiGridView.settings['assinvendorgrid'].updateSelector);
    }
</script>
<style type="text/css">
    .checkbox{
        margin-top: 0;margin-bottom: 0;
    }
</style>
<div id="vendor-content" class="panel-advancedoptions" >
	<div class="errorSummary alert alert-block alert-danger" style="display: none"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body panel-no-padding">
                    <div class="row">
                        <div class="col-xs-5 col-md-3"><label>Booking ID:&nbsp;</label><strong><?= $bkModel->bkg_booking_id ?></strong></div>
                        <div class="col-xs-7 col-md-4"><label>Pickup Date:&nbsp;</label><strong><?= DateTimeFormat::DateTimeToLocale($bkModel->bkg_pickup_date) ?></strong></div>
                        <div class="col-xs-12 col-md-5"><label>Route:&nbsp;</label><strong><?= $bkModel->bkgFromCity->cty_name ?> - <?= $bkModel->bkgToCity->cty_name ?></strong></div>
                    </div>
                    <div class="well p5 m0">
			<?php
			$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			    'id'			 => 'vendorform',
			    'enableClientValidation' => true,
			    'clientOptions'		 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
				'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                                $.ajax({
                                "type":"POST",
                                "dataType":"html",
                                "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                "data":form.serialize(),
                                "success":function(data1){
                               
                                        $("#vendor-content").parent().html(data1);
                                    
                                    },
                                });
                                
                                }
                        }'
			    ),
			    // Please note: When you enable ajax validation, make sure the corresponding
			    // controller action is handling ajax validation correctly.
			    // See class documentation of CActiveForm for details on this,
			    // you need to use the performAjaxValidation()-method described there.
			    'enableAjaxValidation'	 => false,
			    'errorMessageCssClass'	 => 'help-block',
			    'htmlOptions'		 => array(
				'class' => '',
			    ),
			));
			Logger::create("6");
			/* @var $form TbActiveForm */
			?>
                        <div class="row">
                            <div class="col-xs-4">
				<?= $form->textFieldGroup($model, 'vnd_name', array('label' => '', 'htmlOptions' => array('placeholder' => 'search by name'))) ?>
                            </div>
                            <div class="col-xs-4">
				<?= $form->textFieldGroup($model, 'vnd_phone', array('label' => '', 'htmlOptions' => array('placeholder' => 'search by phone'))) ?>
                            </div>

			    <?
			    if ($assignBlocked)
			    {
				?>
    			    <div class="col-xs-2">
    				<div class="row">
    				    <div class="col-xs-12">       
					    <?= $form->checkboxGroup($model, 'vndIsBlocked', ['label' => 'Blocked', 'widgetOptions' => ['htmlOptions' => []], 'inline' => true, 'groupOptions' => ['class' => 'm0']]); ?>
    				    </div>
    				    <div class="col-xs-12">
					    <?= $form->checkboxGroup($model, 'vndIsFreezed', ['label' => 'Freezed', 'widgetOptions' => ['htmlOptions' => []], 'inline' => true, 'groupOptions' => ['class' => 'm0']]); ?>
    				    </div>
    				    <div class="col-xs-12">
					    <?= $form->checkboxGroup($model, 'vndUnApproved', ['label' => 'Unapproved', 'widgetOptions' => ['htmlOptions' => []], 'inline' => true, 'groupOptions' => ['class' => 'm0']]); ?>
    				    </div> 
    				</div> 
    			    </div>

				<?
			    }
			    ?>
                            <div class="col-xs-2 pr5">
                                <button class="btn btn-primary" type="submit" name="searchVendor" id="searchVendor">Search</button>
                            </div>
                        </div>

			<?php $this->endWidget(); ?>

                    </div>
		    <?php
		    //echo "bookingId------->".$bkid."<br>";
		    //echo "bookingId------->".$bkid2;
		    if (!empty($dataProvider))
		    {
			$this->widget('booster.widgets.TbGridView', array(
			    'id'			 => 'assinvendorgrid',
			    'responsiveTable'	 => true,
			    'dataProvider'		 => $dataProvider,
			    'template'		 => "<div class='panel-heading'><div class='row m0'>
                    <div class='col-xs-12 col-sm-5 pt5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div>
                    </div></div>
                    <div class='panel-body'>{items}</div>
                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-5 p5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div></div></div>",
			    'itemsCssClass'		 => 'table table-striped table-bordered mb0',
			    'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
			    //'ajaxType' => 'POST',
			    'columns'		 => array(
				array('name'	 => 'vnd_name',
				    //'value' => '$data["vnd_name"]',
				    'value'	 => function($data) {
					$name	 = '';
					$name	 .= $data["vnd_name"];
					if ($data['vnd_is_freeze'] != 0)
					{
					    $name .= ' <span class="label label-primary">Freezed</span>';
					}
					if ($data['vnd_active'] == 2)
					{
					    $name .= ' <span class="label label-warning">Blocked</span>';
					}
					if ($data['vnd_active'] == 3)
					{
					    $name .= ' <span class="label label-danger">Unapproved</span>';
					}
//                                        if ($data['vnd_forbidden'] == 1) {
//                                            $name .= ' <span class="label label-danger">Alert</span>';
//                                        }
					echo $name;
				    }, 'sortable'		 => true, 'headerHtmlOptions'	 => array(), 'header'		 => 'Name'),
				array('name' => 'vnd_phone', 'value' => '$data["vnd_phone"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Phone'),
				//	array('name' => 'vnd_email', 'value' => '$data["vnd_email"]', 'sortable' => true, 'headerHtmlOptions' => array('style' => 'max-width:90px'), 'htmlOptions' => array('style' => 'word-break: break-all'), 'header' => 'Email'),
				array('name' => 'agt.vnd_overall_rating', 'value' => '$data["vnd_overall_rating"]', 'sortable' => true, 'headerHtmlOptions' => array('style' => 'max-width:90px'), 'htmlOptions' => array('style' => 'word-break: break-all', 'class' => 'text-center'), 'header' => 'Rating'),
				array('name'			 => 'tScore', 'value'			 => '$data["tScore"]',
				    'sortable'		 => true, 'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'text-center'), 'header'		 => 'Network Type'),
				//	array('name' => 'cScore', 'value' => '$data["cScore"]', 'sortable' => true, 'headerHtmlOptions' => array('style' => 'max-width:90px'), 'htmlOptions' => array('style' => 'word-break: break-all'), 'header' => 'Zone Score'),
				//	array('name' => 'mScore', 'value' => '$data["mScore"]', 'sortable' => true, 'headerHtmlOptions' => array('style' => 'max-width:90px'), 'htmlOptions' => array('style' => 'word-break: break-all'), 'header' => 'Miles Score'),
				array('name'			 => 'totalScore', 'value'			 => '$data["totalScore"]',
				    'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'tScore text-center'), 'header'		 => 'Total Score'),
				array('name'			 => 'bvr_bid_amount', 'value'			 => '$data["bvr_bid_amount"]',
				    'headerHtmlOptions'	 => array('style' => 'max-width:90px', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'word-break: break-all', 'class' => 'text-center'), 'header'		 => 'Bid Amount'),
				array('name'			 => 'Action', 'type'			 => 'raw', 'value'			 => 'Vendors::model()->getActionButton($data,' . $bkid . ')', 'sortable'		 => false, 'headerHtmlOptions'	 => array('style' => 'min-width:150px;'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'		 => 'Action'
				)
			)));
		    }
		    Logger::create("7");
		    ?>
		    <a href="<?= Yii::app()->createUrl("admin/cache/resetDependency", ["dep" => "VendorsFetchCount"]) ?>" onclick="return processLink(this);">Clear Cache</a>

                </div>
            </div>
        </div>
    </div>
</div><!--<?
print_r($GLOBALS['time']);
?>-->
<script>
    function processLink(obj) {
        var url = $(obj).attr("href");
        $.ajax({
            url: url,
            success: function(){},
            dataType: "json"
        });
	return false;
    }
    ;
    function valvendor(bkid) {
        $('#btn_' + bkid).removeClass('btn-info');
        $('#btn_' + bkid).addClass('btn-success');
        $('.vbtn').addClass('disabled');
    }

//
    refreshVendor = function () {
        //  box.modal('hide');
        $href = '<?= Yii::app()->createUrl('rcsr/vendor/json') ?>';
        jQuery.ajax({type: 'POST', "dataType": "json", url: $href,
            success: function (data1) {
                $data = data1;
                $('#<?= CHtml::activeId($model, "bcb_driver_id") ?>').select2({data: $data, multiple: false});
            }
        });
    };
   
<?php
$loadingPic = CHtml::image(Booster::getBooster()->getAssetsUrl() . '/img/loading.gif');
?>
    function assignCab(obj) {
        $href = $(obj).attr('href');
        var titlestr = 'Add Driver Details';
        jQuery.ajax({type: 'GET',
            url: $href,
            success: function (data)
            {
                cabBox = bootbox.dialog({
                    message: data,
                    title: titlestr,
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                cabBox.on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });

            }
        });
        return false;
    }

    $('#assinvendorgrid .tScore a1').click(function (e) {
        e.preventDefault();
        return showReturnDetails(this);
    });
    function showReturnDetails(obj, type) {
        var span = 8;
        var that = $(obj);
        var status = that.data('status');
        var rowid = that.attr('vendorid');
        var tr = $('#relatedinfo_' + type + '_' + rowid);
        var parent = that.parents('tr').eq(0);

        if (status && status == 'on') {
            return;
        }
        that.data('status', 'on');
        if (tr.length && !tr.is(':visible'))
        {
            tr.slideDown();
            that.data('status', 'off');
            return false;
        } else if (tr.length && tr.is(':visible'))
        {
            tr.slideUp();
            that.data('status', 'off');
            return false;
        }

        if (tr.length)
        {
            tr.find('td').html('<?= $loadingPic ?>');
            if (!tr.is(':visible')) {
                tr.slideDown();
            }
        } else
        {
            var td = $('<td/>').html('<?= $loadingPic ?>').attr({'colspan': span});
            tr = $('<tr/>').prop({'id': 'relatedinfo_' + type + '_' + rowid}).append(td);
            /* we need to maintain zebra styles :) */
            var fake = $('<tr class="hide"/>').append($('<td/>').attr({'colspan': span}));
            parent.after(tr);
            tr.after(fake);
        }
//	var data = $.extend({$data}, {id:rowid});
        $href = that.attr('href');
        $.ajax({
            url: $href,
            success: function (data) {
                tr.find('td').html(data);
                that.data('status', 'off');
            },
            error: function ()
            {
                tr.find('td').html('{$this->ajaxErrorMessage}');
                that.data('status', 'off');
            }
        });

        return false;
    }
 </script>
