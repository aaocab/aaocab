<style>
.ques{
    font-weight: bold;

}
.ans{
    padding-bottom: 15px;
}
  #btnTop {
  display: none; /* Hidden by default */
  position: fixed; /* Fixed/sticky position */
  bottom: 50%; /* Place the button at the bottom of the page */
  right: 30px; /* Place the button 30px from the right */
  z-index: 99; /* Make sure it does not overlap */
  border: none; /* Remove borders */
  outline: none; /* Remove outline */
  background-color: #f14848; /* Set a background color */
  color: white; /* Text color */
  cursor: pointer; /* Add a mouse pointer on hover */
  padding: 4px; /* Some padding */
  border-radius: 100px; /* Rounded corners */
  font-size: 14px; /* Increase font size */
  width: 30px; height: 30px;
}

#btnTop:hover {
  background-color: #555; /* Add a dark-grey background on hover */
}

 #btnBottom {
  display: none; /* Hidden by default */
  position: fixed; /* Fixed/sticky position */
  bottom: 50%; /* Place the button at the bottom of the page */
  right: 64px; /* Place the button 30px from the right */
  z-index: 99; /* Make sure it does not overlap */
  border: none; /* Remove borders */
  outline: none; /* Remove outline */
  background-color: #3ec4b5; /* Set a background color */
  color: white; /* Text color */
  cursor: pointer; /* Add a mouse pointer on hover */
  padding: 4px; /* Some padding */
  border-radius: 100px; /* Rounded corners */
  font-size: 14px; /* Increase font size */
  width: 30px; height: 30px;
}
#btnBottom:hover {
  background-color: #555; /* Add a dark-grey background on hover */
}
</style>

        <article>
            <section>
                <div class="right_ul">
                    <button onclick="topFunction()" id="btnTop" title="Go to top"><i class="fa fa-arrow-up"></i></button>
                    <button onclick="bottomFunction()" id="btnBottom" title="Go to bottom"><i class="fa fa-arrow-down"></i></button>
                    <div class="h4 block-color">General questions</div>
                    <ul>
                        <li><a href="#faq1">Are there any cancellation charges?</a></li>
                        <? /* /?><li><a href="#faq2">How much do I have to pay in advance for booking a one-way taxi?</a></li> <?/ */ ?>
                        <li><a href="#faq3">Can I request my taxi to be waiting at a place (e.g airport or street intersection)?</a></li>
                        <li><a href="#faq4">Do you provide A/C (Air conditioned) cars?</a></li>
                        <li><a href="#faq5">Do you provide multiple pickups or drops?</a></li>
                        <li><a href="#faq6">Do you provide non AC cabs?</a></li>
                        <li><a href="#faq7">Do you provide self drive cars?</a></li>
                        <li><a href="#faq8">How and when do I get the driver and cab details ?</a></li>
                        <li><a href="#faq9">How can I edit my existing booking?</a></li>
                        <li><a href="#faq10">How do I get confirmation about our booking?</a></li>
                        <li><a href="#faq11">I want a taxi on a sharing basis. Will you provide it to me?</a></li>
                        <li><a href="#faq12">I want to make a booking for a round-trip with Gozo cabs, can I do that?</a></li>
                        <li><a href="#faq12a">I'm going from City A to city B for x days. During that time I will travel to neighboring cities from city B - is that all included in my quoted kms?</a></li>
                        <li><a href="#faq13">I'm hiring a car from Gozo. In the kms quoted on my fixed quote can I use the car for local travel before I leave the city?</a></li>
                        <li><a href="#faq14">In how many cities do you provide services?</a></li>
                        <li><a href="#faq15">My driver overcharged me during the trip... How can you help me?</a></li>
                        <li><a href="#faq16">Our company employees travel regularly. Who do I contact for tie-up?</a></li>
                        <li><a href="#faq17">Sometimes I find Gozo's prices are slightly higher compared to other companies on the same routes</a></li>
                        <li><a href="#faq18">What advantages do I get by booking in advance?</a></li>
                        <li><a href="#faq19">What car categories do you provide?</a></li>
                        <li><a href="#faq20">What if Driver asks me for extra payment? and I do not agree...</a></li>
                        <li><a href="#faq21">What if my cab shows up late?</a></li>
                        <li><a href="#faq22">What if my taxi does not show up?</a></li>
                        <li><a href="#faq23">What if the cab breaks down during the journey</a></li>
                        <li><a href="#faq24">What is the earliest I should make a reservation with Gozo?</a></li>
                        <li><a href="#faq25">Will Gozo drivers take detour per our request?</a></li>
                        <li><a href="#faq36">We will be landing at Bangalore airport and then want to go Cochin? Should I prebook my car or book after landing?</a></li>
                        <li><a href="#faq37">Do you provide an English-speaking driver?</a></li>
                        <li><a href="#faq50">I left something in the Gozo Cab, how do I report the lost item so you can help me get it back.</a></li>
                        <li><a href="#faq51">Are pets allowed?</a></li>
                        <li><a href="#faq52">I see there are some bad reviews for GozoCabs online. Why is that?</a></li>
                    </ul>
                    <div class="h4 block-color mt30">Making, modifying or cancelling a reservation</div>
                    <ul>
                        <li><a href="#faq26">How can I cancel my booking with Gozo?</a></li>
                        <li><a href="#faq27">How can I get a bill/receipt for my trip?</a></li>
                        <li><a href="#faq28">How many people can travel per taxi?</a></li>
                        <li><a href="#faq29">I have done my booking by phone, is toll included in the costs?</a></li>
                        <li><a href="#faq30">Is Toll tax included or excluded from my Total fare?</a></li>
                        <li><a href="#faq31">We have elderly travelers in the vehicle?</a></li>
                        <li><a href="#faq32">What if I have a lot of luggage to be carried during the journey?</a></li>
                        <li><a href="#faq33">What is the best way to make a booking so I can get the best quality and good price?</a></li>
                    </ul>
                    <div class="h4 block-color mt30">Questions about charges or payment</div>
                    <ul>
                        <li><a href="#faq34">Are there any waiting charges?</a></li>
                        <li><a href="#faq35">Do I need to pay any extra amount apart from the one listed here on website?</a></li>
                    </ul>
                    <div class="h4 block-color mt30"><a name="flexxi">Gozo Flexxi SHARE</a></div>
                    <ul>
                        <li><a href="#faq38">What is Gozo Flexxi SHARE?</a></li>
                        <li><a href="#faq39">How does Gozo Flexxi SHARE work?</a></li>
                        <li><a href="#faq40">What is the benefit for Gozo Flexxi SHARE riders?</a></li>
                        <li><a href="#faq41">What is the benefit for Gozo Flexxi SHARE promoters?</a></li>
                        <li><a href="#faq42">Can I pay the driver cash?</a></li>
                        <li><a href="#faq43">As the Gozo Flexxi SHARE promoter, Can I reschedule my trip time?</a></li>
                        <li><a href="#faq44">I'm a Gozo Flexxi SHARE promoter, can I get cash back instead of getting credit applied to my booking amount?</a></li>
                        <li><a href="#faq45">I have booked a Gozo Flexxi SHARED seat, how long will the car wait for me?</a></li>
                        <li><a href="#faq46">What is the cancellation policy for Gozo Flexxi SHARE?</a></li>
                        <li><a href="#faq47">Why do I have to sign in with my Facebook profile when booking a Gozo Flexxi SHARE?</a></li>
                        <li><a href="#faq48">What are the other terms and conditions for booking a Gozo Flexxi SHARE?</a></li>
                        <li><a href="#faq49">How do you collect toll from every person in the shared taxi?</a></li>
                    </ul>
                </div>
            </section>
            <section class="right_section">
                <ol>
                    <a name="faq1"></a>
                    <li>
                        <h4 class="mt30"><b>Are there any cancellation charges?</b></h4>
                        <p>We understand that plans do change. <b>There are no charges for cancelling a trip upto 24hours in advance.</b></p>
                        <p>You may cancel your reservation by logging onto <a href="http://www.aaocab.com/" target="_blank">www.aaocab.com</a> and and cancelling your reservation directly.<br>
                            <b>All bookings cancelled less than 24hours before a pickup shall be subject to a cancellation charge.</b>
                        </p>
                    </li>
                    <? /* /?><a name="faq2"></a>
                      <li>
                      <h4><b>How much do I have to pay in advance for booking a one-way taxi?</b></h4>
                      <p>You can make a 'zero advance payment' reservation with Gozo. such a reservation is referred to as a 'Pay later' reservation. You will be required to pay the final amount in cash to the driver at the completion of your journey. In the case of a 'Pay later' reservatoin, Gozo takes on the risk of making the vehicle available for you as there is a high risk of cancellation when you have not made a financial commitment to the journey. For a 'Pay Later' reservation, you will be charged a 'collect on delivery' fee which is a finance & convenience charge for the ability to make payments at a later time. In some cases, we may ask you to pay for the fuel charges during the journey, which will be deducted from the final amount at the time of bill settlement. Clients who have a high rate of trip cancellation may not be offered a 'Pay Later' option when making a reservation. </p>
                      </li> <?/ */ ?>
                    <a name="faq3"></a>
                    <li>
                        <h4 class="mt30"><b>Can I request my taxi to be waiting at a place (e.g airport or street intersection)?</b></h4>
                        <p>We require the the pickup address be a business address or a well known address. For the security of the driver, we do not accept 'street intersections' as pickup addresses.</p>
                    </li>
                    <a name="faq4"></a>
                    <li>
                        <h4 class="mt30"><b>Do you provide A/C (Air conditioned) cars?</b></h4>
                        <p>We provide only A/C cars. <br>
                        The A/C system is expected to be always working during the trip. 
