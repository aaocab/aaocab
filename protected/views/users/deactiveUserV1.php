<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		body{
			font-family: 'Arial';
			font-size: 12px;
			line-height: 20px;
			margin: 0;
			padding: 0;
		}
		.gray-color{
			color: #aeaeae;
		}
		p{
			font-size: 12px;
		}
		h1{
			font-size: 22px;
			line-height: 22px;
		}
		h2{
			font-size: 16px;
			line-height: 22px;
			font-weight: normal;
		}
		h3{
			font-size: 15px;
			line-height: 22px;
		}
		.text-center{
			text-align: center;
		}
		.main-div{
			width:720px;
			margin: auto;
			font-size: 12px!important;
		}
		.border-bottom{
			border-bottom: #ededed 1px solid;
		}
		.list_type li{
			padding: 8px 0;
		}
		.list_table table{
			width: 100%;
		}
		.list_table td{
			padding: 8px;
			border: #d3d3d3 1px solid;
			font-family: 'Arial';
			font-size: 12px;
			line-height: 20px;
		}
		.blue-color{
			color: #1a4ea2;
		}
		.orange-color{
			color: #f36c31;
		}
		a{
			color: #f36c31;
		}
.card{ box-shadow: -8px 12px 18px 0 rgb(25 42 70 / 13%); padding: 15px; width: 80%; margin: 0 auto; margin-top: 30px;}
		.card h3{ font-size: 18px;}
		label{ display: block; font-size: 16px;}
		textarea{ font-family: 'Arial';}
		@media (min-width: 320px) and (max-width: 767px) {
			.main-div{
				width:94%!important;
				margin: auto!important;
				font-size: 12px!important;
			}
		}
	</style>
</head><!-- comment -->
<div class="card">
	<p style="font-size:16px;"><?= $data['usr_name'] ?>&nbsp;<?= $data['usr_lname'] ?></p>
	<p style="font-size:16px;"><?= $data['usr_mobile'] ?></p> 
	<p style="font-size:16px;"><?= $data['usr_email'] ?></p>
	<h4 style="font-size:16px; color: #39DA8A;"><?=$message;?> </h4>
</div>