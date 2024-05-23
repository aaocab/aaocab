<style>
    .list_booking{
        /**background: #fff;
        -webkit-box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);
        -moz-box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);
        box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);**/
        border: #e9e9e9 1px solid;
        margin: 20px 0 0 0;
    }
    .list_heading{ background: #EFEFEF; overflow: hidden;}
    .gray-color{ color: #848484;}
    .gozo_green{ background: #48b9a7;}
    .gozo_bluecolor{ color: #0766bb;}
    .gozo_greencolor{ color:#48b9a7;}
    .gozo_red{ background: #f34747;}
    .text_right{ text-align: right;}
    .margin_top{ margin-top: 40px;}
    .car_img{ overflow: hidden;}
    .car_img img{ width: 100%;}
    @media (max-width: 768px) {
        .text_right{ text-align: center;}
        .margin_top{ margin-top: 10px;}
    }
</style>
<?php
if ($orders > 0)
{
	foreach ($orders as $order)
	{
		?>
		<div class="row">
			<div class="col-xs-12">
				<div class="list_booking">
					<div class="list_heading">
						<div class="row">
							<div class="col-xs-12 col-sm-8">
								<h3 class="mt15 pl15 text-uppercase"><?php echo $order['vch_title']." - ".$order['vch_code'];?>
								</h3>
							</div>
							<div class="hidden-xs col-sm-4 col-md-4 text_right mt10 mb10"><a class="btn comm-btn mr10"  href="<?= Yii::app()->createUrl('voucher/summary', array('id' => $order['vch_id'],'hash' => $val['bkg_id'],'tinfo' => $val['bkg_id'])) ?>" title="Booking Detail" role="button"></a></div>
						</div>
					</div>
					<div class="row p15">
						<div class="col-xs-12 col-sm-8 col-md-5">
							<span class="gray-color">Order Number :</span> <?php echo $order['vor_number'];?><br/>
							<span class="gray-color">Voucher Type:</span> <?php echo Vouchers::getType($order['vch_type']);?><br/>
							<span class="gray-color">Quantity:</span> <?php echo $order['vod_vch_qty'];?><br/>
							<span class="gray-color">Price:</span> <i class="fa fa-inr"></i><?php echo $order['vch_selling_price'];?><br/>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-5">
						</div>

					</div>
					<div class="list_heading">
						<div class="row">
							<div class="col-xs-12 col-sm-4"><h5 class="mt15 pl15 text-uppercase">Status: <span class="<?= (true) ? 'gozo_greencolor' : 'red-color'; ?>"></span></h5></div>
							<div class="col-xs-12 col-sm-8 text_right mt10 mb10 pr30">Total amount: <b style="font-size: 20px; color: #48b9a7;padding-left: 5px"><i class="fa fa-inr"><?php echo $order['vod_vch_price'];?></i>
								</b></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
else
{
	?>
	<div class="row">
		<div class="col-xs-12">
			<div class="list_booking">
				<div class="list_heading text-center pt20 pb20" style="background: #f77026; color: #fff;">
					<b>Sorry!! No records found</b>
				</div>            
			</div>
		</div>
	</div>  
<? } ?>
<div class="col-xs-12 ml15 mt40 text-right">
	<?php
	$this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination));
	?>
</div>
<script>
    $(document).ready(function () {
        var front_end_height = parseInt($(window).outerHeight(true));
        var footer_height = parseInt($("#footer").outerHeight(true));
        var header_height = parseInt($("#header").outerHeight(true));
        var ch = (front_end_height - (header_height + footer_height + 23));
        $("#content").attr("style", "height:" + ch + "px;");
    });
    function canBooking(booking_id) {
        $href = "<?= Yii::app()->createUrl('booking/canbooking') ?>";
        var $booking_id = booking_id;
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"booking_id": $booking_id},
            success: function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Cancel Booking',
                    onEscape: function () {
                    },
                });
            }
        });
    }
    function viewBooking(obj) {
        var href2 = $(obj).attr("href");
        var bcode = $(obj).attr("bkgcode");

        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details for ' + bcode,
                    onEscape: function () {
                    },
                });
            }
        });
        return false;
    }
    function ratetheJourney(booking_id) {
        $href = "<?= Yii::app()->createUrl('rating/addreview') ?>";
        var $booking_id = booking_id;
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"bkg_id": $booking_id},
            success: function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Review',
                    onEscape: function () {
                    },
                });
            }
        });
    }
    function showreview(booking_id) {
        $href = "<?= Yii::app()->createUrl('rating/showreview') ?>";
        var $booking_id = booking_id;
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"bkg_id": $booking_id},
            success: function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Review',
                    onEscape: function () {
                    },
                });
            }
        });
    }
    function receipt(booking_id, hsh)
    {
        $href = "<?= Yii::app()->createUrl('booking/receipt') ?>";
        var $booking_id = booking_id;
        window.open($href + "/bkg/" + $booking_id + "/hsh/" + hsh, '_blank');
    }
    function verifyBooking(booking_id, hash) {
        $href = "<?= Yii::app()->createUrl('booking/verifybooking') ?>";
        var $booking_id = booking_id;
        var $hash = hash;
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"booking_id": $booking_id},
            success: function (data)
            {
                if (data == true) {
//                    alert('Booking verified successfully');
//                    location.reload();
                    confirmBooking($booking_id, $hash);
                } else {
                    alert('Insufficient data. Please contact our customer support.');
                }
            }
        });
    }

    function confirmBooking($booking_id, $hash) {
        var href1 = '<?= Yii::app()->createUrl('booking/confirmmobile') ?>';
        jQuery.ajax({'type': 'GET', 'url': href1,
            'data': {'bid': $booking_id, 'manual': 'manual', 'hsh': $hash},
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'medium',
                    onEscape: function () {
                    }
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    }
<? /* /?>
  function edit(booking_id) {
  $href = "<?= Yii::app()->createUrl('booking/edit') ?>";
  var $booking_id = booking_id;
  jQuery.ajax({type: 'GET',
  url: $href,
  data: {"bkg_id": $booking_id},
  success: function (data)
  {
  var box = bootbox.dialog({
  message: data,
  title: 'Edit Booking',
  onEscape: function () {
  },
  });
  }
  });
  } <?/ */ ?>
    function modify(booking_id) {
        $href = "<?= Yii::app()->createUrl('booking/editnew') ?>";
        var $booking_id = booking_id;
        jQuery.ajax({type: 'GET',
            url: $href,
            data: {"bkg_id": $booking_id},
            success: function (data)
            {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Edit Booking',
                    onEscape: function () {
                    },
                });
            }
        });
    }
</script>