The only exception is for trips to hilly areas where the A/C needs to be turned off for driving in hilly region to prevent overload on the car engine.
                        </p>
                    </li>
                    <a name="faq5"></a>
                    <li>
                        <h4 class="mt30"><b>Do you provide multiple pickups or drops?</b></h4>
                        <p>Sure, we can arrange multiple pickups or drops for your itinerary.  Please let us know about all the details of your itinerary and we can make the arrangements. </p>
                        <p>Please be aware that you will get billed Rs.150/- for every additional pickup, drop address and waypoints added to your itinerary. In addition you will be responsible for the additional kms driven beyond the included kms on your trip.</p>
                        <p>It is required that the entire itinerary be documented in your reservation. So please let us know of your planned itinerary so that its documented and that the driver has all the information ahead of your trip start time. The quoted price will change based on multiple factors including but not limited to the itinerary, waypoints, driving terrain, local union fees, local restrictions and estimated distances to be driven.</p>
                    </li>
                    <a name="faq6"></a>
                    <li>
                        <h4 class="mt30"><b>Do you provide non AC cabs?</b></h4>
                        <p>Sorry, we do not provide non AC cabs.</p>
                    </li>
                    <a name="faq7"></a>
                    <li>
                        <h4 class="mt30"><b>Do you provide self drive cars?</b></h4>
                        <p>Sorry, we do not provide self-drive cars. Gozo is India's leader in chauffeur-driven outstation taxi travel.</p>
                        <p>Our driver drives up to your doorstep with the vehicle and drives you around during your entire journey.</p>
                    </li>
                    <a name="faq8"></a>
                    <li>
                        <h4 class="mt30"><b>How and when do I get the driver and cab details ?</b></h4>
                        <p>We normally send you cab and driver information at least 4 hours before a pickup.</p>
                        <p>This information is sent to you via email and/or SMS. When creating your booking you have the option to select to receive this information by email, SMS or both.</p>
                    </li>
                    <a name="faq9"></a>
                    <li>
                        <h4 class="mt30"><b>How can I edit my existing booking?</b></h4>
                        <p>Please <a href="http://www.aaocab.com/login" target="_blank">login to the Gozo website.</a> Go to the My bookings section and you can make changes to your booking directly on the website by selecting the specific booking in booking history list. You can change your pickup date, time or add special instructions into the TEXT BOX.</p>
                    </li>
                    <a name="faq10"></a>
                    <li>
                        <h4 class="mt30"><b>How do I get confirmation about our booking?</b></h4>
                        <p>As soon as you create a booking with us, you will receiving the booking confirmation via email and/or SMS. 
