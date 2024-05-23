#php -q /home/gcuser/public_html/cron.php system populateTopDemandRoutes
#php -q /home/gcuser/public_html/cron.php vendor readyApprovalScore
#php -q /home/gcuser/public_html/cron.php vendor stopPaymentForOtherB2BPartners
php -q /home/gcuser/public_html/cron.php vendor stopVendorPaymentForOtherB2BPartners
php -q /home/gcuser/public_html/cron.php transaction transferNegativeWalletBalanceToLedgerBalance
php -q /home/gcuser/public_html/cron.php system setCallingDurationMedian
php -q /home/gcuser/public_html/cron.php booking sendReviewMailOnCompleted
php -q /home/gcuser/public_html/cron.php agent mMTDataCreated --minDays=0 --maxDays=0