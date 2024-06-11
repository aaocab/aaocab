<?
//Yii::app()->yiistrap->register();
Yii::app()->clientScript->registerPackage('jquery');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/bootbox/bootbox.min.js');
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>:: GOZO ::</title>
        <!-- Sets initial viewport load and disables zooming  -->
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
        <!-- SmartAddon.com Verification -->
        <meta name="keywords" content="">
        <meta name="description" content="">
        <link rel="bookmark" href="favicon_16.ico"/>
        <!-- site css -->
        <link rel="stylesheet" href="/css/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" href="/css/site.min.css">
        <link rel="stylesheet" type="text/css" href="/css/component.css" />
        <link href="/css/hover.css" rel="stylesheet" media="all">
        <link rel="stylesheet" href="/css/site.css?v=<?= Yii::app()->params['sitecssVersion']; ?>">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->
        <script>
            $(function () {

                $(window).on("scroll", function () {

                    if ($(window).scrollTop() > 50) {

                        $(".top-menu").addClass("white-header");

                    } else {

                        //remove the background property so it comes transparent again (defined in your css)

                        $(".top-menu").removeClass("white-header");

                    }

                });
            });
        </script>
    </head>
    <body>
        <div class="fixed-menu"><a href="#" class="social-1 hvr-push"><i class="fa fa-facebook"></i></a><a href="#" class="social-2 hvr-push"><i class="fa fa-twitter"></i></a><a href="#" class="social-3 hvr-push"><i class="fa fa-google-plus"></i></a></div>
            <nav class="navbar navbar-default navbar-fixed-top top-menu">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-3 col-sm-2 col-md-4">
                            <div class="navbar-header logo">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="#"><img src="/images/logo.png?v1.1" alt="Chania"></a>
                            </div>
                        </div>
                        <div class="col-xs-9 col-sm-10 col-md-8">
                            <div class="text-right phone-line">
                                <span class="nav-phone-icon"><i class="fa fa-phone"></i> |</span> <span class="nav-phone"> <img src="/images/india-flag.png?v1.1" alt="INDIA"> (+91) 90518-77-000<span class="nav-phone-24x7">  (24x7)</span></span>        <span class="nav-phone">&nbsp; <img src="/images/worl-icon.png?v1.1" alt="International"> (+1) 650-741-GOZO   (24x7)</span>
                            </div>
                        </div>
                    </div>
                    <div class="row  new-navbar">
                        <div class="col-xs-12 float-none pr10">
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav navbar-right mr0">
                                    <li><a href="#" class="hvr-overline-from-center">our specials</a></li>
                                    <li><a href="#" class="hvr-overline-from-center">Why Gozo Cabs</a></li>
                                    <li><a href="#" class="hvr-overline-from-center">Our Blog</a></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle orange-bg" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sign in/Register <span class="caret"></span></a>
                                        <div class="dropdown-menu form-group" style="padding: 15px; padding-bottom: 0px;">
                                            <form method="post" action="login" accept-charset="UTF-8">
                                                <input style="margin-bottom: 15px;" type="text" placeholder="Email" id="name" name="name" class="p5">
                                                <input style="margin-bottom: 15px;" type="password" placeholder="Password" id="country" name="country" class="p5">
                                                <input class="btn btn-primary btn-block" type="submit" id="sign-in" value="Sign In">
                                                <label style="text-align:center;margin-top:5px; width:100%; color:#FFFFFF;">or</label>
                                                <input class="btn btn-primary btn-block orange-bg border-none mb10 border-radius" type="button" id="sign-in-google" value="Register">
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        
        <div class="bg-banner container-fluid">
            <?=$content?>
                    </div>
        <footer id="footer">
            <div class="container">
                <div class="row">    
                    <div class="col-xs-12 col-sm-6 col-md-3 column">          
                        <h4 class="orange pt5 pb5 weight400">Gozo Cabs</h4>
                        <ul class="nav">
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">FAQS</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Openings</a></li>
                            <li><a href="#">Terms and Conditions</a></li>
                            <li><a href="#">Disclaimer</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul> 
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-5 column">          
                        <h4 class="orange pt5 pb5 weight400">Social Media</h4>
                        <div class="social-panel2 p0">
                            <a href="#" class="social-1 hvr-grow"><i class="fa fa-facebook"></i></a><a href="#" class="social-2 hvr-grow"><i class="fa fa-twitter"></i></a><a href="#" class="social-3 hvr-grow"><i class="fa fa-google-plus"></i></a>
                        </div>
                        <div class="col-xs-12 p0 mt30">
                            <h3 class="m0 mb10">Call</h3>
                            <p><img src="/images/india-flag.png" alt="India"> (+91) 90518-77-000 (24x7)</p>
                            <p><img src="/images/worl-icon.png" alt="India"> (+91) 90518-77-000 (24x7)</p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 column">          
                        <h4 class="orange pt5 pb5 weight400">Address</h4>
                        610, Jaksons Crown Heights, Plot No 381, Twin District Centre, 
                        Sector-10, Rohini, Delhi 110085.
                        <div class="address-panel mt40">
                            <h3 class="m0 mb10">E-mail</h3>
                            <a href="#">info@aaocab.com.</a>
                        </div>
                    </div>
                </div>
            </div><!--/row-->
        </div>
        <div class="blue2 mt20 text-center white-color pt15 pb15">© 2015 www.aaocab.com. All rights reserved.</div>
    </footer>









</body>
</html>