</p>
<p>Please make sure your correct email and mobile phone information is entered under <a href="http://www.aaocab.com/index" target="_blank">your profile on GozoCabs</a></p>
                    </li>
                    <a name="faq11"></a>
                    <li>
                        <h4 class="mt30"><b>I want a taxi on a sharing basis. Will you provide it to me?</b></h4>
                        <p>Starting October 2018, we have introduced our Gozo Flexxi SHARE service. You can now rent a seat in a outstation cab provided by Gozo. Visit <a href="http://www.aaocab.com/goFLEXXI">www.aaocab.com/GozoSHARE</a> to learn more.</p>
                        <p>Simply start to create a booking and select the Gozo Flexxi SHARE option when selecting the Car Type. Renting a Gozo Flexxi SHARE for outstation travel is sometimes even cheaper than booking a bus ticket and almost always the fastest way to get there.</p>
                        </li>
                    <a name="faq12"></a>
                    <li>
                        <h4 class="mt30"><b>I want to make a booking for a round-trip with Gozo cabs, can I do that?</b></h4>
                        <p>Gozo cabs specializes in inter-city transportation and focuses specifically on providing quality service at fair prices. You can make reservations on our platform (web, mobile app or helpline/phone) for one-way, round-trip or multi-city trips.</p>
                        <p>We have also started to service Airport transfers at all major airports across India. So you can now use Gozo for chauffeur driven airport pickups and drops from all major airports in India.</p>
                    </li>
                    <a name="faq12a"></a>
                    <li>
                        <h4 class="mt30"><b>I'm going from City A to city B for x days. During that time I will travel to neighboring cities from city B - is that all included in my quoted kms?</b></h4>
                        <p>You have taken the vehicle for the allocated amount of kms and you are free to use the vehicle for that allocated amount of kms on flat terrain. </p>
                        <p>Any trips you make outside of the cities listed in your itinerary shall be chargeable at a higher rate per km. </p>
                        <p>As a best practice, we require that all the cities that you plan to visit should be listed in your written trip itinerary provided to you by Gozo. This way you have complete clarity on the pricing for the trip and the driver and operator have received proper communication regarding your trip plan. This ensures that there is no confusion and the price  quote you receive from Gozo takes your entire trip plan into account. By using this best practice you can avoid any surprises in terms of additional charges for kms driven or changes to itinerary.</p>
                    </li>
                    <a name="faq13"></a>
                    <li>
                        <h4 class="mt30"><b>I'm hiring a car from Gozo. In the kms quoted on my fixed quote can I use the car for local travel before I leave the city?</b></h4>
                        <p>Gozo specializes in intercity / outstation transportation services all over India. Each state has different rules and requirements for use of tourist vehicles. In addition, there are restrictions placed on where tourist vehicles can travel to based on rules set by local unions or city ordinances.</p>
                        <p>All vehicles you hire are quoted with a fixed amount of kms associated with the exact itinerary listed in your confirmation. Usage of the vehicle outside of the documented itinerary and/or beyond the listed kms may be subject to additional charges. Your use of the vehicle is limited to the activities that are clearly listed on your booking confirmation (booking contract)</p>
                        <p><b>Example 1:</b> If you are traveling from City A to City B and back, then you may use the vehicle to leave from City A, goto City B, travel within City B (as long as its within the quoted kms) and return back to City A. </p>
                        <p>If you wish to make short trips outside of City B to points of interest or tourist sites in towns/cities neighboring to City B, this needs to be first clearly listed in your trip plan. If its not listed in your trip plan this activity is not included and will be chargeable as extra. The rate per km that gets billed will be higher for hilly roads vs flat terrain</p>
                        <p><b>Example 2:</b> If you are taking a one-way transfer from city A to city B, then you can only take the vehicle from your pickup address to your drop address. 
