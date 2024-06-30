<style>
    body{ font-family: 'Arial'; font-size: 12px; line-height: 20px;}
    p{ font-size: 13px;}
    .tb_cfont p{font-size: 16px;}
    .main-div{ margin: auto; font-size: 12px!important;}
    .main-div ul{ padding-left: 20px;}
    @media (max-width: 767px) {
        .main-div{ margin: auto; font-size: 12px!important;}
    }
    .padding-xs { padding: 5px; }
    .padding-sm { padding: 5px; }
    .padding-md { padding: 5px; }
    .padding-lg { padding: 5px; 

                  .margin-xs { padding: 5px; }
                  .margin-sm { padding: 5px; }
                  .margin-md { padding: 5px; }
                  .margin-lg { padding: 5px; }

    </style>
    <div class="main-div">
		<?php
		$baseURL = Yii::app()->params['fullBaseURL'];
		/* @var $model Booking */
		$bkg_id	 = $model->bkg_id;

		$url = $baseURL . "/aaohome/vehicle/assureCabInfo?bkg_id=$bkg_id&show=1";
		if ($show == 1)
		{
			?>
			<div class="row" >
				<div class="col-xs-12 col-sm-12 col-sm-6 col-lg-12 col-md-12">
					<p><b><i>Standard for Cabs</i></b></p>
					<ul>
						<li>Inventory to be offered at a car brand level ( and not cab category level) : For example , Toyota Innova(and not SUV)</li>
						<li>All models for cabs to be less than 3 years old (Only 2015,, 2016 and 2017 cab models)</li>
						<li>Cabs needs to have : 1) Audio System , 2) AUX cable, 3) Clean Interior, 4)Dent/rust free Exteriors, 5) USB charging, 6)Air freshener</li>
					</ul>
					<p><b><i>Standard for Drivers</i></b></p>
					<ul>
						<li>All drivers need to : 1) Wear clean clothes (preferably uniform-no jeans, round necks), 2) Not argue with customers (call supply partner for any issues during trip), 3) Be polite, 4) Don't talk on phone while driving, 5) No rash driving or over speeding, 6) Don't ask money from the customer.</li>
					</ul>
					<p><b><i>On-Time</i></b></p>
					<ul>
						<li>Always on time (driver should plan on reaching customer 30 min prior to departure time)</li>
					</ul>
				</div>
			</div>
			<?php
		}
		else
		{
			?>
			<div class="row" >
				<div class="col-xs-12 col-sm-12 col-sm-6 col-lg-12 col-md-12">
					<a href="<?= $url; ?>" style="text-decoration: none; font-size: 14px; color: #000000;">Please note this is an ASSURED Booking. Any deviation from ASSURED Terms & Conditions will attract 100% Penalty Charges.</a>
				</div>
				<div class="col-xs-12 col-sm-12 col-sm-6 col-lg-12 col-md-12" style="margin:5px;">
					<a href="<?= $url; ?>" style="text-decoration: none; font-size: 14px; color: #000000;">कृपया ध्यान दें यह एक ASSURED बुकिंग है। घोषित ASSURED बुकिंग के नियमों और शर्तों की अवहेलना 100%  Penalty आकर्षित करेगा । </a>
				</div>
				<div class="col-xs-12 col-sm-12 col-sm-6 col-lg-12 col-md-12" style="margin:5px;">
					<a href="<?= $url; ?>" style="font-size: 14px; color: #003bb3;"><b>ASSURED Terms & Conditions.</b></a>
				</div>
			</div>
			<?php
		}
		?>
    </div>