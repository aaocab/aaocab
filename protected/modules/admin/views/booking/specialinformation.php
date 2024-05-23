<style>
	.text-color-red{
		color: red !important;
	}

	.main-tab2{
		min-height: 133px !important;
	}
</style>
<?
$tabclass	 = ($minheight) ? "main-tab2-$minheight" : 'main-tab2';
?>
<div class="<?= $tabclass ?>">
    <div class="col-xs-12 col-sm-6 p0">
        <div class="<?= $tabclass ?> main-tab3">
			<div class="row p5 new-tab5">
                <div class="col-xs-6"><b>No of Large Bag:</b></div>
                <div class="col-xs-6 text-right"><?= $model->bkgAddInfo->bkg_num_large_bag ?></div>
            </div>
			<div class="row p5 new-tab5">
                <div class="col-xs-6"><b>No of Person:</b></div>
                <div class="col-xs-6 text-right"><?= $model->bkgAddInfo->bkg_no_person ?></div>
            </div>
			<div class="row p5 new-tab5">
                <div class="col-xs-6"><b>Senior Citizen Traveling:</b></div>
                <div class="col-xs-6 text-right"><?= ($model->bkgAddInfo->bkg_spl_req_senior_citizen_trvl > 0)? "Yes" : "-" ?></div>
            </div>
			<div class="row p5 new-tab5">
				<div class="col-xs-6"><b>Carrier Required:</b></div>
				<div class="col-xs-6 text-right"><?= ($model->bkgAddInfo->bkg_spl_req_carrier  > 0)? "Yes" : "-"  ?></div>
			</div>
			<div class="row p5 new-tab5">
				<div class="col-xs-6"><b>English Speaking Driver:</b></div>
				<div class="col-xs-6 text-right"><?= ($model->bkgAddInfo->bkg_spl_req_driver_english_speaking > 0)? "Yes" : "-"  ?></div>
			</div>
			<div class="row p5 new-tab5">
				<div class="col-xs-6"><b>Others:</b></div>
				<div class="col-xs-6 text-right"><?= ($model->bkgAddInfo->bkg_spl_req_other != '')? $model->bkgAddInfo->bkg_spl_req_other : "-"  ?></div>
			</div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 p0">
        <div class="<?= $tabclass ?> main-tab3">
			<div class="row p5 new-tab5">
                <div class="col-xs-6"><b>No of Small Bag:</b></div>
                <div class="col-xs-6 text-right"><?= $model->bkgAddInfo->bkg_num_small_bag ?></div>
            </div>
			<div class="row p5 new-tab5">
				<div class="col-xs-6"><b>Flight Number: </b></div>
				<div class="col-xs-6 text-right"><?= ($model->bkgAddInfo->bkg_flight_no  != "")? $model->bkgAddInfo->bkg_flight_no : "-"  ?></div>
			</div>
			
            <div class="row p5 new-tab5">
				<div class="col-xs-6"><b>Women Traveling: </b></div>
				<div class="col-xs-6 text-right"><?= ($model->bkgAddInfo->bkg_spl_req_woman_trvl  > 0)? "Yes" : "-"  ?></div>
			</div>
			<div class="row p5 new-tab5">
				<div class="col-xs-6"><b>Kids on Board:</b></div>
				<div class="col-xs-6 text-right"><?= ($model->bkgAddInfo->bkg_spl_req_kids_trvl > 0)? "Yes" : "-"  ?></div>
			</div>
			<div class="row p5 new-tab5">
				<div class="col-xs-6"><b>Journey Break:</b></div>
				<div class="col-xs-6 text-right"><?= ($model->bkgAddInfo->bkg_spl_req_lunch_break_time != '')? $model->bkgAddInfo->bkg_spl_req_lunch_break_time : "-"  ?></div>
			</div>
			<div class="row p5 new-tab5">-</div>
        </div>
    </div>
</div>