Any intermediate pickup points (pick a friend, drop a friend etc) will need to be specified in your booking confirmation (booking contract) and would be separately billable if not listed on the contract.</p>
                        <p>We care for our customers and work hard to meet all your requests. We want you to have a special trip or vacation. Simply give us a call and we will work with you provide you the best solution for your needs. <br>
                            For all queries, please contact us at <a href="mailto:info@aaocab.com">info@aaocab.com</a> or simply call our help line
                        </p>
                        <p><b>Example 3:</b> If you are hiring a vehicle for a round trip from City A to to City B and back to City A, you must let us know if you desire to use the vehicle for local sight-seeing in City A (city of departure).  We need a clear itinerary of your plan so it can be documented 
& communicated to the driver</p>
                        <p><b>Any use of the vehicle outside of what is documented in your trip itinerary is considered unauthorized and may be subject to additional charges. </b></p>
                        <p></p>
                        <p></p>
                    </li>
                    <a name="faq14"></a>
                    <li>
                        <h4 class="mt30"><b>In how many cities do you provide services?</b></h4>
                        <p>We serve trips all over India. 
As of July 2017, we are serving in over 300 cities and 5000 routes across India.</p>
                        <p>Our city coverage across India is always expanding...and we are always happy to help you with your travel needs across India.<br>
                        If the city you want to travel to or from is not listed on our website, simply call us and we will do our best to serve you. 
                        </p>
                    </li>
                    <a name="faq15"></a>
                    <li>
                        <h4 class="mt30"><b>My driver overcharged me during the trip... How can you help me?</b></h4>
                        <p>Please send us full details of your experience by email at info [AT] Gozocabs [DOT] COM <br>
                        Mention your booking ID and whatever details you have so we can gain full context of the matter. </p>
                        <p>Our Customer advocacy team will get involved and help address the situation expediently.</p>
                    </li>
                    <a name="faq16"></a>
                    <li>
                        <h4 class="mt30"><b>Our company employees travel regularly. Who do I contact for tie-up?</b></h4>
                        <p>Please write to us at <a href="mailto:corporateaccounts@gozocabs.in">corporateaccounts@gozocabs.in</a></p>
                        <p>Learn more about our business travel program <a href="http://www.aaocab.com/business-travel" target="_blank">here</a></p>
                    </li>
                    <a name="faq17"></a>
                    <li>
                        <h4 class="mt30"><b>Sometimes I find Gozo's prices are slightly higher compared to other companies on the same routes</b></h4>
                        <p>Price is what you pay and value is what you get.</p>
                        <p>At Gozo, we focus on delivering the highest value for a fair price. In addition, our quotes are transparent with no pricing surprises later. Our philosophy is to not just get your business today but to get it forever after.</p>
                        <p>Sure it is possible that you may get a quote lower than ours, and thats where its important that you compare apples to apples.</p>
                        <p>When looking at a lower quote, here are some pricing traps to watch for....</p>
                        <ul>
                            <li>
                                Is the vehicle commercially licensed (YELLOW NUMBER PLATE) and driver commercially licensed? You do not want to travel in a private vehicle. Its not only illegal but also unsafe to use a private vehicle instead of one with a commercial permit
                            </li>
                            <li>Private vehicles are either uninsured or inadequately insured, lack commercial permits, evade paying tourist taxes and fees and may be using drivers with questionable driving experience or records.</li>
                            <li>Most companies will low ball you with quotes that only include base fare and may quote too few included kms for your trip. (Its a classis bait and switch scam)</li>
                            <li>Those providers would add on daily driver allowances, state taxes, toll taxes, excess km charges to your final bill and extort you for far more money than you had expected. This will lead to a unpleasant experience. Gozo always provides you a written quote (our confirmation email has all the details...read it carefully) and its best that you compare our written quote with theirs. </li>
                        </ul>
                        <p><b>Transparent apples-apples comparison is best when you are comparing Gozo's pricing with others.
