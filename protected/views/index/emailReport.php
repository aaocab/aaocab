<?php
error_reporting(-1);
ini_set('display_errors', 1);

$req = new Booking();
$results = $req->getBusinessPastDays();
$emailWrapper = new emailWrapper();
$results['action'] = $action;
$emailWrapper->emailReport($results);

?>

