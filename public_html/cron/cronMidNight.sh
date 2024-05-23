php -q /home/gcuser/public_html/cron.php emailreport daily
php -q /home/gcuser/public_html/cron.php booking unverifiedFollowupFeedback
php -q /home/gcuser/public_html/cron.php emailreport cancelDaily
php -q /home/gcuser/public_html/cron.php appreciationMessage sendAppreciationMessage
php -q /home/gcuser/public_html/cron.php driver autoRejectDocument
php -q /home/gcuser/public_html/cron.php driver autoDisapprove
php -q /home/gcuser/public_html/cron.php vehicle autoRejectDocument
#php -q /home/gcuser/public_html/cron.php vehicle autoDisapprove
php -q /home/gcuser/public_html/cron.php vendor adminFreeze
php -q /home/gcuser/public_html/cron.php vendor updateVendorsSummary
php -q /home/gcuser/public_html/cron.php vendor updateDriverScore
php -q /home/gcuser/public_html/cron.php vendor updateFreeze
php -q /home/gcuser/public_html/cron.php vendor updateDormant
php -q /home/gcuser/public_html/cron.php system refreshProfitablitySurge
php -q /home/gcuser/public_html/cron.php vendor driverAppUsed
php -q /home/gcuser/public_html/cron.php vendor dayWiseMargin
#php -q /home/gcuser/public_html/cron.php vendor updateIdentity
#php -q /home/gcuser/public_html/cron.php vehicle addBoost
php -q /home/gcuser/public_html/cron.php system updatevendor
php -q /home/gcuser/public_html/cron.php system updatedriver --interval=2
php -q /home/gcuser/public_html/cron.php system updatevehicle
#php -q /home/gcuser/public_html/cron.php vehicle rejectBoost
php -q /home/gcuser/public_html/cron.php vendor updateLastAcceptedBidDatetime
#php /home/gcuser/public_html/cron.php booking channelPartnerPushReportDaily
php -q /home/gcuser/public_html/cron.php system updateCustomerStats