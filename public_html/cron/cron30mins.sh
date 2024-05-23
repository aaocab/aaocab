php -q /home/gcuser/public_html/cron.php pickupAlert postAutoCancelBeforePickup
php -q /home/gcuser/public_html/cron.php pickupAlert vendor
#php /home/gcuser/public_html/cron.php pickupAlert remindVendor24
php -q /home/gcuser/public_html/cron.php agent rePushApiCall
#php -q /home/gcuser/public_html/cron.php agent pushMissingCabDriverDataApiCall
php -q /home/gcuser/public_html/cron.php booking updatePartnerTripVerify
# php -q /home/gcuser/public_html/cron.php booking pricelockForQT
# php -q /home/gcuser/public_html/cron.php booking saveCountBidFloated
php -q /home/gcuser/public_html/cron.php booking CancelUnconfirmedCavBookings
php -q /home/gcuser/public_html/cron.php booking sendQuotedBookingSmsEmail
php -q /home/gcuser/public_html/cron.php booking reviewmailforTripComplete
php -q /home/gcuser/public_html/cron.php booking getBookingForAutoCancelRule
php -q /home/gcuser/public_html/cron.php vendor sendCabDriverNotification
php -q /home/gcuser/public_html/cron.php driver customerNoshowPushToMMT
php -q /home/gcuser/public_html/cron.php system scqPriorityScore
php -q /home/gcuser/public_html/cron.php system ShiftTimeOver
php -q /home/gcuser/public_html/cron.php system AutoFURDocumentApproval
php -q /home/gcuser/public_html/cron.php system getAllImageForIread
php -q /home/gcuser/public_html/cron.php agent AddPartnerSetting
php -q /home/gcuser/public_html/cron.php agent AddPartnerRuleCommision
php -q /home/gcuser/public_html/cron.php booking cancelUnverifiedTFRBookings
php -q /home/gcuser/public_html/cron.php booking sendQuoteExpiryReminderToCustomerNew