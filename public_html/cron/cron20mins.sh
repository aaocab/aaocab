#php -q /home/gcuser/public_html/cron.php transaction getstatus
#php -q /home/gcuser/public_html/cron.php booking updateStatus
php -q /home/gcuser/public_html/cron.php booking updateRelated
php -q /home/gcuser/public_html/cron.php booking unverifiedFollowup
php -q /home/gcuser/public_html/cron.php booking leadFollowup
#php /home/gcuser/public_html/cron.php pickupAlert remindPickup
#php -q /home/gcuser/public_html/cron.php booking mmtUnverified
#php -q /home/gcuser/public_html/cron.php pickupAlert sendCabAssignedEmail
php -q /home/gcuser/public_html/cron.php booking quotedToUnverified
php -q /home/gcuser/public_html/cron.php booking pricelockForQT
#wget -qO- http://www.aaocab.com/payment/updateAdvance &> /dev/null
php -q /home/gcuser/public_html/cron.php booking criticalTripAmount
php -q /home/gcuser/public_html/cron.php booking autoCancelRule
php -q /home/gcuser/public_html/cron.php system AutoCloseCSA
