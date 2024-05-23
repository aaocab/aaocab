<div class="footer footer-new pl0 pr0 pt10">
	<div class="footer-text footer-link">
		<ul>
			<li><a href="#">Home</a></li>
			<li><a href="#">Blog</a></li>
			<li><a href="#">About Us</a></li>
			<li><a href="#">FAQS</a></li>
			<li><a href="#">Contact Us</a></li>
			<li><a href="#">Careers</a></li>
			<li><a href="#">Terms and Conditions</a></li>
			<li><a href="#">Disclaimer</a></li>
			<li><a href="#">Privacy Policy</a></li>
			<li><a href="#">Sitemap</a></li>
			<li><a href="#">One Way Cabs</a></li>
			<li><a href="#">Ask Us To Be Official Partner</a></li>
			<li><a href="#">Business Travel</a></li>
			<li><a href="#">For Startups</a></li>
			<li><a href="#">Your Travel Desk</a></li>
			<li><a href="#">Join Our Agent Network</a></li>
			<li><a href="#">Brand Partners</a></li>
			<li><a href="#">Price Guarantee</a></li>
			<li><a href="#">Why GozoCabs</a></li>
			<li><a href="#">News Room</a></li>
		</ul>
	</div>
	<div class="clear"></div>
	<div class="footer-socials mt15">
		<a href="#" class="scale-hover icon icon-round no-border icon-xs bg-facebook border-teal-3d"><i class="fab fa-facebook-f"></i></a>
		<a href="#" class="scale-hover icon icon-round no-border icon-xs bg-twitter"><i class="fab fa-twitter"></i></a>
		<a href="#" class="scale-hover icon icon-round no-border icon-xs bg-instagram"><i class="fab fa-instagram"></i></a>
		<a href="#" class="scale-hover icon icon-round no-border icon-xs back-to-top bg-blue-dark"><i class="fa fa-angle-up font-16"></i></a>
	</div>
	<p class="footer-copyright mt0">© 2019 Gozo Technologies Pvt. Ltd. All Rights Reserved.</p>
</div>

<!--<a href="#" class="back-to-top-badge back-to-top-small bg-highlight"><i class="fa fa-angle-up"></i>Back to Top</a>
<div id="menu-share" data-height="420" class="menu-box menu-bottom">
	<div class="menu-title">
		<span class="color-highlight">Just tap to share</span>
		<h1>Sharing is Caring</h1>
		<a href="#" class="menu-hide"><i class="fa fa-times"></i></a>
	</div>
	<div class="sheet-share-list">
		<a href="#" class="shareToFacebook"><i class="fab fa-facebook-f bg-facebook"></i><span>Facebook</span><i class="fa fa-angle-right"></i></a>
		<a href="#" class="shareToTwitter"><i class="fab fa-twitter bg-twitter"></i><span>Twitter</span><i class="fa fa-angle-right"></i></a>
		<a href="#" class="shareToLinkedIn"><i class="fab fa-linkedin-in bg-linkedin"></i><span>LinkedIn</span><i class="fa fa-angle-right"></i></a>
		<a href="#" class="shareToGooglePlus"><i class="fab fa-google-plus-g bg-google"></i><span>Google +</span><i class="fa fa-angle-right"></i></a>
		<a href="#" class="shareToPinterest"><i class="fab fa-pinterest-p bg-pinterest"></i><span>Pinterest</span><i class="fa fa-angle-right"></i></a>
		<a href="#" class="shareToWhatsApp"><i class="fab fa-whatsapp bg-whatsapp"></i><span>WhatsApp</span><i class="fa fa-angle-right"></i></a>
		<a href="#" class="shareToMail no-border bottom-5"><i class="fas fa-envelope bg-mail"></i><span>Email</span><i class="fa fa-angle-right"></i></a>
	</div>
</div>-->


<!--<script type="text/javascript" src="<?= ASSETS_URL ?>js/mobile/jquery.js"></script>-->

<script type="text/javascript" src="<?= ASSETS_URL ?>js/mobile/plugins.js"></script>
<script type="text/javascript" src="<?= ASSETS_URL ?>js/mobile/custom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.8.1/jquery.timepicker.min.js"></script>