You will find that in 99% of the situations, you not only save more but also have a great experience with Gozo. With Gozo you get a good price and great value.</b></p>
                    </li>
                    <a name="faq18"></a>
                    <li>
                        <h4 class="mt30"><b>What advantages do I get by booking in advance?</b></h4>
                        <p>There are many advantages to book in advance</p>
                        <ul>
                            <li><b>Peace of mind</b> ... we get to work in arranging a vehicle. Get a completely transparent, hassle-free reservation with Gozo</li>
                            <li><b>No risk</b>...you can make a 'pay later' reservation and then pay online when plans firm up</li>
                            <li>In many cases, our trip concierges can be of help in recommending you places to stay, things to do and offer you additional resources for your vacation. we have an expansive network of travel operators who you can use as a resource. You can simply write to us at traveldesk (AT) gozocabs (dot) com</li>
                            <li>Our bookings are also backed by our <a href="http://www.aaocab.com/price-guarantee" target="_blank">'best price guarantee'.</a></li>
                        </ul>
                    </li>
                    <a name="faq19"></a>
                    <li>
                        <h4 class="mt30"><b>What car categories do you provide?</b></h4>
                        <p>We provide 4 types of cars :-</p>
                        <ul>
                            <li>Compact  - includes models like Indica and Indigo</li>
                            <li>Sedan cars - includes models like Etios, D’zire</li>
                            <li>Family cars/SUV - includes models like Xylo, Tavera</li>
                            <li>Family Lux/SUV - Innovas</li>
                        </ul>
                        <p>Additional services like tempo traveller or buses can be arranged upon request, simply call our customer service help line. In many cases, additional requests for a car with carrier will cost extra.</p>
                    </li>
                    <a name="faq20"></a>
                    <li>
                        <h4 class="mt30"><b>What if Driver asks me for extra payment? and I do not agree...</b></h4>
                        <p>Normally the driver will not ask you for any payment beyond the amount that is listed in your booking confirmation. </p>
                        <p>If the driver asks you for additional payment claiming extra kms driven or tolls or parking charges, please always ask the driver to provide a 'duty slip' with the details of the payment being requested.</p>
                        <p>If you feel that the charges being requested are not justified, you may call our service center and ask them to resolve the situation. <br>
                        If you end up having to pay the driver but do not agree with the charges, please take a picture of the 'duty slip'  detailing the payment and send it to our team at info@aaocab.com and include your booking confirmation. Our team will address the issue with the driver / taxi operator and help resolve the situation. 
                        </p>
                    </li>
                    <a name="faq21"></a>
                    <li>
                        <h4 class="mt30"><b>What if my cab shows up late?</b></h4>
                        <p>We are proud of our on-time performance but sometimes delays do happen.</p>
                        <p>If the nature of your booking is time-sensative ...involving an airport pickup/drop or meeting at your destination, please budget for additional travel time (usually add 30mins for traffic delays for every 2 hours of estimated travel time) and also let us know of this as a special request when making your reservation.</p>
                        <p>We will make additional efforts to ensure that we're vigilant and ensure a safe & punctual transit for you.</p>
                        <p>if for any case, your cab is delayed and you have to cancel your reservation we will issue a full refund of any payment that you may have made in the form of advance towards the taxi reservation</p>
                    </li>
                    <a name="faq22"></a>
                    <li>
                        <h4 class="mt30"><b>What if my taxi does not show up?</b></h4>
                        <p>It is extremely rare to have a situation where the vehicle does not show up at the scheduled time. Such an event is generally the result of unavoidable circumstances like inordinate amount of traffic, heavy rains, traffic blockades or vehicular breakdowns to name a few.</p>
                        <p>In such situations, we usually are notified by the driver and taxi operator at their first opportunity. We in turn do our part in keeping the customer aware of the situation as soon as we find out. </p>
                        <p>If you choose to cancel the trip - due to a delay on our part, we will waive off last minute cancellation fees. We earnestly work to avoid any such delays and work to ensure a 100% on-time performance. If you do have such an experience, we will work to make it up to you and earn your business for the future. </p>
                        <p>As always, your feedback is welcome. Write to our customer-advocacy team at customeradvocacy [AT] gozocabs {DOT} in and we will act on your feedback so we continue to get better every time.</p>
                    </li>
                    <a name="faq23"></a>
                    <li>
                        <h4 class="mt30"><b>What if the cab breaks down during the journey</b></h4>
                        <p>All our taxi's are regularly inspected along over 30 different points.</p>
                        <p>However, breakdowns cannot be anticipated and do happen. In those cases, we expediently arrange a backup cab to ensure that your travel plans continue uninterrupted and with the least possible delay.</p>
                    </li>
                    <a name="faq24"></a>
                    <li>
                        <h4 class="mt30"><b>What is the earliest I should make a reservation with Gozo?</b></h4>
                        <p>You may reserve with Gozo at anytime. <br>
                        We are open 24x7 and our platform automatically gets to work for arranging cabs - no human intervention required.
                        </p>
                        <p>However, the shorter the notice, fewer the inventory choices and tighter the inventory in the market. While Gozo has the largest and most expansive network of inter-city taxi across India , our ability to get you the highest quality and best pricing gets diminished as the journey date gets closer.</p>
                        <p>As a best practice, make a reservation with us as quickly as you know of your plans. There is no charge to cancel a booking as long as you cancel more than 24hours in advance. We normally accept reservations upto 6months in advance, with most our customers sharing their plans with us at least 3 months ahead.</p>
                    </li>
                    <a name="faq25"></a>
                    <li>
                        <h4 class="mt30"><b>Will Gozo drivers take detour per our request?</b></h4>
                        <p>We are happy to support your requests for adding additional pickups, waypoints, stopovers or drop addresses.</p>
                        <p>However these changes mean that your itinerary needs to be updated and such changes may translate into a change in your quoted prices. </p>
                        <p>Every additional pickup, drop, waypoint or stop over is typically charged at Rs.150/-.</p>
                        <p>All your trip updates will need to be communicated to our service center or you can make the trip updates in our app directly. The driver  must receive an official trip update message from Gozo Cabs. The driver will take a detour only after an official update to the trip/itinerary has been made.</p>
                    </li>
                    <a name="faq36"></a>
                    <li>
                        <h4 class="mt30"><b>we will be landing at Bangalore airport and then want to go Cochin? Should I prebook my car or book after landing?</b></h4>
                        <p>Normally it takes us between 1-2hours to arrange a car. We suggest pre-booking ahead of time so it gives an opportunity to arrange a car and driver for your trip.</p>
                    </li>
                    <a name="faq37"></a>
                    <li>
                        <h4 class="mt30"><b>Do you provide an English-speaking driver?</b></h4>
                        <p>We do try our best to provide a English speaking driver if the request is received on your booking under the additional requests section ahead of time. This is generally subject to availability of a English-speaking driver. If you are not a resident of the region, we suggest that you install Google Translate on your phone. Using the apps voice transcription features, you can speak in your native language and the app would translate it into spoken words of the language of your choice.</p>
                    </li>
                    <a name="faq50"></a>
                    <li>
                        <h4 class="mt30"><b>I left something in the Gozo Cab, how do I report the lost item so you can help me get it back.</b></h4>
                        <p>We’re sorry that you left your belonging in the car. We will try our to search for the item and if found we will contact you on how to best get it back to you. Typically the item will be sent to you by post at your cost of the courier service. Please go to <a href="http://support.aaocab.com/open.php">http://support.aaocab.com/open.php</a> and open a new ticket, use help topic lost and found. This will get us what we need and we can start a lost & found inquiry for you.</p>
                    </li>
                    <a name="faq26"></a>
                    <li>
                        <h4 class="mt30"><b>How can I cancel my booking with Gozo?</b></h4>
                        <p>You may cancel your booking at anytime. <br>
                        Bookings cancelled within 24hours of pickup time will incur a cancellation fee that will be charged to your account. If you cancel your booking greater than 24hours before your pickup time, the booking may be cancelled at no charge.
                        </p>
                        <p>Please DO NOT CALL the helpdesk / reservations desk for cancelling bookings as these bookings cannot be cancelled by the representatives. You need to <a href="http://www.aaocab.com/login" target="_blank">login to your account</a> to cancel your booking.</p>
                    </li>
                    <a name="faq27"></a>
                    <li>
                        <h4 class="mt30"><b>How can I get a bill/receipt for my trip?</b></h4>
                        <p>Invoices are automatically generated and sent to you by email for every trip that you complete with us. <br>
                            If you have lost your invoice email or cannot find it your invoice can  be retrieved by logging in at <a href="http://www.aaocab.com/" target="_blank">www.aaocab.com.</a> <br>
                            Go into the My bookings section, select history and you can generate the invoice/receipt for any past trip.
                        </p>
                    </li>
                    <a name="faq28"></a>
                    <li>
                        <h4 class="mt30"><b>How many people can travel per taxi?</b></h4>
                        <p>Our seating configurations are listed when you are making a reservation. We typically list seating capacity as X +1 where X = number of passengers and the +1 is the chauffeur / driver.</p>
                    </li>
                    <a name="faq29"></a>
                    <li>
                        <h4 class="mt30"><b>I have done my booking by phone, is toll included in the costs?</b></h4>
                        <p>Your booking confirmation is clear on all the items that are included in your trip quotation. Any city that you plan to visit should be explicitly listed in your trip plan. While we put all efforts to provide you quotes that are inclusive of toll tax, state tax and other charges for all one-way drop.
