<?php
if (count($models) > 0) 
{
    foreach ($models as $order) 
	{
        ?>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body p15">
						<div class="row">
<div class="col-12">
								<h3 class="font-18 text-uppercase weight600"><?php echo $order['vch_title']; ?></h3>
							</div>
							<div class="col-12 col-lg-12">
								<div class="row">
		                            <div class="col-12 col-lg-12 mb10"><span class="color-gray">Description:</span> <?php echo $order['vch_desc']; ?></div>
									<div class="col-12 col-lg-4 mb10"><span class="color-gray">Order Number:</span> <b><?php echo $order['vor_number']; ?></b></div>
									<div class="col-6 col-lg-4 mb10"><span class="color-gray">Quantity:</span> <?php echo $order['vod_vch_qty']; ?></div>
									<div class="col-6 col-lg-4 mb10 text-right"><span class="color-gray">Price:</span> &#x20B9;<b><?php echo $order['vch_selling_price']; ?></b></div>
									<div class="col-12 col-lg-4 mb10"><span class="color-gray">Purchase Date:</span> <?php echo date('d/m/Y', strtotime($order['vor_date'])) . ', ' . date('h:i A', strtotime($order['vor_date'])); ?> </div>
		                        </div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-5 col-lg-4 pr0 pt5">Status:  <span class="<?= ($order['vor_active'] == 1) ? 'color-green2' : 'color-red'; ?>"><b><?php echo VoucherOrder::getStatus($order['vor_active']); ?></b></span></div>
							<div class="col-7 col-lg-8 pl0 text-right"><span class="badge badge-pill badge-success pl10 pr10">Total amount: <span class="font-16">&#x20B9;<b><?php echo round($order['vod_vch_price']); ?></b></span></span></div>
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
		<div class="col-12 col-lg-6 offset-lg-3">
			<div class="card">
				<div class="card-body text-center">
					Sorry!! No records found
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<div>
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