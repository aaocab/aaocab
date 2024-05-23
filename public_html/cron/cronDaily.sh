php -q /home/gcuser/public_html/cron.php vehicle updateCode
php -q /home/gcuser/public_html/cron.php driver updateCode
php -q /home/gcuser/public_html/cron.php booking updateTrail
php -q /home/gcuser/public_html/cron.php vendor updateOrientation
php -q /home/gcuser/public_html/cron.php agent lockedOutstandingBalance
php -q /home/gcuser/public_html/cron.php vendor setEffectiveCreditLimit
php -q /home/gcuser/public_html/cron.php vendor setLouRequiredFlag
php -q /home/gcuser/public_html/cron.php agent setEffectiveCreditLimit
php -q /home/gcuser/public_html/cron.php transaction updateOutstandingLedgerBalance
php -q /home/gcuser/public_html/cron.php system updateCityStats
php -q /home/gcuser/public_html/cron.php vehicle addBoost
php -q /home/gcuser/public_html/cron.php vehicle rejectBoost
#php -q /home/gcuser/public_html/cron.php agent mMTDataPickup --minDays=3 --maxDays=15
php -q /home/gcuser/public_html/cron.php agent mMTDataCreated
php -q /home/gcuser/public_html/cron.php system autoVendorApproval
php -q /home/gcuser/public_html/cron.php system autoCloseDispatchQueue
php -q /home/gcuser/public_html/cron.php system autoCloseUpSellQueue
php -q /home/gcuser/public_html/cron.php system autoCloseB2BPostPickupQueue 
php -q /home/gcuser/public_html/cron.php system autoCloseBARQueue
php -q /home/gcuser/public_html/cron.php system autoCloseDemMisFire
php -q /home/gcuser/public_html/cron.php system autoFurForRating
php -q /home/gcuser/public_html/cron.php system autoCloseAutoFurForRating
php -q /home/gcuser/public_html/cron.php system archive
php -q /home/gcuser/public_html/cron.php zone InsertDZPP1Day
php -q /home/gcuser/public_html/cron.php zone InsertDZPP90Day
php -q /home/gcuser/public_html/cron.php zone InsertDZPPGlobal
#php -q /home/gcuser/public_html/cron.php zone UpdateZoneVendorMapped
php -q /home/gcuser/public_html/cron.php system AdminAttendance
php -q /home/gcuser/public_html/cron.php system getAllDocsImage
php -q /home/gcuser/public_html/cron.php system DocExpNotification10days
php -q /home/gcuser/public_html/cron.php booking updGozoAmt
php -q /home/gcuser/public_html/cron.php system AutoCloseCSA
php -q /home/gcuser/public_html/cron.php booking reviewMmt
php -q /home/gcuser/public_html/cron.php pickupAlert bestPriceGuarantee
php -q /home/gcuser/public_html/cron.php newsletter bookGozoAgain
php -q /home/gcuser/public_html/cron.php emailreport dailyBookingPickup
php -q /home/gcuser/public_html/cron.php emailreport bookingCompletedToday2
php -q /home/gcuser/public_html/cron.php booking notifyCustomer
php -q /home/gcuser/public_html/cron.php vendor rmndVndCollectRating

find ~/protected/doc/ -empty -type d -print -delete
find ~/protected/contact/ -empty -type d -print -delete
find ~/public_html/attachments/ -empty -type d -print -delete

#php -q /home/gcuser/public_html/cron.php vendor sendSmsLastActive
#php -q /home/gcuser/public_html/cron.php driver broadcastMsg
#php -q /home/gcuser/public_html/cron.php system updatePartnerStateData
#php -q /home/gcuser/public_html/cron.php vendor verifyVendorStatus
#php -q /home/gcuser/public_html/cron.php system vendorTDS
#php -q /home/gcuser/public_html/cron.php system optimizeSelectedTables
php -q /home/gcuser/public_html/cron.php booking FetchBookingStats
#php -q /home/gcuser/public_html/cron.php system UpdateUserCities
#php -q /home/gcuser/public_html/cron.php system UpdateDriverCities
#php -q /home/gcuser/public_html/cron.php system UpdateDriverZoneMaster
#php -q /home/gcuser/public_html/cron.php booking FetchBookingCitiesStats
#php -q /home/gcuser/public_html/cron.php system LocationStatsDaily
php -q /home/gcuser/public_html/cron.php zone InsertDURP1Day
php -q /home/gcuser/public_html/cron.php zone InsertDURP90Day
php -q /home/gcuser/public_html/cron.php system UpdateEventSurge
php -q /home/gcuser/public_html/cron.php vendor PtnrDependency
php -q /home/gcuser/public_html/cron.php vendor SetVendorCoins
#php -q /home/gcuser/public_html/cron.php vendor freezeVendorForSecurityDeposit
php -q /home/gcuser/public_html/cron.php driver SetDriverCoins
#php -q /home/gcuser/public_html/cron.php zone UpdateZoneCapacity
php -q /home/gcuser/public_html/cron.php vehicle verifyStatus
php -q /home/gcuser/public_html/cron.php system autoFURForBookingCancellation
php -q /home/gcuser/public_html/cron.php driver HighRatingFreezeDriver
php -q /home/gcuser/public_html/cron.php system AutoCloseManaualAssignment
php -q /home/gcuser/public_html/cron.php system AutoCloseDispatchFollowUp
php -q /home/gcuser/public_html/cron.php accounting calculateSD
php -q /home/gcuser/public_html/cron.php onetime processVendorTDS
php -q /home/gcuser/public_html/cron.php booking BookingReferal
php -q /home/gcuser/public_html/cron.php booking ProcessReferalPayout
php -q /home/gcuser/public_html/cron.php system UpdateWhatsappNumber
#php -q /home/gcuser/public_html/cron.php system ReferAFriend
php -q /home/gcuser/public_html/cron.php vendor GozoNowNotificationStats
php -q /home/gcuser/public_html/cron.php vendor UpdateVendorToDCO
php -q /home/gcuser/public_html/cron.php vendor UpdateDCOToVendor