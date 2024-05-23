#/home/gozouser/automysqlbackup/automysqlbackup /home/gozouser/automysqlbackup/myserver.conf
#rsync -aP --delete -e ssh /home/gozouser/automysqlbackup/db/  root@128.199.140.133:/backups/db/
#php /home/gcuser/public_html/cron.php emailreport snapshot
php -q /home/gcuser/public_html/cron.php pickupAlert customerAlertBeforePickup
php -q /home/gcuser/public_html/cron.php booking customerReviewMail
php -q /home/gcuser/public_html/cron.php booking nonCommercialPickup
php -q /home/gcuser/public_html/cron.php vendor readyApprovalScore
php -q /home/gcuser/public_html/cron.php driver readyApprovalScore
php -q /home/gcuser/public_html/cron.php vehicle readyApprovalScore
#php -q /home/gcuser/public_html/cron.php ola updateRateByOla
php -q /home/gcuser/public_html/cron.php system AutoCloseGozoNow