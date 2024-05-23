<?php
$dboSettings = Config::get('dbo.settings');
$data		 = CJSON::decode($dboSettings);
?>
<?php if($getDboConfirmEndTime!='')
{
?>
<p class="mb10"><b>You are eligible for double back offer if you confirm before <?= date('D, jS M, h:i A', strtotime($getDboConfirmEndTime)) ?></b></p>
<?php }?>
<ol class="p15">
	<li class="mb10">This offer is valid for trips booked directly with Gozo Cabs either by booking by phone with us, on Gozocabs.com or through our mobile app.</li>
	<li class="mb10">The trip must be booked and paid for at least > 24hours in advance of travel date and time and must have paid in advance. The Double back program requires that there be a time difference of 24hours from the time payment is received for the booking and the trip start time.Double Back is not applicable for vehicle tempo traveller.</li>
	<li class="mb10">DOUBLE BACK is available for trips booked all over India.</li>
	<li class="mb10">The offer period is valid from <?php echo date("F j, Y", strtotime($data['dboStartDate'])); ?> to <?php echo date("F j, Y", strtotime($data['dboEndDate'])); ?>.</li>
	<li class="mb10">The DOUBLE BACK GUARANTEE will match up to ₹1000 to double the original amount that you paid in advance at the time of booking. Amount that we will refund as double against the advance will be made in the Gozo wallet and is subject to conditions.</li>
	<li class="mb10">Gozo Cabs reserves the right to terminate the offer without giving any notice at its sole discretion.</li>
	<li class="mb10">Refunds are usually processed within 21 days from the date of request, however, Gozo is not responsible for delays from the payment gateways. Gozo shall issue our guaranteed refund-match amount after due diligence is done by our team to ensure that the T&C of double back offer were met and that the act was free of malice or malicious intent.</li>
	<li class="mb10">No other compensation shall be applicable alongside this offer.</li>
	<li class="mb10">The listed fares are subject to change without any prior notification.</li>
	<li class="mb10">Gozo reserves the right to refuse or deny service to anyone. In such cases, your booking will not be accepted and DOUBLE BACK shall not apply. In all such cases, Gozo may cancel the booking at least 5 days before pickup time or within 24 hours of receiving your booking request whichever is later.</li>
	<li class="mb10">This program does not apply to bookings that are generated from a third-party marketing affiliate or reseller partner of Gozo cabs. Only applies to bookings directly made on GozoCabs.com website or mobile app or booked with us by directly by phone</li>
	<li class="mb10">In addition to the above terms, all standard Gozo terms and conditions as listed at www.gozocabs.com/terms shall be applicable.</li>
	<li class="mb10">For example, if you have paid ₹500 advance and Gozo cancels the trip, you’ll get ₹1000 refund in wallet. If you have paid ₹1000 in advance and Gozo is unable to arrange a car, you’ll get ₹2000 refund. If you have paid ₹1500 in advance and Gozo is unable to arrange a car, you’ll get ₹2500 refund, as it is capped by our ₹1000 match guarantee.</li>
	<li class="mb10">Double Back program guarantee is only applicable when Gozo Cabs is unable to allocate a cab for your service and rest of the advance booking & payment terms & conditions are met and not when the ride is canceled by the customer.</li>
	<li class="mb10">Gozo reserves the right in its sole discretion to modify or discontinue the Double back program or to restrict its availability to any person, at any time, for any or no reason, and without prior notice or liability to you. The terms that are in effect at the time of your booking will determine your eligibility under the Double back program.</li>
	<li class="mb10">The failure by Gozo to enforce any provision of these Terms & Conditions shall not constitute a waiver of that provision.</li>
	<li class="mb10">In the scenario where Gozo’s car is a no-show, customer must inform via an email immediately to info@gozocabs.com (will auto-reply with a case #) or via a phone call or web chat and get a case # immediately. Double back offer cannot be honored in the case of car no-show complaints unless a support case # was created and it is agreed between Gozo and customer in writing via an email or other instrument that the vehicle was not available for service.</li>
	<li class="mb10">Double back program shall not be applicable if the vehicle is allocated but gets delayed due to traffic or other conditions beyond our control. So long as Gozo has allocated the vehicle to serve the ride, any cancellation by the customer because of delay in pick up or break down shall not apply for Double back guarantee.</li>
	<li class="mb10">The Double Back Offer shall not be applicable in the case of bookings that were cancelled due to natural calamity, political unrest, or unforeseen circumstances.</li>
</ol>