<script>
    $fromCity = '<?= $datacity ?>';
    var toCity = [];
    var toCity1 = [];
    var toCity2 = [];
    var toCity4 = [];
    var airportList = [];
    var trlocList = [];
    $destCity = null;

    $(document).ready(function ()
    {
        $("#bkg_pickup_date_time1").selectize();
        $("#bkg_pickup_date_time2").selectize();
        $("#bkg_pickup_date_time3").selectize();
        $("#bkg_pickup_date_time4").selectize();
        $("#bkg_pickup_date_time5").selectize();
        $('#brt_pickup_date_time_11').timepicker();

        $('.date_PickSet').datepicker({
            changeMonth: true,
            format: 'dd/mm/yyyy',
            minDate: 0
        });



    });
    $sourceList = null;

    function loadSource(query, callback) {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }
    function loadAirportSource(query, callback) {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }
    function loadTime(query, callback) {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/timedrop')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }

    function populateSource(obj, cityId) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylist1')) ?>',
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue('<?= $model->bkg_from_city_id ?>');
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue('<?= $model->bkg_from_city_id ?>');
            }
        });
    }

    function populatePackage(obj, pckid) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/package')) ?>',
                    dataType: 'json',
                    data: {
                        pckid: pckid
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue('<?= $model->bkg_package_id ?>');
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue('<?= $model->bkg_package_id ?>');
            }
        });
    }

    function loadPackage(query, callback) {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/package')) ?>?q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }

    function populateAirportList(obj, cityId) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportcities')) ?>',
                    dataType: 'json',
                    data: {
                        city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue('<?= $model->bkgAirport ?>');
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue('<?= $model->bkgAirport ?>');
            }
        });
    }
    function changeDestination(value, obj) {
        if (!value.length)
            return;
        var existingValue = obj.getValue();
        if (existingValue == '')
        {
            existingValue = '<?= $model->bkg_to_city_id ?>';
        }
        obj.disable();
        obj.clearOptions();
        obj.load(function (callback) {
            //  xhr && xhr.abort();
            xhr = $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist')) ?>/source/' + value,
                dataType: 'json',
                success: function (results)
                {
                    obj.enable();
                    callback(results);
                    obj.setValue(existingValue);
                },
                error: function () {
                    callback();
                }
            });
        });
    }

    function changeTrDestination(value, obj) {
        if (!value.length)
            return;
        var existingValue = obj.getValue();
        if (existingValue == '')
        {
            existingValue = '<?= $model->bkgTransferLoc ?>';
        }
        obj.disable();
        obj.clearOptions();
        obj.load(function (callback) {
            //  xhr && xhr.abort();
            xhr = $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('index/getairportnearest')) ?>/source/' + value,
                dataType: 'json',
                success: function (results)
                {
                    obj.enable();
                    callback(results);
                    obj.setValue(existingValue);
                },
                error: function () {
                    callback();
                }
            });
        });
    }

    $('.bkg_transfer_type_2').click(function () {
        var result = $(this).val();
        $('.rad_chk1').val(result);
        if (result == 1) {
            $(".dlabel").text('Destination Location');
        } else {
            $(".dlabel").text('Pickup Location');
        }
    });

    function validateTransfer() {
        var trType = 0;
        trType = $('.bkg_transfer_type_2').val();
        var trType = $('.rad_chk1').val();
        var strCityErr = '';
        if (trType == 0) {
            alert('error');
        }
        if (trType == 1) {
            if ($('#BookingTemp_bkgAirport').val() == '') {
                strCityErr += "Pickup Airport must be selected before proceed.";
            }
            if ($('#BookingTemp_bkgTransferLoc').val() == '') {
                strCityErr += "\nDestination location must be selected before proceed.";
            }
        }
        if (trType == 2) {
            if ($('#BookingTemp_bkgAirport').val() == '') {
                strCityErr += "Drop off Airport must be selected before proceed.";
            }
            if ($('#BookingTemp_bkgTransferLoc').val() == '') {
                strCityErr += "\nPickup location must be selected before proceed.";
            }
        }
        if (strCityErr != '') {
            alert(strCityErr);
            return false;
        } else {
            return true;
        }
    }

    $('#btnTransfer').click(function (event) {
        if (!validateTransfer()) {
            event.preventDefault();
        }
    });
</script>
<script type="text/javascript">

    $('#Booking_bkg_return_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });

    $('#BookingTemp_bkg_pickup_date_date').datepicker({
        format: 'dd/mm/yyyy'
    });


</script>