</p>
                        <p>For round-trip or multi-city trips we will exclude this items from the quotation. 
In anycase, your booking confirmation (booking contract) provides complete transparency on what is included in your trip plan and quote.</p>
                    </li>
                    <a name="faq30"></a>
                    <li>
                        <h4 class="mt30"><b>Is Toll tax included or excluded from my Total fare?</b></h4>
                        <p>Toll-Tax is charged by NHAI & respective authorities for using National & State Highways.</p>
                        <p>Your booking confirmation email clearly details out if Toll taxes are included for your defined itinerary or if they are excluded from your total fare. When Toll taxes are stated as excluded from your trip confirmation email, the guest/customer is expected to pay the toll taxes at the toll booths encountered on your journey. When listed as included, the toll tax is prepaid by the customer as part of their total fare. </p>
                        <p>For one-way trips, your travel route is well defined and known to us. So as a convenience to our guests/customers we include the Toll tax amount in your booking fare. As a result you do not need to pay anything extra - driver will take care of the toll tax payments as long as you are traveling as per the plans defined on your itinerary (listed in your booking confirmation). </p>
                    </li>
                    <a name="faq31"></a>
                    <li>
                        <h4 class="mt30"><b>We have elderly travelers in the vehicle?</b></h4>
                        <p>We encourage you to make a note in your reservation under the additional requests section. We try our best to accomodate all additional requests but cannot commit to it for each reservation. This is why having your reservation and additional requests ahead of time helps us make the proper arrangements.</p>
                    </li>
                    <a name="faq32"></a>
                    <li>
                        <h4 class="mt30"><b>What if I have a lot of luggage to be carried during the journey?</b></h4>
                        <p>When making the reservation, please be clear on your requirements. You have the option to specify number of passengers, number of small and large luggage pieces and any special request. Our goal is to provide you with a quality service at a fair price.</p>
                        <p>Information you provide us enables us to find the right vehicle that matches your requirements.</p>
                        <p>When you request a vehicle a vehicle with carrier, we try our best to arrange it but may not be able to sometimes. A vehicle with carrier will also imply an additional cost of Rs. 150/- per day of the trip.</p>
                    </li>
                    <a name="faq33"></a>
                    <li>
                        <h4 class="mt30"><b>What is the best way to make a booking so I can get the best quality and good price?</b></h4>
                        <p>We always recommend that you book your trip directly using our app or website. Our best prices are available on our mobile app.</p>
                        <p>Get our app at <a href="https://play.google.com/store/apps/details?id=com.gozocabs.client&hl=en" target="_blank">https://play.google.com/store/apps/details?id=com.gozocabs.client&hl=en</a></p>
                        <p>You can pay in advance to reconfirm your booking. If you choose to 'pay later' you will be charged a 'collect on delivery' (COD) fee. Also please be sure to reconfirm your booking in a timely manner. Unconfirmed bookings are subject to automatic cancellations. </p>
                    </li>
                    <a name="faq34"></a>
                    <li>
                        <h4 class="mt30"><b>Are there any waiting charges?</b></h4>
                        <p>There are no waiting charges up to first 30 minutes during the trip. </p>
                        <p>At pickup time, your cab will wait for a maximum of 30 minutes. If requested to wait longer (provided the cab driver is able to wait) you will be responsible for waiting charges at the rate of Rs.120/hour.</p>
                    </li>
                    <a name="faq35"></a>
                    <li>
                        <h4 class="mt30"><b>Do I need to pay any extra amount apart from the one listed here on website?</b></h4>
                        <p>No, the amount listed on the website is the final amount you will need to Pay. Our quotes are commpletely transparent and we list all the applicable charges upfront. Most our trip quotes are inclusive of the toll tax, state tax and other charges.</p>
                        <p>For all quotations, Parking charges are NOT included and the guest will be liable to pay charges for parking wherever applicable.<br>
                        Parking charges are typically Rs.150-300 in the case of most airport pickups.
                        </p>
                    </li>
                    <a name="faq38"></a>
                    <li>
                        <h4 class="mt30"><b>What is Gozo Flexxi SHARE?</b></h4>
                        <p>Gozo Flexxi SHARE is our way to help you to save money for your one-way rides. If you are willing to share your cab with others, you simply book a car for yourself and then offer it to other travelers as a Gozo Flexxi SHARE ride. By doing this you are saying – “I’m going for sure. If others can ride with me, I will save money. If not, I’m going anyways”</p>
                    </li>
                    <a name="faq39"></a>
                    <li>
                        <h4 class="mt30"><b>How does Gozo Flexxi SHARE work?</b></h4>
                        <p>If you are paying for a full car and not using it, why not offer 1 or more seats to others to share the cost. Gozo will list your cab ride as a Gozo Flexxi SHARE ride.</p>
                        <p>Once you offer your cab booking as a Gozo Flexxi SHARED ride, we will start to find other riders to share the ride with you. You become a Gozo Flexxi SHARE Promoter.</p>
                        <p>You are responsible for the full booking amount to Gozo. As we find other riders, we will continue to reduce your fare.</p>
                        <p>Gozo will take care of taking payment from the Gozo Flexxi SHARED riders. Gozo Flexxi SHARED riders pay for their seats in full and can cancel upto 36hours in advance for a full refund.</p>
                        <p>On the day of the trip, the Gozo Flexxi SHARE promoter gets picked up at their address. All Gozo Flexxi SHARED riders are picked up at a fixed defined location that will be provided to you after picking up the Gozo Flexxi SHARE promoter.</p>
                        <p>Upon reaching the destination, all riders are dropped at their destination address.</p>
                        <p>Everyone saves money with Gozo Flexxi SHARE.</p>
                    </li>
                    <a name="faq40"></a>
                    <li>
                        <h4 class="mt30"><b>What is the benefit for Gozo Flexxi SHARE riders?</b></h4>
                        <p>You can buy a unused seat in a AC taxi that's hired by someone else, all at the price of less than a bus ticket. So if you are looking to travel to a specific destination for a real cheap price and have flexible plans then simply setup an alert for a Gozo Flexxi SHARED seat and buy it as soon as one that matches your needs becomes available</p>
                    </li>
                    <a name="faq41"></a>
                    <li>
                        <h4 class="mt30"><b>What is the benefit for Gozo Flexxi SHARE promoters?</b></h4>
                        <p>If you are renting a taxi to a particular destination and have empty seats in your hired taxi you can offer those unused seats to other riders and make money. As the seats you offer are purchased by other riders, we will give you a credit which can be applied towards your booking or can be redeemed for future use in Gozo coins.</p>
                    </li>
                    <a name="faq42"></a>
                    <li>
                        <h4 class="mt30"><b>Can I pay the driver cash?</b></h4>
                        <p>No, for Gozo Flexxi SHARED bookings you must pay your portion of the payments in advance. Both Gozo Flexxi SHARE promoters and riders pay for their seats in advance of the trip.</p>
                    </li>
                    <a name="faq43"></a>
                    <li>
                        <h4 class="mt30"><b>As the Gozo Flexxi SHARE promoter, Can I reschedule my trip time?</b></h4>
                        <p>Once a Gozo Flexxi SHARE trip is scheduled and Gozo Flexxi SHARE riders have purchased their seats, it's not going to be possible to reschedule the trip to avoid inconveniencing the co-travellers.</p>
                    </li>
                    <a name="faq44"></a>
                    <li>
                        <h4 class="mt30"><b>I'm a Gozo Flexxi SHARE promoter, can I get cash back instead of getting credit applied to my booking amount?</b></h4>
                        <p>Sorry. As a Gozo Flexxi SHARE promoter, your price for the booking keeps going down as your infused seats are sold to Gozo Flexxi SHARED riders. We offer you the option to get this credit spoiled to your booking amount or to be added to your account in the form of Gozo coins which you may redeem towards future travel</p>
                    </li>
                    <a name="faq45"></a>
                    <li>
                        <h4 class="mt30"><b>I have booked a Gozo Flexxi SHARED seat, how long will the car wait for me?</b></h4>
                        <p>You will be given a 15minute time-window to join the other Gozo Flexxi SHARE travelers at the pickup point. You will also have the drivers phone number so you can coordinate with him as well. The car will wait for you only during that pickup time window given to you.</p>
                    </li>
                    <a name="faq46"></a>
                    <li>
                        <h4 class="mt30"><b>What is the cancellation policy for Gozo Gozo Flexxi SHARE?</b></h4>
                        <p>You may cancel your Gozo Flexxi SHARE ticket anytime 24hours before start of you trip. If you cancel within 24hours of your pickup, cancellation charges apply. If the trip is cancelled within the last 12hours of your pickup time, you may cancel with no refund. Please refer to Gozo Flexxi SHARE Terms & Conditions for the terms of your booking.</p>
                    </li>
                    <a name="faq47"></a>
                    <li>
                        <h4 class="mt30"><b>Why do I have to sign in with my Facebook profile when booking a Gozo Flexxi SHARE?</b></h4>
                        <p>We require that you use your own social media account to login when booking a Gozo Flexxi SHARE. We use this to inform Gozo Flexxi SHARE riders about who their co-riders are going to be. At the time of start of the trip, the driver or co-riders may require you to provide your identification. If you fail to identify yourself, you may be prohibited from continuing with your ride and shall not be eligible for a refund.</p>
                    </li>
                    <a name="faq48"></a>
                    <li>
                        <h4 class="mt30"><b>What are the other terms and conditions for booking a Gozo Flexxi SHARE?</b></h4>
                        <p>Full terms and conditions for Gozo Flexxi SHARE are listed at <a href="http://www.aaocab.com/terms-GozoFLEXXI" target="_block">www.aaocab.com/terms-GozoFLEXXI</a></p>
                    </li>
                    <a name="faq49"></a>
                    <li>
                        <h4 class="mt30"><b>How do you collect toll from every person in the shared taxi?</b></h4>
                        <p>The toll and state tax amount is split across all the travelers in the shared cab. So with 4 travellers, the toll is divided by 4, but if there are fewer than 4 travelers your share of the toll and state tax will be higher.</p>
                    </li>
                    <a name="faq51"></a>
                    <li>
                        <h4 class="mt30"><b>Are pets allowed?</b></h4>
                        <p>Yes small pets are allowed but you will need to specify this at the time of making a booking and this is added to the additional instructions / requirements for your trip. There may be a pet cleaning fee that might be charged by the taxi operator depending on where and how long you are traveling. Also, Pets may not be allowed in small cars (depends on your route of travel). Best if you call us.</p>
                    </li>
                    <a name="faq52"></a>
                    <li>
                        <h4 class="mt30"><b>Why does GozoCabs only request reviews to be put on Google & TripAdvisor and not any other website?</b></h4>
                        <p>As a company policy, We do not respond to reviews or complaints on any other forum outside of GozoCabs or Google or TripAdvisor as we have no way to confirm that those are from real users or real feedback. We shall respond to reviews only on these 2 external sites as we know these sites have strong policies against fake reviews. We do not respond to reviews that are posted on sites other than Google or TripAdvisor.</p>
                        <p>Gozo is making a huge difference in the industry with its service and there are some people who do not like what we are doing in terms of offering good quality, transparency and at great prices.</p>
                        <p>GozoCabs is very focused on delivering a solid user experience and we are very proactive about Customer service. Any customer who has a service complaint or appreciation about us is requested to directly contact or customer advocacy team first at GozoCares [at] GozoCabs [dot] in or if you are not satisfied with our response, you can post your reviews on Google or TripAdvisor. Our customer accuracy team will take care of your needs and ensure we address the issues reported.</p>
                    </li>
					<a name="faq53"></a>
                    <li>
                        <h4 class="mt30"><b>Why has GozoCabs boycotted the use of MouthShut.com?</b></h4>
                        <p>In our interactions we have found various websites, particularly Mouthshut.com to be filled with biased reviews targeted at getting Gozo to work in a "pay to play for better reviews" model. We are do not endorse such practices and have boycotted such websites. We have had direct experience particularly with MouthShut.com where we have been invited to "pay" to get our reputation "fixed" on Mouthshut.com.</p>
                        <p>We work hard to treat our customers well and prefer to show you the real picture as seen on unbiased platforms on Google & TripAdvisor.</p>
                    </li>
                </ol>
            </section>
        </article>

<script>
// When the user scrolls down 20px from the top of the document, show the button

window.onscroll = function() {scrollFunction()};
function scrollFunction() {
    
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("btnTop").style.display = "block";
    document.getElementById("btnBottom").style.display = "block"; 
    
  } 
  else {
    document.getElementById("btnTop").style.display = "none";
    document.getElementById("btnBottom").style.display = "none";
  }
  if(document.documentElement.scrollTop >= 9345)
  {
     document.getElementById("btnBottom").style.display = "none"; 
  }
}
// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
function bottomFunction()
{ 
    var percentageToScroll = 82;
    var percentage = percentageToScroll / 100;
    var height = $(document).height() - $(window).height();
    var scrollAmount = height * percentage;
    //alert("aa="+scrollAmount);
    jQuery("html, body").animate({
        scrollTop: scrollAmount
    }, 900);
}
</script>
