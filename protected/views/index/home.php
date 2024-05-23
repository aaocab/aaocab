<style type="text/css">
    .h3_36{ font-size: 36px !important; line-height: normal;}
    .h3_30{ font-size: 30px !important; line-height: normal;}
    .h3_18{ font-size: 18px !important; line-height: normal;}
</style>
<?php
if (isset($jsonproviderStructureMarkupData) && trim($jsonproviderStructureMarkupData) != '')
{
	?>
	<script type="application/ld+json">
	<?php echo $jsonproviderStructureMarkupData; ?>
	</script>
<?php } ?>

<?php if($flashdata = Yii::app()->user->getFlash('coin'))//code for flash a popup for ew
{
?>
	<script type="text/javascript">

		$(window).on('load',function(){
			$('#myModal3').modal('show');
		});
	</script>
<?php
	}
?>

<?php
/* @var $this Controller */
$this->newHome = true;
Logger::create("Entering Top Search View: " . Filter::getExecutionTime());

?>
<?php $imgVer        = Yii::app()->params['imageVersion']; ?>
<div class="row">
	<?php 
	#print_r($model);
	?>
    <?=$this->renderPartial('topSearch', array('model' => $model,'tripType' => $tripType), true, FALSE); ?>
</div>
<div class="row gray-bg-new hidden">
    <div class="col-lg-7 col-sm-11 col-md-9 text-center flash_banner float-none marginauto">
        <span class="h3 mt0 mb5 flash_red mt10 n">Save upto 50% on every booking*</span><br>
        <span class="h5 text-uppercase mt0 mb5 mt10">Taxi and Car rentals all over India – Gozo’s online cab booking service</span><br>
        Gozocabs is your one-stop shop for hiring chauffeur driven taxi in India for inter-city, airport transfers and even local daily tours, specializing in one-way taxi trips all over India at transparent fares. Gozo’s coverage extends across <b>2,000</b> locations in India, with over <b>20,000</b> vehicles, more than <b>75,000</b> satisfied customers and above <b>10</b> Million kms driven in a year alone. Book by web, phone or app 24x7! 
    </div>
</div>
<!--<div class="row flash_banner hide">
    <div class="col-lg-12 p0 text-center">
        <h1 class="text-uppercase mt0 mb10 flash_orange"><b>Flash Sale!</b></h1>
        <h4>We post some last minute very attractive deals for flexible travellers. Follow <a href="https://twitter.com/gozocabs" target="_blank">Twitter</a> or check <a href="https://www.facebook.com/gozocabs" target="_blank">Facebook</a> for instant deal alerts.</h4>
    </div>
</div>
<div class="row flash_banner hide" style="background: #ffc864;">
    <div class="col-lg-12 p0 hidden-sm hidden-xs text-center">     
        <figure><img src="/images/flash_lg1.jpg?v=<?= $imgVer ?>" alt="Flash Sale"></figure>		
    </div>
    <div class="col-sm-12 p0 hidden-lg hidden-md hidden-xs text-center">      
        <figure><img src="/images/flash_sm1.jpg?v=<?= $imgVer ?>" alt="Flash Sale"></figure>		
    </div>
    <div class="col-xs-12 p0 hidden-lg hidden-md hidden-sm text-center">
        <? /* /?><a target="_blank" href="https://twitter.com/gozocabs"><?/ */ ?>
        <figure><img src="/images/flash_sm1.jpg?v=<?= $imgVer ?>" alt=" Flash Sale"></figure>
        <? /* /?></a><?/ */ ?>
    </div>
	
</div> -->


		
	
<div class="row gray-bg-new">
	
    <div class="col-xs-12 col-sm-11 float-none marginauto customer-box">
        <div class="hidden-xs">
            <div class="col-xs-12 mt10 mb10">
                <div class="row mt40 n">
                    <div class="col-xs-12 text-right">
                        <a class="btn arrow-part left-arrow-part" href="#myCarouselTestimonial" role="button" data-slide="prev">
                            <span class="fa fa-angle-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="btn arrow-part right-arrow-part" href="#myCarouselTestimonial" role="button" data-slide="next">
                            <span class="fa fa-angle-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>

                    </div>
                </div>
                <div id="myCarouselTestimonial" class="carousel slide mt20 " data-ride="carousel" data-interval="false">
                    <div class="carousel-inner" role="listbox"> 
                        <?php
                        Logger::create("Executing Testimonial: " . Filter::getExecutionTime());
                        $rows          = Yii::app()->cache->get("getTopRatings");
                        if ($rows === false)
                        {
                            /* @var $modelTestimonial Ratings */
                            $rows = Ratings::model()->getTopRatings1(9, 2);
                            Yii::app()->cache->set("getTopRatings", $rows, 7200);
                        }

                        $active = "active";
                        $i      = 0;
                        foreach ($rows as $row)
                        {
                            $r        = $i % 3;
                            $toCities = $row['cities'];
                            if ($r == 0)
                            {
                                ?>
                                <div class="item <?= $active ?>">
                                    <div class="row flex">
                                    <? } ?>
                                    <div class="col-xs-12 col-md-4 mb10">
                                        <div class="panel panel-default customer-panel">
                                            <div class="panel-body">
                                                <div class="text-center mb10"><img  class="lozad" data-src="/images/commas.png?v=<?= $imgVer ?>" alt="" ></div>
                                                <div class="text-center mb20 user-review">
                                                    <?= $row['rtg_customer_review'] ?>

                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12 pull-left mr10">
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                                <div class="test-name mb20 mr15" style="float: left"><?= $row['initial'] ?></div>
                                                            </div>
                                                            <div class="col-xs-9 col-sm-8 col-md-9 pl0">
                                                                <p class="m0"><i><b>- <?= $row['user_name'] ?></b></i></p>
                                                                <p class="m0"><b><?= $toCities; ?>,</b> <i><?= Booking::model()->getBookingType($row['bkg_booking_type']); ?></i></p>
                                                                <p class="m0 block-color3"><i><b><?= date('jS M Y', strtotime($row['rtg_customer_date'])) ?></b></i></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?
                                    $i++;
                                    $active = "";
                                    if ($r == 2)
                                    {
                                        ?>
                                    </div>
                                </div>
                                <?
                            }
                        }
                        Logger::create("Testimonial Executed: " . Filter::getExecutionTime());
                        ?>
                    </div>              
                </div>
				<span class="pull-right">More reviews on <a href="<?= Yii::app()->createUrl('index/testimonial'); ?>">Gozocabs.com</a>, <a href="https://bit.ly/ReviewGozoOnGoogle" target="_blank">Google</a>, <a href="https://bit.ly/ReviewGozoOnTripAdvisor" target="_blank">TripAdvisor</a> | <a href="<?= Yii::app()->createUrl('faq'); ?>#faq53" class="text-danger" target="_blank">Do not trust MouthShut.com</a></span>
            </div>
        </div>
    </div>
