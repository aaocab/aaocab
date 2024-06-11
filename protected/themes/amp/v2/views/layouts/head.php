<!DOCTYPE html>
<html amp>
	<head>
		<title><?= $this->pageTitle ?></title>
		<meta name="google-site-verification" content="5JEqiMjViFVWGKtv22A7eplvB9LBgQIUVpEZQfHtGFo" />
		<meta charset="utf-8" />
		<?php
		$isLoggedin = !(Yii::app()->user->isGuest);
		$canonicalUrl = (($this->ampPageEnabled == 1) ? str_replace('amp/', '', Yii::app()->request->url) : Yii::app()->request->url);
		?>
		<link rel="canonical" href="<?php echo Yii::app()->createAbsoluteUrl($canonicalUrl); ?>" />
		<?php
		$this->widget("application.widgets.SeoHead", [
			'defaultKeywords'	 => "outstation taxi,oneway, outstation-taxi-india, shared outstation, Car Rental, inter city taxi service, Car Hire, Taxi Service, Cab Service, Cab Hire, Taxi Hire ,Cab Rental, Taxi Booking, Rent A Car, Car Rental India, Online Cab Booking, Taxi Cab , Car Rental Service, Online Taxi Booking, Local Taxi Service, Cheap Car Rental , Car Rental, Car Hire Services, Car Rentals India, Taxi Booking India, Cab Booking India Car For Hire, Taxi Services, Online Car Rentals , Book A Taxi , Book A Cab, Car Rentals Agency India, Car Rent In India, India Rental Cars, India Cabs, Rent Car In India, Car Rental India, India Car Rental, Rent A Car India, Car Rental In India, Rent A Car In India, India Car Rental Company, Corporate Car Rental India, Car Rental Company In India",
			'defaultDescription' => "India's Largest Intercity Car Rentals | Hire Outstation taxi and Airport Transfers cabs Online | Call +91-9051877000 | Book an outstation taxi in Delhi, Bangalore, Chennai, Chandigarh, Pune, Mumbai, Darjeeling, Gangtok & 400 cities of India at affordable rates with transparent billing and 24 X 7 customer support"
		]);
		?>
		<style amp-boilerplate>body{
				-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;
				-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;
				-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;
				animation:-amp-start 8s steps(1,end) 0s 1 normal both
			}
			@-webkit-keyframes -amp-start{
				from{
					visibility:hidden
				}
				to{
					visibility:visible
				}
			}
			@-moz-keyframes -amp-start{
				from{
					visibility:hidden
				}
				to{
					visibility:visible
				}
			}
			@-ms-keyframes -amp-start{
				from{
					visibility:hidden
				}
				to{
					visibility:visible
				}
			}
			@-o-keyframes -amp-start{
				from{
					visibility:hidden
				}
				to{
					visibility:visible
				}
			}
			@keyframes -amp-start{
				from{
					visibility:hidden
				}
				to{
					visibility:visible
				}
			}</style><noscript><style amp-boilerplate>body{
				-webkit-animation:none;
				-moz-animation:none;
				-ms-animation:none;
				animation:none
				}</style></noscript>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
		<script async src="https://cdn.ampproject.org/v0.js"></script>
		<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
		<script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
		<script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-latest.js"></script>
		<script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
		<script async custom-element="amp-selector" src="https://cdn.ampproject.org/v0/amp-selector-0.1.js"></script>
		<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>

		<!-- Google Tag Manager -->
		<style amp-custom>
			body{
				font-family:'Roboto', sans-serif;
				font-size:14px;
				line-height:22px;
				padding:0;
				margin:0;
				color: #475F7B;
			}
			a{
				color:#1a73e8;
				text-decoration: none;
			}
			p{
				margin: 0 0 8px 0;
			}
			h1{
				font-size:20px;
				line-height:normal;
				margin: 0 0 5px 0;
			}
			h2{
				font-size:16px;
				line-height:normal;
				margin: 0 0 5px 0;
			}
			h3{
				font-size:16px;
				line-height:normal;
				margin: 0 0 5px 0;
			}
			h4{
				font-size:16px;
				line-height:normal;
				margin: 0 0 5px 0;
			}
			.font20{
				font-size:20px;
				line-height:normal
			}
			.font18{
				font-size:18px;
				line-height:normal
			}
			.font16{
				font-size:16px;
				line-height:normal
			}
			.font14{
				font-size:14px;
				line-height:normal
			}
			.font12{
				font-size:12px;
				line-height:normal
			}
			.font11{
				font-size:11px;
				line-height:normal
			}
			.text-style1{
			}
			.text-center{
				text-align: center;
			}
			.gray-color{
				color: #989898;
			}
			.orange-color{
				color: #ff6801;
			}
			.white-color{
				color: #fff;
			}
			.btn-border-green{
				color: #00a388;
				border: #00a388 1px solid;
				padding: 2px 10px;
				-webkit-border-radius: 2px;
				-moz-border-radius: 2px;
				border-radius: 2px;
			}
			.page-content {
				padding-left: 15px;
				padding-right: 15px;
				overflow: hidden;
			}
			.text-center{
				text-align: center;
			}
			.text-left{
				text-align: left;
			}
			.text-right{
				text-align: right;
			}
			.wrraper{
				width:96%;
				margin: 0 auto;
				clear: both;
				overflow: hidden;
			}
			.top-right-menu{
				background: #f8f8f8;
				border-bottom: #efefef 1px solid;
				color: #475F7B;
				text-align: center;
				display:block;
				padding:5px 15px
			}
			.top-right-menu a{
				color: #475F7B;
				padding: 0 10px;
			}
			.footer-bg{
				background:#193651;
				color:#fff;
				padding:15px
			}
			.footer-link{
				text-align:center
			}
			.footer-link a{
				color:#fff;
				text-decoration:none;
				padding:1px 10px;
				text-align:center;
				text-transform:uppercase;
				display:inline-block
			}
			table{
				width:100%;
				border-collapse:collapse
			}
			tr:nth-of-type(odd){
				background:#f9f9f9
			}
			th{
				background:#fff;
				color:#475F7B;
				font-weight:700
			}
			td,th{
				padding:6px;
				border:1px solid #ccc;
				text-align:left
			}
			.row-amp{
				margin-left:15px;
				margin-right:15px
			}
			.logo-panel{
				padding:7px 15px;
				display:block;
				margin:0 0 0;
				border-bottom:#efefef 1px solid
			}
			.logo-panel img{
				width:100px
			}
			.next-btn{
				background:#48b9a7;
				text-transform:uppercase;
				font-size:18px;
				font-weight:700;
				border:none;
				padding:7px 30px;
				color:#fff;
				-webkit-border-radius:2px;
				-moz-border-radius:2px;
				border-radius:2px;
				transition:all .5s ease-in-out;
				display:inline-block;
				text-decoration:none
			}
			.main_time{
				width:41%;
				padding: 2%;
				font-size: 11px;
				float:left;
				border:1px solid #dcdcdc;
				margin:1% 1%;
				text-align:center;
				min-height:100px;
				line-height: 14px;
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
				border-radius: 4px;
			}
			.main_time a{
				color:#1a73e8;
				text-align:center;
				text-decoration:none
			}
			.main_time a:hover{
				color:#0b0b0b
			}
			.car_box2 img{
				width:100%
			}
			.card-view{
				border: #e8e8e8 1px solid;
				width: 90%;
				margin: 15px auto 15px auto;
				position: relative;
				padding: 10px;
				overflow: hidden;
				-webkit-border-radius: 3px;
				-moz-border-radius: 3px;
				border-radius: 3px;
			}
			.card-view-left{
				width: 67%;
				float: left;
				color: #475F7B;
				line-height: 16px;
			}
			.card-view-right{
				width: 33%;
				float: left;
				text-align: right;
			}
			.card-view-mid{
				width: 100%;
				float: left;
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
				border-radius: 4px;
				border: #e2e2e2 1px solid;
				height: 100%;
				margin:20px 0 5px 0;
			}
			.card-view-mid ul{
				list-style-type: none;
				padding: 0;
				margin: 0;
				width: 100%;
			}
			.card-view-mid li{
				padding: 0 3%;
				text-align: center;
				display: inline-block;
				border-right: #dcdcdc 1px solid;
				color: #878787;
				width: 18%;
				float: left;
				height: 100%;
				font-size: 11px;
				line-height: 15px;
			}
			.card-view-mid li:last-child{
				border-right:none;
			}
			.card-view-mid li span{
				font-size: 24px;
				font-weight: bold;
				line-height: normal;
				color: #475F7B;
				line-height: 38px;
			}

			.card-view-mid2{
				width: 100%;
				float: left;
				margin:25px 0 5px 0;
				text-align: center;
			}

			.flex{
				display: -webkit-box;
				display: -webkit-flex;
				display: -ms-flexbox;
				display: flex;
				flex-wrap: wrap;
			}
			.card-text1{
				font-size: 14px;
				text-transform: uppercase;
				margin-bottom: 10px;
			}
			.card-text2{
				color: #475F7B;
				text-transform: uppercase;
				font-size: 20px;
				font-weight: 500;
				line-height: 24px;
			}
			.note-panel{
				display: block;
				float: left;
				color: #b6c6d9;
				width: 100%;
				padding-top: 5px;
			}
			.btn-book{
			}
			.btn-book a{
				background: #1c4fa2;
				color: #fff;
				font-weight: bold;
				font-size: 14px;
				text-transform: uppercase;
				padding: 8px 20px;
				text-decoration: none;
				line-height: normal;
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
				border-radius: 4px;
			}
			.btn-book a:hover{
				background: #11336a;
				color: #fff;
			}
			.title-panel{
				color: #475F7B;
				font-weight: bold;
				text-transform: uppercase;
				font-size: 16px;
			}
			.title-panel2{
				background: #ff2929 url(/images/title-bg-amp.png) center right no-repeat;
				position: absolute;
				top: 8px;
				left: 0;
				z-index: 1;
				color: #fff;
				font-weight: bold;
				padding: 6px 25px 3px 10px;
				text-transform: uppercase;
				border-left: #ffa200 8px solid;
				font-size: 16px;
			}

			.btn-2 a{
				position: absolute;
				bottom: 20px;
				right: 0;
				z-index: 1;
				color: #fff;
				font-weight: bold;
				padding: 6px 10px 5px 10px;
				text-transform: uppercase;
				font-size: 13px;
				background: rgba(249,110,198,1);
				-webkit-border-radius: 100px 0 0 100px;
				-moz-border-radius: 100px 0 0 100px;
				border-radius: 100px 0 0 100px;
				background: -moz-linear-gradient(45deg, rgba(249,110,198,1) 0%, rgba(127,114,242,1) 100%);
				background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(249,110,198,1)), color-stop(100%, rgba(127,114,242,1)));
				background: -webkit-linear-gradient(45deg, rgba(249,110,198,1) 0%, rgba(127,114,242,1) 100%);
				background: -o-linear-gradient(45deg, rgba(249,110,198,1) 0%, rgba(127,114,242,1) 100%);
				background: -ms-linear-gradient(45deg, rgba(249,110,198,1) 0%, rgba(127,114,242,1) 100%);
				background: linear-gradient(45deg, rgba(249,110,198,1) 0%, rgba(127,114,242,1) 100%);
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f96ec6', endColorstr='#7f72f2', GradientType=1 );
				-webkit-box-shadow: 0px 5px 5px 0px rgba(0,0,0,0.12);
				-moz-box-shadow: 0px 5px 5px 0px rgba(0,0,0,0.12);
				box-shadow: 0px 5px 5px 0px rgba(0,0,0,0.12);
			}


			.side-bar{
				list-style-type:none;
				margin:0;
				padding:0;
				position:absolute;
				right:0;
				top:12px;
				z-index: 2;
			}
			.side-bar ul{
				list-style-type:none;
				margin:0;
				padding:0;
				position:relative;
				right:0;
				width:160px
			}
			.side-bar li{
				display:inline-block;
				float:left;
				margin-right:1px;
				width:160px
			}
			.side-bar li a{
				display:block;
				min-width:100px;
				height:40px;
				text-align:center;
				line-height:50px;
				font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;
				color:#fff;
				background:#fff;
				text-decoration:none
			}
			.side-bar li:hover a{
				background:#f36c31
			}
			.side-bar li:hover ul a{
				background:#f3f3f3;
				color:#2f3036;
				height:40px;
				line-height:40px
			}
			.side-bar li:hover ul a:hover{
				background:#19c589;
				color:#fff
			}
			.side-bar li ul{
				display:none
			}
			.side-bar li ul li{
				display:block;
				float:none
			}
			.side-bar li ul li a{
				width:auto;
				min-width:100px;
				padding:0 20px;
				text-align:left
			}
			.side-bar ul li a:hover + .hidden,.hidden:hover{
				display:block
			}
			.show-menu{
				font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;
				text-decoration:none;
				color:#fff;
				background:#19c589;
				text-align:center;
				padding:10px 0;
				display:none
			}
			input[type=checkbox]{
				display:none
			}
			input[type=checkbox]:checked ~ #menu{
				display:block
			}

			.inner-top-mune{
				background: #1a4ea2;
				padding: 15px;
				color: #fff;
			}
			.newline{
				color: #475F7B;
				padding: 15px;
				position: relative;
				background: #fff;
			}
			.newline a{
				color: #475F7B;
			}
			.accordion-style h4{
				border: #e2e2e2 1px solid;
				width: 88%;
				margin: 5px auto 0 auto;
				position: relative;
				padding: 15px;
				overflow: hidden;
				color: #475F7B;
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
				border-radius: 4px;
				font-weight: 500;
			}
			.accordion-style a{
				color: #fff;
			}
			.accordion-style2{
				border: #e2e2e2 1px solid;
				width: 88%;
				margin: 1px auto 10px auto;
				position: relative;
				padding: 15px;
				overflow: hidden;
				color: #475F7B;
				-webkit-border-radius: 4px;
				-moz-border-radius: 4px;
				border-radius: 4px;
				background: #fff;
			}
			.table-view{
				width: 100%;
				float: left;
				border-bottom: #e5e5e5 1px dashed;
			}
			.table-view-left{
				width: 55%;
				float: left;
				color: #475F7B;
				padding: 10px 0;
				height: 100%;
			}
			.table-view-right{
				width: 45%;
				float: left;
				color: #475F7B;
				text-align: right;
				padding: 10px 0;
				height: 100%;
				font-weight: bold;
				font-size: 15px;
			}
			.accordion-style2 div:last-child{
				border-bottom: none;
			}
			.full-width-img{
				width: 100%;
				margin: 0 auto;
				text-align: center;
			}
			.thumb-box{
				float: left;
				margin-bottom: 25px;
				width: 50%;
				text-align: center;
				line-height: 16px;
				color: #8c8c8c;
			}
			.thumb-box h4{
				color: #000;
			}

			.card-hedding .h4{
				text-align: center;
				background: #00a388;
				-webkit-border-radius: 100px;
				-moz-border-radius: 100px;
				border-radius: 100px;
				margin: 0 0 10px 0;
				color: #fff;
				padding: 8px 0;
			}
			.card-hedding h1{
				color: #000;
			}
			.left-card{
				width: 50%;
				float: left;
				text-align: center;
			}
			.left-card, .right-card{
				color: #636363;
			}
			.right-card{
				width: 50%;
				float: left;
				text-align: right;
				text-align: center;
			}
			.edit-box{
				position: absolute;
				right: 0;
				bottom: 20px;
			}
			.edit-box a{
				padding: 10px 15px;
				display: block;
			}

			/*********Start Tab Panels **********/
			:root {
				--color-primary: #005AF0;
				--space-1: .5rem;  /* 8px */
				--space-4: 2rem;   /* 32px */
			}

			/* Styles for the flex layout based tabs */
			amp-selector[role=tablist].tabs-with-flex {
				display: flex;
				flex-wrap: wrap;
				width: 92%;
				margin: 0 auto;
			}
			amp-selector[role=tablist].tabs-with-flex [role=tab] {
				flex-grow: 1;
				/* custom styling, feel free to change */
				text-align: center;
				padding: var(--space-1);
				text-transform: uppercase;
				font-size: 11px;
				margin-top: -32px;
				margin-left: 6px;
				margin-right: 6px;
				color: #0d4da7;
				font-weight: 500;
			}
			.tap-contens{height: 44px; overflow: hidden; background: #EEF3FA; border: #ccd9eb 1px solid; border-radius: 100px; width: 100%; margin-bottom: -10px;}
			.tabs-with-flex a {
				text-decoration: none;
			}
			amp-selector[role=tablist].tabs-with-flex [role=tab][selected] {
				outline: none;
				/* custom styling, feel free to change */
				background: #5A8DEE;
				color :#fff ;
				border-radius: 100px;
				margin-bottom: 20px;
			}
			amp-selector[role=tablist].tabs-with-flex [role=tabpanel] {
				display: none;
				width: 100%;
				order: 1; /* must be greater than the order of the tab buttons to flex to the next line */
				/* custom styling, feel free to change */
				padding:0 0;
			}
			amp-selector[role=tablist].tabs-with-flex [role=tab][selected] + [role=tabpanel] {
				display: block;
			}

			/* Styles for the selector based tabs */
			amp-selector[role=tablist].tabs-with-selector {
				display: flex;
			}
			amp-selector[role=tablist].tabs-with-selector [role=tab][selected] {
				outline: none;
				/* custom styling, feel free to change */
				border-bottom: 2px solid var(--color-primary);
			}
			amp-selector[role=tablist].tabs-with-selector {
				display: flex;
			}
			amp-selector[role=tablist].tabs-with-selector [role=tab] {
				/* custom styling, feel free to change */
				width: 100%;
				text-align: center;
				padding: var(--space-1);
			}
			amp-selector.tabpanels [role=tabpanel] {
				display: none;
				/* custom styling, feel free to change */
				padding: var(--space-4);
			}
			amp-selector.tabpanels [role=tabpanel][selected] {
				outline: none;
				display: block;
			}
			.bgactive{
				background: #00a388;
				color :#fff ;
			}
			.bgnormal{
				background: #e9eff5;
				color :#6c6c6c ;
			}
			.bgnormal a{
				background: #e9eff5;
				color :#6c6c6c ;

			}
			/*    .tab-sub-menu{
					width:100%;
					margin-top: 10px;
					display: inline-block;
					text-align: center;
					font-size: 11px;
					
				}*/
			.tab-sub-menu{
				font-size: 11px;
				text-align: center;
				width:calc(48% - 5px);
				margin-top: 10px;
				border-bottom: 2px solid #efefef;
				color: #475F7B;
				padding:6px 4px;
				display: inline-block;
				border-radius: 3px;
			}
			.tab-sub-menu:hover{
				background: #efefef;
			}
			.tab-sub-menu.active{
				background: #efefef;
				outline: none;
				/* custom styling, feel free to change */
				border-bottom: #1c4fa2 2px solid;
			}
			/********* End Tab Panels **********/

			/********* Start Right Side Bar **********/
			.menu-icon{
				position: absolute;
				top: 15px;
				right: 10px;
				background: #fff;
				border: none;
				padding: 0;
				cursor: pointer;
			}
			.sample-sidebar{
				width: 50%;
				padding: 15px;
				background: #fff;
			}
			.btn-close{
				background: #fff;
				color: #475F7B;
				border: none;
				position: absolute;
				z-index: 2;
				top: 10px;
				right: 10px;
				cursor: pointer;
				width: 30px;
				height: 30px;
			}
			.amp-sidebar-toolbar-target-hidden ul{
				padding: 0;
				margin: 20px 0 0 0;
				list-style-type: none;
			}
			.amp-sidebar-toolbar-target-hidden li{
				color: #000;
				border-bottom: #f1f1f1 1px solid;
			}
			.amp-sidebar-toolbar-target-hidden li:last-child{
				border-bottom: none;
			}
			.amp-sidebar-toolbar-target-hidden li a{
				color: #475F7B;
				display: block;
				text-decoration: none;
				padding: 12px 0;
				font-size: 13px;
			}
			.amp-sidebar-toolbar-target-hidden li a:hover{
				color: #000;
			}
			/*********End Right Side Bar **********/

			/*@media only screen and (max-width: 760px),(min-device-width: 768px) and (max-device-width: 1024px) {
			table,thead,tbody,th,td,tr{display:block}
			thead tr{position:absolute;top:-9999px;left:-9999px}
			tr{border:1px solid #ccc}
			td{border:none;border-bottom:1px solid #eee;position:relative;padding-left:50%}
			td:before{position:absolute;top:6px;left:6px;width:45%;padding-right:10px;white-space:nowrap}
			td:nth-of-type(1):before{content:"Vehicle Type"}
			td:nth-of-type(2):before{content:"Model Type"}
			td:nth-of-type(3):before{content:"Passenger Capacity"}
			td:nth-of-type(4):before{content:"Luggage Capacity"}
			td:nth-of-type(5):before{content:"Rate/km"}
			td:nth-of-type(6):before{content:"Fare"}
			}*/
			.m0,.m-n{
				margin:0
			}
			.m5,.m-xs{
				margin:5px
			}
			.m5.n,.m-xs.n{
				margin:-5px
			}
			.m10,.m-sm{
				margin:10px
			}
			.m10.n,.m-sm.n{
				margin:-10px
			}
			.m15,.m{
				margin:15px
			}
			.m15.n,.m.n{
				margin:-15px
			}
			.m20,.m-md{
				margin:20px
			}
			.m20.n,.m-md.n{
				margin:-20px
			}
			.m30,.m-lg{
				margin:30px
			}
			.m30.n,.m-lg.n{
				margin:-30px
			}
			.m40,.m-xl{
				margin:40px
			}
			.m40.n,.m-xl.n{
				margin:-40px
			}
			.m50,.m-xxl{
				margin:50px
			}
			.m50.n,.m-xxl.n{
				margin:-50px
			}
			.mb0,.mb-n{
				margin-bottom:0
			}
			.mb5,.mb-xs{
				margin-bottom:5px
			}
			.mb5.n,.mb-xs.n{
				margin-bottom:-5px
			}
			.mb10,.mb-sm{
				margin-bottom:10px
			}
			.mb10.n,.mb-sm.n{
				margin-bottom:-10px
			}
			.mb15,.mb{
				margin-bottom:15px
			}
			.mb15.n,.mb.n{
				margin-bottom:-15px
			}
			.mb20,.mb-md{
				margin-bottom:20px
			}
			.mb20.n,.mb-md.n{
				margin-bottom:-20px
			}
			.mb30,.mb-lg{
				margin-bottom:30px
			}
			.mb30.n,.mb-lg.n{
				margin-bottom:-30px
			}
			.mb40,.mb-xl{
				margin-bottom:40px
			}
			.mb40.n,.mb-xl.n{
				margin-bottom:-40px
			}
			.mb50,.mb-xxl{
				margin-bottom:50px
			}
			.mb50.n,.mb-xxl.n{
				margin-bottom:-50px
			}
			.ml0,.ml-n{
				margin-left:0
			}
			.ml5,.ml-xs{
				margin-left:5px
			}
			.ml5.n,.ml-xs.n{
				margin-left:-5px
			}
			.ml10,.ml-sm{
				margin-left:10px
			}
			.ml10.n,.ml-sm.n{
				margin-left:-10px
			}
			.ml15,.ml{
				margin-left:15px
			}
			.ml15.n,.ml.n{
				margin-left:-15px
			}
			.ml20,.ml-md{
				margin-left:20px
			}
			.ml20.n,.ml-md.n{
				margin-left:-20px
			}
			.ml30,.ml-lg{
				margin-left:30px
			}
			.ml30.n,.ml-lg.n{
				margin-left:-30px
			}
			.ml40,.ml-xl{
				margin-left:40px
			}
			.ml40.n,.ml-xl.n{
				margin-left:-40px
			}
			.ml50,.ml-xxl{
				margin-left:50px
			}
			.ml50.n,.ml-xxl.n{
				margin-left:-50px
			}
			.mr0,.mr-n{
				margin-right:0
			}
			.mr5,.mr-xs{
				margin-right:5px
			}
			.mr5.n,.mr-xs.n{
				margin-right:-5px
			}
			.mr10,.mr-sm{
				margin-right:10px
			}
			.mr10.n,.mr-sm.n{
				margin-right:-10px
			}
			.mr15,.mr{
				margin-right:15px
			}
			.mr15.n,.mr.n{
				margin-right:-15px
			}
			.mr20,.mr-md{
				margin-right:20px
			}
			.mr20.n,.mr-md.n{
				margin-right:-20px
			}
			.mr30,.mr-lg{
				margin-right:30px
			}
			.mr30.n,.mr-lg.n{
				margin-right:-30px
			}
			.mr40,.mr-xl{
				margin-right:40px
			}
			.mr40.n,.mr-xl.n{
				margin-right:-40px
			}
			.mr50,.mr-xxl{
				margin-right:50px
			}
			.mr50.n,.mr-xxl.n{
				margin-right:-50px
			}
			.mt0,.mt-n{
				margin-top:0
			}
			.mt5,.mt-xs{
				margin-top:5px
			}
			.mt5.n,.mt-xs.n{
				margin-top:-5px
			}
			.mt10,.mt-sm{
				margin-top:10px
			}
			.mt10.n,.mt-sm.n{
				margin-top:-10px
			}
			.mt15,.mt{
				margin-top:15px
			}
			.mt15.n,.mt.n{
				margin-top:-15px
			}
			.mt20,.mt-md{
				margin-top:20px
			}
			.mt20.n,.mt-md.n{
				margin-top:-20px
			}
			.mt30,.mt-lg{
				margin-top:30px
			}
			.mt30.n,.mt-lg.n{
				margin-top:-30px
			}
			.mt40,.mt-xl{
				margin-top:40px
			}
			.mt40.n,.mt-xl.n{
				margin-top:-40px
			}
			.mt50,.mt-xxl{
				margin-top:50px
			}
			.mt50.n,.mt-xxl.n{
				margin-top:-50px
			}
			.p0,.p-n{
				padding:0
			}
			.p5,.p-xs{
				padding:5px
			}
			.p10,.p-sm{
				padding:10px
			}
			.p15,.p{
				padding:15px
			}
			.p20,.p-md{
				padding:20px
			}
			.p30,.p-lg{
				padding:30px
			}
			.p40,.p-xl{
				padding:40px
			}
			.p50,.p-xxl{
				padding:50px
			}
			.pb0,.pb-n{
				padding-bottom:0
			}
			.pb5,.pb-xs{
				padding-bottom:5px
			}
			.pb10,.pb-sm{
				padding-bottom:10px
			}
			.pb15,.pb{
				padding-bottom:15px
			}
			.pb20,.pb-md{
				padding-bottom:20px
			}
			.pb30,.pb-lg{
				padding-bottom:30px
			}
			.pb40,.pb-xl{
				padding-bottom:40px
			}
			.pb50,.pb-xxl{
				padding-bottom:50px
			}
			.pl0,.pl-n{
				padding-left:0
			}
			.pl5,.pl-xs{
				padding-left:5px
			}
			.pl10,.pl-sm{
				padding-left:10px
			}
			.pl15,.pl{
				padding-left:15px
			}
			.pl20,.pl-md{
				padding-left:20px
			}
			.pl30,.pl-lg{
				padding-left:30px
			}
			.pl40,.pl-xl{
				padding-left:40px
			}
			.pl50,.pl-xxl{
				padding-left:50px
			}
			.pr0,.pr-n{
				padding-right:0
			}
			.pr5,.pr-xs{
				padding-right:5px
			}
			.pr10,.pr-sm{
				padding-right:10px
			}
			.pr15,.pr{
				padding-right:15px
			}
			.pr20,.pr-md{
				padding-right:20px
			}
			.pr30,.pr-lg{
				padding-right:30px
			}
			.pr40,.pr-xl{
				padding-right:40px
			}
			.pr50,.pr-xxl{
				padding-right:50px
			}
			.pt0,.pt-n{
				padding-top:0
			}
			.pt5,.pt-xs{
				padding-top:5px
			}
			.pt10,.pt-sm{
				padding-top:10px
			}
			.pt15,.pt{
				padding-top:15px
			}
			.pt20,.pt-md{
				padding-top:20px
			}
			.pt30,.pt-lg{
				padding-top:30px
			}
			.pt40,.pt-xl{
				padding-top:40px
			}
			.pt50,.pt-xxl{
				padding-top:50px
			}
			.tab-style a{    padding: 12px 3px 12px 3px; text-align: center; margin: 0;
                 display: inline-block; color: #0d4da7; font-size: 11px; font-weight: 500; cursor: pointer; line-height: 15px; width: 32.5%;}
			.tab-style a.active{ /*background: url(/images/botton_line.png) bottom center no-repeat;*/ background: #0d4da7; color: #fff; padding: 12px 0;}

.ui-inner-facetune a{ background: #fff; padding: 13px 15px 14px 15px; border-radius: 5px 0 0 5px; display: block; color: #475F7B; font-size: 15px; font-weight: 500;  text-align: left;}			
.ui-box{ margin-bottom: 10px; width: 100%; float: left; box-shadow: none; border: #ddd 1px solid; min-height:inherit; border-radius: 5px; padding-bottom: 0px; display: block; position: relative;}
.ui-box2{ margin-bottom: 10px; box-shadow: none; border: #ddd 1px solid; min-height:inherit; border-radius: 5px; padding-bottom: 0px; display: block; position: relative;}
.info-accordion{ margin-top: -27px;}
.info-accordion .accordion-header{ background: #fff; border: #fff 1px solid; padding-right: 0;}
.info-2{ width: 26px; height: 26px; background: url(/images/css_sprites1.png?v=0.5) -39px -40px;}
.face-info{ position: absolute; top: -8px; right: 0; background: none; border: none; padding-right: 0; text-align: right;}
.face-info a{ color: #475F7B; display:inline-block;}
.face-info a:hover{ color: #A9BDDC;}
.a-1{ float: left; width: 85%;}
.a-2{ float: left; width: 13%; margin-top: 48px;}
.accordion-content{ width: 720%; margin-left: -650%;}
.color-orange{ color: #ffa200;}
.list-view-content{ background: #fff; border: 1px solid #e8e8e8;  border-radius: 3px;  margin: 10px;  padding: 10px;}
.list-view-content ul{}
.list-view-content li{ width: 49%; display: inline-block; font-size: 15px;}
.list-view-content li a{ color: #004471; line-height: 24px;}
</style>
	</head>
	<body>
	<!-- Google Tag Manager -->
	<amp-analytics type="gtag" data-credentials="include">
<script type="application/json">
{
  "vars" : {
    "gtag_id": "G-4TQDEZYH5H",
    "config" : {
      "G-4TQDEZYH5H": { "groups": "default" },
	  "linker": { "domains": ["www.aaocab.com", "m.aaocab.com", "www-aaocab-com.cdn.ampproject.org"] }
    }
  }
}
</script>
</amp-analytics>
<!--	<amp-analytics type="googleanalytics" config="http://www.aaocab.com/index/ga4" data-credentials="include">
		<script type="application/json">
			{
				"vars": {
					"GA4_MEASUREMENT_ID": "G-4TQDEZYH5H",
					"GA4_ENDPOINT_HOSTNAME": "www.google-analytics.com",
					"DEFAULT_PAGEVIEW_ENABLED": true,    
					"GOOGLE_CONSENT_ENABLED": true,
					"WEBVITALS_TRACKING": true,
					"PERFORMANCE_TIMING_TRACKING": false,
					"SEND_DOUBLECLICK_BEACON": false,
					"linkers": {
						"enabled": true,
						"destinationDomains": ["www.aaocab.com", "m.aaocab.com", "www-aaocab-com.cdn.ampproject.org"],
						"_gl": {
							"ids": {
								"_ga_4TQDEZYH5H": "${ga4SessionCookie}"
							},
							"proxyOnly": false
						}
					}
				}
			}
		</script>
	</amp-analytics> -->
	<amp-sidebar id="sidebar-right"
				 class="sample-sidebar"
				 layout="nodisplay"
				 side="right">
		<button on="tap:sidebar-right.close" class="btn-close"><amp-img src="/images/img-2022/vector.svg" width="15" height="15"></amp-img></button>
		<nav toolbar="(min-width: 3000px)"
			 toolbar-target="target-element-right">
			<ul id="menu">
				<li>
					<ul class="hidden">
						<?php
						if ($isLoggedin)
						{
							?>
							<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" >Hello <?= $uname ?><i class="fa fa-user" style="padding-left: 10px"></i></a></li>
							<li><a href="<?= Yii::app()->createUrl('users/view') ?>">My Profile</a></li> 
							<li><a href="<?= Yii::app()->createUrl('index/index'); ?>">New Booking</a></li>
							<li><a href="<?= Yii::app()->createUrl('booking/list'); ?>">Booking History</a></li>
							<li><a href="<?= Yii::app()->createUrl('users/refer'); ?>">Refer friends</a></li>
							<li><a href="<?= Yii::app()->createUrl('users/creditlist'); ?>">Gozo Coins</a></li>
							<li><a href="<?= Yii::app()->createUrl('users/changePassword') ?>"><nobr>Change Password</nobr></a></li> 
						<?php } ?>
						<li><a href="/agent/join">Become an agent</a></li>
						<li><a href="/vendor/join">Attach Your Taxi</a></li>
						<li><a href="/index/testimonial">Testimonials</a></li>
						<li><a href="/blog">Blog</a></li>
						<?php
						if (!$isLoggedin)
						{
							?>
							<li><a href="/signin">Sign In</a></li>
							<?php
						}
						else
						{
							?>
							<li><a href="<?= Yii::app()->createUrl('users/logout') ?>">Log Out</a></li>
						<?php } ?>
					</ul>
				</li>
			</ul>
		</nav>
	</amp-sidebar>
<a href="<?= Yii::app()->createUrl('scq/form') ?>" style="text-decoration: none; position: absolute; right: 45px; top: 18px;">
		<amp-img width="20" height="22" src="/images/img-2022/bx-phone-call.svg" alt="India"></amp-img> 
	</a>
	<button on="tap:sidebar-right.toggle" class="menu-icon"><amp-img src="/images/img-2022/bx-menu.svg" width="30" height="30" ></amp-img></button>
	<div id="target-element-right">
	</div>
	<?= $content; ?>
</body>
</html>
