<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
		<title>GozoCabs</title>

<!--		<link rel="stylesheet" type="text/css" href="<?= ASSETS_URL ?>/fonts/css/fontawesome-all.min.css">-->
		<link rel="stylesheet" type="text/css" href="<?= ASSETS_URL ?>/css/mobile/framework.css">
        <link rel="stylesheet" type="text/css" href="<?= ASSETS_URL ?>/css/mobile/framework-store.css">
		<link rel="stylesheet" type="text/css" href="<?= ASSETS_URL ?>/css/mobile/style.css">

		<link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Hind&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	</head>
	<script>
        var $baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
       function ajaxindicatorstart(text)
        {
            if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading')
            {
                jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader1.gif"><div>' + text + '</div></div><div class="bg"></div></div>');
            }

            jQuery('#resultLoading').css({
                'width': '100%',
                'height': '100%',
                'position': 'fixed',
                'z-index': '10000000',
                'top': '0',
                'left': '0',
                'right': '0',
                'bottom': '0',
                'margin': 'auto'
            });

            jQuery('#resultLoading .bg').css({
                'background': '#ddd',
                'opacity': '0.6',
                'width': '100%',
                'height': '100%',
                'position': 'absolute',
                'top': '0'
            });

            jQuery('#resultLoading>div:first').css({
                'width': '250px',
                'height': '75px',
                'text-align': 'center',
                'position': 'fixed',
                'top': '0',
                'left': '0',
                'right': '0',
                'bottom': '0',
                'margin': 'auto',
                'font-size': '16px',
                'z-index': '10',
                'color': '#111'

            });

            jQuery('#resultLoading .bg').height('100%');
            jQuery('#resultLoading').fadeIn(100);
            jQuery('body').css('cursor', 'wait');
        }

        function ajaxindicatorstop()
        {
            jQuery('#resultLoading .bg').height('100%');
            jQuery('#resultLoading').fadeOut(100);
            jQuery('body').css('cursor', 'default');
        }
	</script>

	<?= $content; ?>

</html>