</div>

	
    <div class="carousel_area mb20 hidden-xs">
        <!--<h1 class="text-center mt0">Popular Trips</h1> -->
        <?php
        Logger::create("Executing Route Data: " . Filter::getExecutionTime());
        $routeArrList         = ['delhi-jaipur',
            'delhi-shimla',
            'delhi-nainital',
            'delhi-agra',
            'chennai-tirupati',
            'jaipur-ajmer'
        ];
        $imageList            = ['delhi-jaipur'     => 'add1.jpg',
            'delhi-shimla'     => 'add2.jpg',
            'delhi-nainital'   => 'add3.jpg',
            'delhi-agra'       => 'add4.jpg',
            'chennai-tirupati' => 'add6.jpg',
            'jaipur-ajmer'     => 'add8.jpg'
        ];
        $routeDataArrList     = [];
        $routeDataArrListJSON = Yii::app()->cache->get("routeDataArrList1");
        if ($routeDataArrListJSON == false)
        {
            $routeDataArrList = Route::model()->getRouteDetailsbyNameList($routeArrList);
            Yii::app()->cache->set("routeDataArrList1", json_encode($routeDataArrList), 604800);
        }
        else
        {
            $routeDataArrList = json_decode($routeDataArrListJSON, true);
        }
        ?>
        <div id="style2b_wrapper">
            <div id="style2b" class="style2b" style="overflow:hidden">
                <div class="previous_button"></div>

                <ul>

                    <?
                    foreach ($routeDataArrList as $rtName => $rtData)
                    {
                        ?>
                        <li>
                            <div class="feature_event">
                                <div class="feature_eventimg box"><figure><img class="lozad" data-src="<?= "https://www.gozocabs.com/images/" . $imageList[$rtName] ?>?v=<?= $imgVer ?>" src="<?= "https://www.gozocabs.com/images/" . $imageList[$rtName] ?>?v=<?= $imgVer ?>" alt="<?= $rtData['fcity_name'] . ' to ' . $rtData['tcity_name'] ?>"></figure></div>
                                <div class="feature_eventtext">
                                    <div class="text-left m0 mb10 h4"><?= $rtData['fcity_name'] . ' to ' . $rtData['tcity_name'] ?><span class="pull-right small-style"><?= $rtData['rut_estm_distance'] ?> Km</span></div>

                                    <div class="row">
                                        <div class="col-xs-6 text-left text-uppercase">starting from</div>
                                        <div class="col-xs-6 text-right"><h3 class="mt0"><i class="fa fa-inr"></i><?= $rtData['baseAmount'] ?></h3></div>
                                        <div class="book-now col-xs-12"><a href="<?= "/book-taxi/" . $rtName ?>">Book Now</a></div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <?
                    }
                    Logger::create("Route Data Rendered: " . Filter::getExecutionTime());
                    ?>
                </ul>
                <div class="next_button"></div>
                <div class="clear"></div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
	<!--modal for win a day start-->
	<div id="myModal3" class="modal fade" role="dialog">
		<div class="modal-dialog">
		  <!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header p5 border-none">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body text-center mb10 user-review pt0 blue-color" style="font-weight: bold; font-size: 14px;">
				  <p><span class="h4"><b>Thank you for visiting.</b></span></p><p> Just for visiting we have given you <span style="font-size:20px;" class="orange-color "><?=$flashdata?></span> in Gozo coins that you can redeem on your next rental with Gozo.</p><p> We are now entering your name for a chance to win a free 1 day rental. There is a new winner announced every month. If you win we will be contacting you by email.</p>
				</div>
			</div>
		</div>
	</div>
	<!--modal for win a day end-->
<script>
	$(document).ready(function(){
		<?if($isFlexxi){?>
				
				 window.close();
				 window.opener.updateLogin();
		<?}
		?>
	});
</script> 

