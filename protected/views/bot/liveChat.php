<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promiseResolver.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/css/liveChatWindow.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/liveChat.js?v=' . $version);
?>
<head>
	<title>Gozo | Live Chat</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
		textarea { border: none; }
    </style>
</head>
<body>
	<div class="messaging">
		<div class="inbox_msg">
			<div class="mesgs">
				<div class="msg_history">

				</div>
				<div class="type_msg">
					<div class="input_msg_write">
                        <textarea class="write_msg" placeholder="Type a message" cols="208"></textarea>
						<button class="msg_send_btn" type="button" onclick="sendMessage()"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>