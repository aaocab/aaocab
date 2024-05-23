<?php

#AUTOGENERATED BY HYBRIDAUTH 2.1.1-dev INSTALLER - Wednesday 16th of August 2017 02:53:28 PM

/* !
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "https://";

return
        array(
            "base_url" => "{$protocol}{$_SERVER['HTTP_HOST']}/users/oauth",
            "providers" => array(
                // openid providers
                "OpenID" => array(
                    "enabled" => false
                ),
                "AOL" => array(
                    "enabled" => false
                ),
                "Yahoo" => array(
                    "enabled" => false,
                    "keys" => array("id" => "", "secret" => "")
                ),
                "Google" => array(
                    "enabled" => true,
                    "keys"		 => array("id" => "794396816054-eiut1guo55kfnjp4et57d5fq3akppnb3.apps.googleusercontent.com", "secret" => "b8NY_IWxQlYGgEFawGFspcKG"),
					"scope"		 => "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email",
				),
                "Facebook" => array(
                    "enabled" => true,
                    "keys" => array("id" => "1760374187589468", "secret" => "7a44944d5422d46da586d5e3b6b511f9"),
                    "allowSignedRequest" => true,
                    'scope'        => [
                        'email', 
                        'public_profile', 
                    ]
                ),
                "Twitter" => array(
                    "enabled" => false,
                    "keys" => array("key" => "", "secret" => "")
                ),
                // windows live
                "Live" => array(
                    "enabled" => false,
                    "keys" => array("id" => "", "secret" => "")
                ),
                "MySpace" => array(
                    "enabled" => false,
                    "keys" => array("key" => "", "secret" => "")
                ),
                "LinkedIn" => array(
                    "enabled" => false,
                    "keys" => array("key" => "", "secret" => "")
                ),
                "Foursquare" => array(
                    "enabled" => false,
                    "keys" => array("id" => "", "secret" => "")
                ),
            ),
            // if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
            "debug_mode" => false,
            "debug_file" => ""
);
