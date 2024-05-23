<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		body{ font-family: 'Arial'; font-size: 12px; line-height: 20px; margin: 0; padding: 0;}
		.gray-color{ color: #aeaeae;}
		p{ font-size: 12px;}
		h1{ font-size: 22px; line-height: 22px;}
		h2{ font-size: 16px; line-height: 22px; font-weight: normal;}
		h3{ font-size: 15px; line-height: 22px;}
		.text-center{ text-align: center;}
		.main-div{ width:720px; margin: auto; font-size: 12px!important;}
		.border-bottom{ border-bottom: #ededed 1px solid;}
		.list_type li{ padding: 8px 0;}
		.list_table table{ width: 100%;}
		.list_table td{ padding: 8px; border: #d3d3d3 1px solid; font-family: 'Arial'; font-size: 12px; line-height: 20px;}
		.blue-color{ color: #1a4ea2;}
		.orange-color{ color: #f36c31;}
		a{ color: #f36c31;}
		@media (min-width: 320px) and (max-width: 767px) { 
			.main-div{ width:94%!important; margin: auto!important; font-size: 12px!important;}
		}
	</style>
</head>
<div class="main-div">
    <h3><b>PLEASE READ THIS AGREEMENT AND ACCEPT IT TO PROCEED</b></h3>
    <p>This Agreement (the &ldquo;Agreement&rdquo;) is effective on and from <?= date("d/m/Y", strtotime(DATE('Y-m-d'))); ?> </p>
	<h2><b>BETWEEN:</b></h2>
	<p><b>M/S. GOZO TECHNOLOGIES PVT. LTD.</b> (the "Company" or "Gozo" or "GozoCabs"), a Company incorporated under the Companies Act, 1956 and having its Corporate Office at <?= $address ?>, hereinafter referred to as the "First Party" (which expression shall unless repugnant to the context or meaning thereof include his heirs, executors, administrators, legal representatives and assigns) of the one part, represented by <b>Gozocabs</b></p>
	<h2><b>AND</b></h2>
	<p><b><?= $data['ctt_business_name']; ?></b> (the "Operator") a Proprietorship/ Partnership/ Company, having its principal place of business at <b><?= $data['ctt_business_name']; ?></b> hereinafter referred to as the "Second Party" (which expression shall unless repugnant to be the context or meaning thereof include his heirs, executors, administrators, legal representatives and assigns) of the second part, represented by <b>Mr. <?= $data['name']; ?></b></p>
	<p><b>WHEREAS</b> the Company is inter-alia engaged in the business of provision of tour and travel related services through various platforms, including its website(s), communication systems like phone or SMS, customer service & call centers, customer and vendor management systems, support ticketing systems and across its web and mobile applications (hereinafter referred to as the "Platform"). The Company makes available a platform for matchmaking "consumers" seeking travel services from and to various destinations across India with "Operators" who can provide such travel and transportation services.</p>
	<p><b>WHEREAS</b> the Operator is inter-alia engaged in the business of operating and providing taxi-based transportation services (hereinafter referred to as "Cab Service")</p>
	<p><b>WHEREAS</b> the Operator desires to receive leads of consumers (hereinafter referred to as the "Customers" or "Consumers") who may avail its Cab Service through Company's Platform and the Company allow Operator to use its Platform on the terms and conditions stated herein and mutually agreed between the Parties.</p>
	<p><b>And</b> that the below definitions would prevail for the Terms used in the Agreement</p>
	<h2 class="blue-color"><b>1.	Definitions</b></h2>
	<ol type="A" class="list_type">
		<li><b>GozoCabs</b> or <b>Gozo</b> is a technology Company that makes available a marketplace platform for matchmaking "consumers" seeking travel services from and to various destinations across India with "Operators" who can provide such travel and transportation services;</li>
		<li><b>Customer, Consumer or Lead source</b> - An individual or organization that seeks services made available by independent third-party Operators through the Gozo Platform. Such an individual or organization is a customer on the Gozo Platform. A customer seeks services either for their own use or use by another. However, a customer may or may not be the traveller (actual user of the service).</li>
		<li>Traveller - A traveller is the person(s) consuming the service assigned to and being delivered by the Operator. The traveller may be the Customer (as defined above) or person(s) authorized by the Customer.</li>
		<li><b>Operator, Vendor, Provider, Partner or Third-Party Provider</b> - An individual or organization that operates as an independent third-party and uses or interacts with the Gozo platform to receive leads for travellers that can avail of the operator's taxi transportation service or product. An Operator or Provider Partner is an independent third-party who has a contractual relationship with Gozo and delivers its services lawfully. An Operator is NOT an employee of Gozo.</li>
		<li><b>Booking, Lead or Trip Request</b> - A request for travel service that is presented on the Gozo platform and is available to be assigned to an Operator on the Gozo platform.</li>
		<li><b>Services or Travel Services</b> - Gozo provides technology oriented matchmaking services through its platform. Consumers and Operators can interact with Gozo platform through the use of applications, websites, content, products like SMS or phones. Collectively these are referred to as Services.</li>
		<li><b>Assigned booking or Accepted lead or Trip</b> - A lead created on the Gozo Platform is offered to Operators from time to time. At the time of being offered, the relevant terms and conditions for the lead are presented to the Operator. The Operator may accept or reject such a lead. Upon being accepted by the Operator, such a lead is considered to be an Assigned booking or Accepted lead or a Trip. Trip start time - Time at beginning of trip at which a traveller is first picked up for the trip.</li>
		<li><b>Approved vehicle </b>– An approved vehicle is one for which all paperwork has been submitted to Gozo and has been approved by Gozo to be used on a specific Gozo Operator account. For a vehicle to be approved by Gozo, said vehicle must be either a vehicle that is owned by the Operator (ownership proof provided to Gozo) or one for which that Operator has presented Gozo with a copy of a Letter of Undertaking whereby the vehicle owner has granted exclusive authority to the Operator to operate said vehicle.</li>
		<li><b>Approved driver </b>– An approved driver is a person for whom all required paperwork has been submitted to Gozo and has been approved by Gozo to deliver a service on behalf of the Gozo Operator account.</li>
	</ol>
	<p>NOW, THEREFORE, IT IS HEREBY AGREED as follows:</p>
	<h2 class="blue-color"><b>2.	Representation & Warranties</b></h2>
	<ol type="A" class="list_type">
		<li>Operator represents and warrants that the Operator possesses, and shall possess throughout the subsistence of this agreement, the necessary infrastructure, licenses, skills and expertise to provide its service on the Company's Platform and to timely perform its obligations under the bookings thus made by the Customers.</li>
		<li>Operator represents and warrants that it has valid and subsisting certifications, affiliations, licenses, permissions and approvals as required to be taken from the relevant authorities for the performance of its obligations and that it shall comply with all applicable laws, rules and regulations, and the amendments thereof. Operator further represents that it has undertaken all checks and compliances required by regulatory authorities for providing and operating cab services including verification of documents of vehicles and drivers used by the Operator. Operator further represents that all vehicles being operated by the operator and attached with the Company's Platform are being operated lawfully including but not limited to being either owned directly by the operator or are leased by the operator from the owner of the vehicle.</li>
		<li>GozoCabs represents that it regularly enters into agreements with various third-parties including but not limited to other marketplace platforms, corporate clients, travel agencies, individuals or organizations to receive leads of consumers seeking Travel Services. Said leads are assigned to Operators that interact with the Gozo platform.</li>
	</ol>
	<h2 class="blue-color"><b>3.	Registration of Operator & Use of platform</b></h2>
	<ol type="A" class="list_type">
		<li>Operator shall register on the Platform by providing requisite information for the purposes of completing the registration requirements set by Company either online or by providing the information in writing to the Authorised Representative of the Company.</li>
		<li>The required information may include details of the Operator, owner, company name, registration details, contact details, bank account details, details of Vehicles, area of operations, packages, rates, policies, condition and model of Vehicles, valid copy of license, address proofs, Vehicle documents, driver details, Know Your Customer ("KYC") documents etc.</li>
		<li>Gozo has the right to reject any one or more of the KYC documents submitted by Operator and may ask for other documents or further information.</li>
		<li>Upon any change, the Operator must update such information or data and documents in writing with the Platform within reasonable period of time.</li>
		<li>After verification, Operators account shall be activated on the platform. The information related to admin account including username and password shall be treated as Confidential Information and may not be shared or transferred to anyone. Operator shall not authorize third parties to use his Gozo Operator Account with the Company and neither shall he assign or otherwise transfer his Gozo Operator Account to any other person or entity.</li>
		<li>Operator shall provide details of Vehicle(s), price, rates etc. from time to time in accordance with the instructions or Policies of Company.</li>
		<li>Operator shall be informed by the Platform, at its sole discretion, about new trip requests or any changes or updates to existing trip requests either by SMS, mobile app notifications, written communications through the platform or the Service Provider may access information online from his account on the Platform.</li>
		<li>Operator agrees and acknowledges that the decision to send a booking to the Operator shall be decided on a variety of factors and Company shall not be liable if the Operator is not offered every booking that is received on the Platform. The factors determining the booking assignment may be changed by Company based on various attributes and proprietary logic as implemented by the Company.</li>
	</ol>
	<h2 class="blue-color"><b>4.	Operators Obligations</b></h2>
	<ol type="A" class="list_type">
		<li>Operator shall comply by the terms and conditions of this Agreement including the Service Level Document appended herewith as Annexure I. The Service Level Document along with any future amendments is made a part of this Agreement by reference and any violation of the terms of the Service Level Document shall be deemed to be a violation of this Agreement granting Company the right to terminate this Agreement forthwith. The Operator shall be solely and fully responsible for any consequential loss, damage, costs or liability and/or proceedings arising from non-performance of their obligations under this agreement.</li>
		<li>The Operator agrees and acknowledges that it has read and clearly understood Annexure I. The Operator also has selected an Operator Relationship Tier / Level in Annexure - I and agrees to comply with the applicable terms and conditions for the "Operator Relationship Tier/Level" selected by the Operator.</li>
		<li>Operator agrees and acknowledges that he shall not be entitled to raise a query or complaint regarding any booking after expiry of 7 calendar days (day 1 means the day of completion of the trip), which the Operator hereby acknowledges to be a reasonable period of time to resolve any dispute in that respect.</li>
		<li>Operator shall immediately inform the Company of any information that is likely to impact the Service or services thereof; however, such communication of information shall not absolve the Operator from its other obligations undertaken under this Agreement.</li>
		<li><b>Written and trackable communications:</b> If the Operator is to receive any instruction, trip request or any communication bearing official purpose from any employee of the Company or person claiming to be associated with the Company in any non-written form, the Operator MUST require that such communication be received officially via the Platform. In lieu of receiving such instruction via the Platform, the Operator may accept such instructions only if sent via the Company's official email address. Operator must retain such communication for documentation purposes.</li>
		<li><b>Responsibility for performance:</b> Operators shall be responsible for timely performance of its obligations to the Customers under the booking. Gozo reserves the right to withhold payments to the Operator and levy a penalty for each such incident as listed in Annexure I. Operator understands that Company is ONLY providing a marketplace for services and assumes NO responsibility or liability for the actions or omissions of the Operator including non-adherence of the scheduled timings, behaviour of the Operator's staff, conditions inside the cab, loss of life or property, delay, breakdown or inconvenience suffered by the Customers. Company shall be entitled to publish as such to Customers.</li>
		<li>Operator shall not represent itself as acting on behalf of the Company to any person or entity. Operator shall also NOT represent that the Cab Service are products/ services offered or sold by the Company.</li>
		<li>Operator shall be solely responsible for claims, damages or proceedings arising out of or relating to the Service and its use of the Gozo Platform as a result of this agreement. In case of any unforeseen circumstances like an accident resulting in a physical injury to travellers, driver or the cab, the complete responsibility will be of the Operator. Any claims, losses, liability, expenses resulting as a result thereof shall be the sole responsibility of the Operator.</li>
		<li><b>Unauthorized use of Operator Account:</b> If the Operator learns of any unauthorised use of its credentials for accessing the Platform, it must notify the Company immediately by email at vendor@gozocabs.in or by phone at the Company's operator relations phone number.</li>
		<li><b>Use of unregistered or unapproved drivers and/or vehicles:</b> Only the Operator itself & the Operator's approved Drivers are authorized to deliver Services on behalf of its Gozo Operator Account with the Company. The Operator hereby agrees that it shall absolutely not delegate, sell, advertise to sell, subcontract or assign their responsibility to deliver Services to any third party including but not limited to another Operator registered on the Gozo platform. Use of unregistered drivers or vehicles (those which are not an approved driver or not an approved vehicle) is considered a violation of this agreement and shall be deemed as grounds for termination of this contract. In addition, Gozo reserves the right to withhold payments to the Operator and levy a penalty for each such incident as listed in Annexure I. In addition, the Operator shall be held responsible and liable for any damages, legal actions or consequences resulting from non-compliance to these requirements.</li>
		<li><b>Use of pre-approved vehicles and drivers only:</b> The Operator shall only deploy vehicles and drivers that are owned and/or in regular service of the Operator and for which all relevant documents are confirmed to be valid and compliance with applicable laws by the Operator. Such document must be duly submitted and approved on file with the Company. A vehicle or driver may not be used for service unless it has received proper approval from the Company(approved driver and approved vehicle as defined hereinabove). In the event that a vehicle being deployed by the operator is not owned by the Operator, the Operator agrees to and is required to submit a undertaking document indicating that the Operator is authorized by the vehicle owner to operate the vehicle.</li>
		<li><b>Driver documents and Background check:</b> Operator shall ensure that all its Drivers registered with Gozo are law abiding citizens of the country, have a valid Commercial Driving License, Photo Identity Proof & Address Proof. The Operator must also ensure that the background and history of the Drivers being used to deliver Travel Services have been adequately checked and with no record of criminal behaviour. The Operator undertakes and represents that all drivers engaged by the Operator in delivering services under this agreement are issued a valid Police Verification Certificate. The Company reserves the right to seek a copy of such documents as and when necessary.</li>
		<li><b>Documentation to be carried in vehicle:</b> Operator shall ensure that all the vehicles used in providing Travel Services are having required Registration certificate, Insurance, Pollution under control certificate, Commercial Taxi License, Vehicle fitness certificates, Interstate Permits and all such relevant documents as required by law. It is the Operator's sole responsibility to ensure such compliance, present the relevant documents to Gozo for approval and ensure these are carried in the vehicle all the time. Any failure to comply or any violation of such requirements would be deemed as grounds for immediate termination of the relationship. The Operator shall be held responsible and liable for any damages or consequences resulting from non-compliance to these requirements. In addition, Operator shall maintain clear and up-to-date purchase/lease/financial documents with respect to the Vehicles used in providing Travel Services.</li>
		<li><b>Commercial licensed vehicles:</b> The Operator hereby agrees that he shall use ONLY commercially licensed vehicles for serving trips assigned by the Company. Any use of private vehicles is illegal and shall be deemed as breach of the terms and sufficient grounds for termination of the relationship. Operator shall be solely and exclusively responsible and liable for any damages, legal actions or consequences resulting from use of non-commercial vehicles.</li>
		<li><b>Well maintained vehicles:</b> The Operator agrees to adhere to quality standards with respect to vehicles used to serve trips assigned by the Company. It agrees to only use vehicles which are no more than 3 years old and have not been driven for more than 1,00,000 kms. as of the date of start of trip. It also agrees to ensure that every vehicle used in Service shall have a well-maintained AC and in a clean and pleasant-smelling condition as of the date of start of trip.</li>
		<li><b>Timely updates of trip cancellations by the Customer:</b> Operator must notify the Company of any cancellations of trips by the customer within 24 hours of scheduled start of such trip, else it shall be presumed that the trip was duly completed, and the Operator shall be liable to pay due charges to the Company for the given trip.</li>
		<li><b>Agreement to serve:</b> Once a lead is accepted by an Operator, the Operator undertakes responsibility of assigning a vehicle and driver to the trip irrespective of the advance payment status of the trip, whether paid fully/partly in advance or payment to made to the driver upon completion of service.</li>
		<li><b>No decline / cancellation after acceptance:</b> It is unacceptable that an operator declines or refuses a trip that was previously accepted by it. Cancellation of a trip by the Operator within 24 (twenty-four) hours of the scheduled trip start time is absolutely unacceptable and shall lead to negative ratings for the Operator on the Platform. In the event, an Operator cancels a previously accepted trip less than 4 (four) hours before the scheduled trip start time, the operator shall also be liable for a penalty as listed in Annexure I. This amount shall be applied to the Operators account statement for the given period.</li>
		<li><b>No change of assigned Vehicle and/or Driver:</b> Any change in assigned vehicle or driver MUST be notified to the Company immediately. If the vehicle or driver showing up to serve the customer does not match the information provided to the Company, this shall result in a penalty to your account as listed in Annexure I and may be considered grounds for termination of relationship.</li>
		<li><b>Additional charges:</b> If any additional charges are to be levied to the customer which are incidental to the trip, and not covered in the Booking Amount payable by the Customer, the Operator and / or the driver must notify and confirm the charges with the Company first before requesting the customer for such additional charges. The Company may be notified via the Platform (partner or driver mobile app) or via the helpdesk phone line. Once the additional charges are approved by the Company, the amount may be requested by the customer.</li>
		<li><b>Commitment to quality service:</b> Gozo receives service quality feedback for each trip from its customers. All quality reviews received will be shared with the operator. The Operator is required to take corrective action and update Gozo about the action being taken to address any poor-quality complaints.</li>
		<li><b>Non-solicitation:</b> Operator shall not solicit the Customers of the Company to book directly through the Operator, bypassing the Company and / or its marketplace Platform.</li>
		<li><b>Abide by the law:</b> That the Operator agrees to comply with all applicable laws when delivering services arising out of this agreement, and that it shall only use the Platform for lawful purposes. </li>
		<li><b>Immediate notification:</b> The Operator agrees to notify the Company immediately about any interruptions, unplanned changes to itinerary or unforeseen events that may occur during delivery of Service. Such events include but are not limited to arguments with customer, any damage to person or property, excessive delays in transport, accidents, flat tires, missed flights, missed railway connections or any such event that may cause customer dissatisfaction or impact successful delivery of the service.</li>
	</ol>

	<h2 class="blue-color"><b>5. Company's rights and obligations</b></h2>
	<ol type="A" class="list_type">
		<li>Company reserves the right to block, suspend, deactivate or otherwise restrict any Operator from accessing or using its Platform (thereby delisting them from its Platform) without any cause at any time per its sole discretion.</li>
		<li>Gozo has the right to suspend/terminate the Operator Services on Platform and the use of the Platform by the Operator or may put payments to the Operator on hold (if applicable) or make necessary adjustments to payments due to Operator or raise an appropriate invoice on the Operator without further notice, if Gozo discovers or it is brought to their notice that the aforesaid data is false, incorrect, misleading, misrepresented, fraudulent or does not comply with the Policies, or the Operator has failed to provide updated information, data or documents and in such case the Operator shall also be liable for all the liabilities, risks, damages and consequences that may arise from the very beginning.</li>
		<li>Gozo may verify the progress of the Services provided by the Operator to the Customer under the Transaction by contacting the Operator for any bookings and the Customer from time to time. Gozo may also, from time to time, demand production of documents to verify the progress or completion of delivery of Services from the Operator.</li>
		<li>Gozo shall not attach into a new Gozo partner account, any vehicle that is previously attached to the Gozo network under an existing partners account if a valid undertaking document is already placed on file with Gozo for that vehicle. In order for Gozo to attach such a vehicle into a new partner account we shall require a NO objection certificate to be received from the owner of the vehicle or an updated undertaking document to be placed on file by the Gozo partner trying to attach that vehicle.</li>
		<li>Upon identifying or being notified by any Person or by Law enforcement agency that Operator has violated any Law in the performance of the transaction, Company shall be entitled to immediately suspend Operator access to Platform, notify any Law enforcement agency or any Authority or banks for appropriate action or act in any other way to cooperate with Authorities or protect Company and Platform's interests.</li>
		<li>Company is NOT responsible or liable for the actions or inactions of a Consumer in relation to the activities of the Operator or his driver or his Vehicle. The work performed by the Operator shall be at the risk of the Operator exclusively.</li>
		<li>Company shall be entitled to run any discounts and/ or offers and/ or promotion on the Service at any time, as per Company's discretion. Operator shall not raise any objections to such promotions/ discounts run by the Company.</li>
	</ol>
	<h2 class="blue-color"><b>6.	Delivery of service</b></h2>
	<ol type="A" class="list_type">
		<li>Operator shall have the sole responsibility for any obligations, claims, liabilities, damages or proceedings that arise from provision of Travel Services from its Gozo Operator Account with the Company.</li>
		<li>Operator acknowledges that he is solely responsible for taking such precautions as may be reasonable and proper (including maintaining adequate car documents, driver license, tax receipts, emission certificate, insurance papers etc.) regarding compliance of all applicable laws.</li>
		<li>Operator acknowledges that he is solely responsible for taking such precautions as may be reasonable and proper regarding cleanliness and fitness of the cars, mental and physical fitness of the driver to operate a vehicle for delivery in compliance with the performance expectations of this agreement.</li>
		<li>Operator acknowledges that he is solely responsible for any acts or omissions of his Driver, the Consumer or a third party.</li>
		<li>Operator and its Driver shall duly comply with the payment instructions and/or of any other special instructions (including requests for multiple pickups, carrier requirement, placard requirement or any such instruction) issued by the Company or its representatives regarding the Travel Service being performed by the Operator.</li>
		<li>Operator acknowledges that the Company may release contact, vehicle, insurance information and/or any other information pertaining to the Operator to the law enforcement authorities upon such reasonable request.</li>
		<li>Operator acknowledges that, it shall only transport person(s) authorized by the Consumer during the performance of travel services for such Consumer. The person authorized by the Consumer to consume the travel services is listed as the "Primary Traveller" under the booking confirmation. The operator may only transport packages that are accompanied by the 'Traveller'. No unaccompanied packages shall be transported.</li>
		<li>Operator acknowledges and agree that all Consumers should be transported directly to their specified destination, as directed by the applicable Consumer, without unauthorized interruptions or stops.</li>
	</ol>
	<h2 class="blue-color"><b>7.	Commercial terms</b></h2>
	<ol type="A" class="list_type">
		<li>Operator shall pay a commission to the Company for every booking that is done and processed through the Company's Platform. Such commission shall be determined by the Company on a per booking basis and reflected on the Platform as part of the Assignment of Trip to the</li>
		<li>Company may at its discretion charge an online payment convenience fee of 2% to the Operator on the Total Service Booking Value collected online for every booking.</li>
		<li>Operator recognizes that Company would not be responsible for provision of Cab services. The passengers would be aware that the transportation of passenger by Cab services would be provided by the Operator.</li>
		<li>Company shall only act as an intermediary of the Operator for all the Cab reservations made by the Customers through Company's Platform and Company is not involved in the actual rendering of the Service.</li>
	</ol>
	<h2 class="blue-color"><b>8.	Payments and Billing cycle</b></h2>
	<ol type="A" class="list_type">
		<li>Company accepts payments through bank transfer, to the following bank details. Company shall release the payment to the Operator under a mechanism as per its discretion and considering the prevailing statutory regulations. Gozo bank account details</li>
		<li class="list_table">Gozo bank account details
			<table width='100%'>
				<tr>
					<td>Beneficiary Name:</td>
					<td>GOZO TECHNOLOGIES PRIVATE LIMITED</td>
				</tr>
				<tr>
					<td>Bank Name:</td>
					<td>HDFC BANK LTD</td>
				</tr>
				<tr>
					<td>Branch Name:</td>
					<td>Badshahpur, Gurgaon</td>
				</tr>
				<tr>
					<td>IFSC Code:</td>
					<td>HDFC0001098</td>
				</tr>
				<tr>
					<td>Account Number:</td>
					<td>50200020818192</td>
				</tr>
			</table>
		</li>
		<li>All accounts and billing related communications to the Company must be sent via email to <a href="mailto:accounts@gozocabs.in">accounts@gozocabs.in</a></li>
		<li><b>Deposit with the Company:</b> Before empanelment, the Operator shall be required to deposit an amount of Rs. 3,500/- (Rupees three thousand five hundred only) with the Company. The deposit amount shall be maintained on the Operators account as an interest-free deposit. Upon termination of the relation, any amount due to the Company may be deducted from the deposit and the remainder will be returned to the Operator. During the course of the agreement, the Company at its sole discretion may request the Operator to increase the deposit with the Company. </li>
		<li>All payments due to the Company will be payable by the Operator within 7 days from the date of Invoice and must be deposited in the Company bank account listed herein. In the event of any delay due to any reason including discrepancies in the amount due from the Operator, Company shall have the right to charge interest @ 18% per annum for the period of delay in addition to any other right to remedy it has under the Agreement. Company has the right to take appropriate legal action including right to set off from the payment of Operator until any outstanding amount due from Operator to the Company inclusive of accrued interest is fully recovered. In case Company is not able to recover the payments due from the Operator to the Company, within the given time, it reserves the rights to take appropriate legal actions against the Operator</li>
		<li>All payments payable to the Company must be made directly in the name of the company to the official Bank Account of the Company as laid out in this Agreement or as expressly communicated by the Company in writing.</li>
	</ol>
	<h2 class="blue-color"><b>9.	Customer Support</b></h2>
	<ol type="A" class="list_type">
		<li>Company shall provide Level 1 customer support in connection with the Service. In the event, any complaint is received by the Company for the deficiency in services provided by the Operator, the Company shall forward the same to the Operator immediately and then the Operator is bound to resolve the complaint in a time bound manner.</li>
	</ol>
	<h2 class="blue-color"><b>10.	Whistleblower:</b></h2>
	<ol type="A" class="list_type">
		<li>The Company believes in a transparent and long-term relation with all its Operators and discourages any exchange of monies, favours, incentives or any form of understanding, by the Operator with any individual or employee associated or not with the Company to seek business or favours that extend beyond the scope of business the Operator is entitled to from the Company. The Company maintains a real-time ranking of all the Operators based on multiple factors and believes in rewarding the best performing Operators automatically. Should the Operators receive any undue or inappropriate demands, favours or any such express behaviour from any individual, representative or employee associated or who claims to be able to have an influence with the LimiCompany, it must be reported immediately by email to <a href="mailto:whistleblower@gozocabs.in">whistleblower@gozocabs.in</a>. All information provided shall be treated as confidential and shall in no manner affect the Operators business with the Company. In turn, it will help the Company maintain a transparent and professional relation with all its Operators.</li>
	</ol>
	<h2 class="blue-color"><b>11.	Indemnity</b></h2>
	<ol type="A" class="list_type">
		<li>The Operator agrees and undertakes to indemnify and to hold harmless the Company, its affiliates, successors, agents, assigns, and each of their directors, officers, employees, associates, agents, and representatives from and against any losses, damages, liability, claims, costs, penalty and expenses (including, without limitation, reasonable attorney's fees) incurred by reason of (i) any breach or alleged breach by the Operator of the Operator's obligations, representations, or warranties herein; (ii) any violation by the Operator of applicable law or regulation; or (iii) any complaint, claim or proceedings by the Customers.</li>
		<li>Additionally, the Operator shall, at all times and to the complete satisfaction of the Company indemnify, defend and hold harmless, Company and its officers, directors, employees, associates successors, representatives and agents, against any third party claim, demand, suit, action or other proceeding brought against Company or its directors, successors, representatives, agents, officers and employees and against all penalty, damages, awards, settlements, liabilities, losses, costs and expenses related thereto (including attorney's fees) to the extent that such claim, suit, action or other proceedings are, directly or indirectly, based on or arise on account of the Cab Service and their content, or any breach of any of the terms and conditions of this Agreement by the Operator or failure of the Operator in the performance or observance of its role, functions, responsibilities as specified herein, or the breach by Operator of representations and warranties to the Company and/or to the Customer.</li>
		<li>This clause shall survive the expiration or termination of this Agreement.</li>
	</ol>
	<h2 class="blue-color"><b>12.	Limitation of Liabilities</b></h2>
	<ol type="A" class="list_type">
		<li><p>Operator's liability to Gozo under this Agreement shall be limited to the actual damages suffered by Gozo. Operator is solely and fully responsible for the travellers transporation and shall be solely & fully liable to the customer and traveller under this agreement.</p>
			<p>Except for the obligation of Gozo to pay Operator pursuant to the terms of this Agreement, Gozo shall have no liability to Operator or to anyone claiming through or under this Agreement by reason of the execution or performance of this Agreement.</p>
			<p>In case of occurrence of any accident to the vehicle, injuries or physical harm to the travellers including death, Gozo will not be responsible in any way and Operator shall be fully responsible and held liable for the safety of the travellers.</p>
			<p>Neither Party, shall in any event, be liable for any indirect, consequential, special, contingent or incidental damage or loss whatsoever, including, without limitation, loss of profit, revenue or bargain, arising out of or in connection with this Agreement.</p>
		</li>
	</ol>
	<h2 class="blue-color"><b>13.	Term & Termination</b></h2>
	<ol type="A" class="list_type">
		<li>This Agreement shall be valid for a term of 12 months from the Execution Date ("Initial Term") and shall be automatically renewed for subsequent consecutive terms of 12 months each ("Renewal Terms") unless terminated as per the provisions of this clause.</li>
		<li>Either of the Parties shall be entitled to terminate this Agreement, without assigning any reason thereof, by serving a 30 day's prior written notice to the other Party.</li>
		<li>Company shall be entitled to forthwith terminate the Agreement in case of breach by the Operator of any of its obligations, representations and warranties under this Agreement.</li>
		<li>Company shall be entitled to terminate the Agreement in case the Company has reason to believe that the Operator is in breach of its obligations, representations or warranties under this Agreement or for any reason as per the Company's discretion.</li>
		<li>Operator shall not be exonerated from its obligations towards the Company or Customers accrued on it prior to such termination. Operator shall be liable to honour all bookings assigned to the Operator prior to termination but to be performed post termination, in compliance with the terms of this Agreement.</li>
		<li>Operator shall be liable to immediately pay any amount due and payable by it to the Company or the Customer at the time of termination. Company shall be entitled to set-off or deduct any amount payable by the Vendor, including any Penalties as stipulated herein.</li>
		<li>Company reserves the right to block, suspend, deactivate or otherwise restrict any Operator from accessing or using its platform (thereby delisting them from its platform), instead of terminating the agreement for any period of time, with or without any cause at any time per its sole discretion.</li>
	</ol>
	<h2 class="blue-color"><b>14.	Confidentiality</b></h2>
	<ol type="A" class="list_type">
		<li>Operator acknowledges that Company may, in reliance of this Agreement, provide the Operator with access to trade secrets, customers and other "confidential information". Operator agrees to keep said information as confidential and not use such Confidential Information except for the purpose for which it was disclosed under this Agreement and not to use the said information on his own behalf or disclose the same to any third party.</li>
		<li>For the purposes of this Agreement, "Confidential Information" shall mean all oral or written information (in whatever form) that the Operator has access to while rendering its obligations under this Agreement or which the Operator acquires from the Company in connection with this Agreement or through the use of Website, Operator Interface and concerning the Company, its holding company or subsidiaries, or any aspect of their business, including without limitation, information relating to suppliers, Customers, operations, computer software, hardware, customer information, net rates, information or any data pertaining to or available on the XML/API Interface, Access Codes, information regarding the business operations, financial or technical information, prices, products, content, services, marketing strategies and opportunities, business projections, terms and conditions of this Agreement, intellectual property information, or any other information designated by the Company as confidential or that, under the circumstances surrounding disclosure, the Vendor should reasonably treat as confidential.</li>
		<li>Operator agrees and acknowledges that monetary damages may not be a sufficient remedy for any breach of this clause and that the Company shall be entitled to specific performance or injunctive relief or such other interim measure also as a remedy for any breach or threatened breach of this clause, in addition to any other remedies available at law or in equity.</li>
		<li>All Confidential Information is and shall remain the property of the Company.</li>
		<li>The terms of this clause shall survive the termination or expiration of this Agreement.</li>
	</ol>
	<h2 class="blue-color"><b>15.	Privacy & Copyright Protection</b></h2>
	<ol type="A" class="list_type">
		<li>The Company's Privacy Policies as expressly put on the website and updated from time to time, explain how the Operator's personal data and privacy are protected when the Operator uses our Services. Also, by using the Services, the Operator agrees that the company may use such data in accordance with its Privacy Policies.</li>
		<li>Content displayed on the Company platform is copyrighted by the Company. It respects all copyrights and trademarks owned by third-parties. Any third-party content or logos displayed on this website are owned by the respective third-party. The Operator too, is expected to respect all such copyrights. It may not copy or reproduce any content offered on the platform without express consent of the Company.</li>
	</ol>
	<h2 class="blue-color"><b>16.	Relationship of the Parties</b></h2>
	<ol type="A" class="list_type">
		<li>Nothing in this Agreement shall create, constitute or evidence any partnership, joint venture, agency, trust or employer/employee relationship between the Parties, and a Party may not make or allow to be made, any representation that any such relationship exists between the Parties. A Party shall not have the authority to act for, or to incur any obligation on behalf of, the other Party, except as expressly provided for in this Agreement. Any association between the Parties shall be strictly on a principal to principal basis. However, to the extent of collection of booking amount from the Customer for further remittance to the Operator, the Company shall act as a 'pure collection agent' of the Operator</li>
	</ol>
	<h2 class="blue-color"><b>17.	Modification of Services</b></h2>
	<ol type="A" class="list_type">
		<li>The Company, in its bid to constantly improve the Services may add or remove functionalities or features and may suspend or stop some part of or an entire service altogether as required by changes in law or to upgrade or improve our services. The Operator may have to accommodate the changes from time to time.</li>
		<li>The Company may add or create new limits to its Services at any time. The Operator may have to accommodate the changes from time to time.</li>
		<li>If there is any inconsistency between these terms and any updated or modified terms as listed on GozoCabs website, the updated terms listed on GozoCabs website will prevail to the extent of the inconsistency.</li>
		<li>If the Operator does not comply with the terms or as they may be modified with time and the Company does not act immediately, this doesn't mean that the Company gives up any rights that it may have (such as taking suitable action in the future).</li>
		<li>If a particular term or sub-section is not enforceable in this agreement, this will not affect the applicability of any other terms.</li>
	</ol>
	<h2 class="blue-color"><b>18.	Entire Agreement and Amendments</b></h2>
	<ol type="A" class="list_type">
		<li>This Agreement together with the Annexures hereto constitutes the entire Agreement and understanding between the Parties relating to the subject matter hereof and supersedes all other agreement, oral or written, made between the Parties with respect to such subject matter. If any terms of this agreement conflict with any other document/electronic record, the terms and conditions of this Agreement shall prevail. Operator also represents that it has read the entire Agreement and the appended Annexures. Operator hereby accepts all the policies attached to this Agreement and other rules and policies of the Company applicable to Operator. Any amendments or modifications to this Agreement can be made by the Company and notified to the Operator and shall be binding on both the Parties.</li>
	</ol>
	<h2 class="blue-color"><b>19.	Notices</b></h2>
	<ol type="A" class="list_type">
		<li class="list_table">Any notice or communication required to be addressed or given to the Parties shall be deemed to be served if given in writing at the following addresses:
			<table width='100%'>
				<tr>
					<td width="70%">
						<p>To the Company:</p>
						<p style="margin: 20px 0;">Gozo Technologies Private Limited Legal Department <?= $address ?></p>
						<p>Email ID: operator@gozocabs.in <br>With a copy to: legal@gozocabs.com</p>
					</td>
					<td>
						<p>To the Vendor,</p>
						<p style="margin: 20px 0;">
							<?php
							echo ($data['ctt_user_type'] == 2) ? $data['ctt_business_name'] : $data['name'];
							echo "<br>";
							echo $data['ctt_address'];
							?>
						</p>
					</td>
				</tr>
			</table>
		</li>
	</ol>
	<!--	At the address and email mentioned on Annexure II herein-->
	<h2 class="blue-color"><b>20.	Jurisdiction</b></h2>
	<ol type="A" class="list_type">
		<li>It is agreed that this Agreement is made under the exclusive jurisdiction of the laws of India. Disputes under Agreement are subject to the exclusive jurisdiction of the courts of India.</li>
		<li>All claims arising out of or relating to these terms or the Services will be referred to an arbitrator appointed by the Company, failing him to any other arbitrator chosen by the Company and the Operator in writing. The decision of such an arbitrator shall be binding on both parties.</li>
	</ol>
	<br><br><br><br><br><br>
	<h2 class="blue-color"><b>21.	Assignment</b></h2>
	<ol type="A" class="list_type">
		<li>Operator shall not be entitled to assign its rights and obligations, unless otherwise expressly stipulated under this Agreement, without the prior written consent of the Company. However, Company shall be entitled to assign its rights and obligations under the Agreement to its subsidiaries, affiliates, holding companies and companies under common control (hereinafter "Group Companies").
		</li>
	</ol>
	<h2>IN WITNESS WHEREOF, each of the Parties has executed this Agreement on the date indicated above.</h2>
	<div style="height: 700px;">
		<table width="100%" border='1' bordercolor="c9c9c9" cellpadding="10">
			<tr>
				<td width="312">
					<p><strong>M/S. GOZO TECHNOLOGIES PVT. LTD.</strong> (<strong>COMPANY</strong>)</p>
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;</p>
					<p><strong>Signature: </strong>__ __ __ __ __ __ __ __ __&nbsp; __ __ __ __ _</p>
					<p><strong>&nbsp;</strong></p>
					<p><strong>&nbsp;</strong></p>
					<p><strong>Mr. </strong>__ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __</p>
					<p>For Gozo Technologies Pvt Ltd</p>
					<p>&nbsp;</p>
					<p><strong>Date:&nbsp; </strong></p>
					<p><strong>Place:&nbsp; </strong></p>
				</td>
				<td width="290">
					<p><strong>M/S. </strong> <?= $data['name']; ?> </p>
					<p>(<strong>OPERATOR</strong>)</p>
					<?php
					if ($data['digital_signed'] != 1)
					{
						?>
						<p>&nbsp;&nbsp;&nbsp;&nbsp;</p>
						<p><strong>&nbsp;</strong></p>
						<?php
					}
					if ($data['digital_signed'] == 1)
					{
                      $digitalSignImg = VendorAgreement::getPathById($data['vag_id'], VendorAgreement::DIGITAL_SIGN);
					  //$digitalImg = 'https://' . $data['host'] . $data['vag_digital_sign'];
						?>
						<table>
							<tr>
								<td valign="top"><span style="font-size:12px; font-weight: bold;">Signature :</span></td>
								<td valign="top" align="left"><img src="<?= $digitalSignImg; ?>" width="220px;" height="50px;"></td>
							</tr>
						</table>
						<?php
					}
					else
					{
						?>
						<p><strong>Signature: </strong>__ __ __ __ __ __ __ __ __&nbsp; __ __ __ __ _</p>  
						<?php
					}
					if ($data['digital_signed'] != 1)
					{
						?>
						<p><strong>&nbsp;</strong></p>
						<p><strong>&nbsp;</strong></p>
						<?php
					}
					if ($data['digital_signed'] == 1)
					{
						?>
						<p><strong>Mr. </strong><?= $data['name']; ?> </p>
						<p>( Signed Electronically )</p>
						<p><strong>&nbsp;</strong></p>
						<?php
					}
					else
					{
						?>
						<p><strong>Mr. </strong>__ __ __ __ __ __ __ __ __ __ __ __ __ __ __</p>
						<p>For <?= $data['name']; ?>&nbsp;</p>
						<p><strong>&nbsp;</strong></p>  
						<?php
					}
					if ($data['digital_signed'] == 1)
					{
						?>
						<p><strong>IP Address: </strong> <?= $data['vag_digital_ip']; ?></p>
						<p><strong>On Device: </strong> <?= $data['vag_digital_device_id']; ?></p>
						<p><strong>Device UUID: </strong> <?= $data['vag_digital_uuid']; ?></p>
						<?php
					}
					?>
					<p><strong>Date: </strong> <?= date("d/m/Y", strtotime(DATE('Y-m-d'))); ?></p>
					<p><strong>Place: </strong> 
						<?php
						if ($data['vag_digital_lat'] > '0' && $data['vag_digital_long'] > '0')
						{
							echo $data['vag_digital_lat'] . ", " . $data['vag_digital_long'];
						}
						?></p>
				</td>
			</tr>

		</table>
	</div>
	<h1 class="text-center blue-color">Annexure I - Service Level Document</h1>
	<h2>1) <strong>Operator&rsquo;s Relationship Tier &gt;&gt;&gt;&gt; <?= ($data['vnd_relation_tier'] != '') ? $data['vnd_relation_tier'] : '____________________________'; ?> &lt;&lt;&lt;&lt; </strong></h2>
	<p>Operators may select one of the following tiers. The below table shows the Service level expected from Operators at each tier</p>
	<table width="100%" border='1' bordercolor="c9c9c9" cellpadding="10">

        <tr>
            <td width="96">
                <p><strong>&nbsp;</strong></p>
            </td>
            <td width="240">
                <p><strong>Tier 2 - (Silver Level) </strong></p>
            </td>
            <td width="241">
                <p><strong>Tier 1 &ndash; (Gold Level) </strong></p>
            </td>
        </tr>
        <tr>
            <td width="96" valign="top">
                <p>Service</p>
                <p>Requirements</p>
            </td>
            <td width="240">
                <p>&bull;&nbsp;&nbsp; Commercial vehicles only</p>
                <p>&bull;&nbsp;&nbsp; Car Models NOT TO BE older than 3 years and total driven mileage of vehicle not to exceed 1,00,000 kilometres</p>
                <p>&bull;&nbsp;&nbsp; AC vehicles with functional units. AC to be always operated in vehicle at customers request.</p>
                <p>&bull;&nbsp;&nbsp; Clean and well-maintained vehicles.</p>
                <p>&bull;&nbsp;&nbsp; Report for pickup at least 15mins ahead of scheduled trip start time. See applicable penalty listed in Annexure I</p>
                <p>&bull;&nbsp;&nbsp; Carry a placard bearing customer&rsquo;s name when &lsquo;meet-and-greet&rsquo; service is requested</p>
                <p>&bull;&nbsp;&nbsp; Vehicles must be equipped with First aid kit, properly inflated spare wheel, tool kit, seat belts in all seats and a fire extinguisher</p>
                <p>&bull;&nbsp;&nbsp; Well behaved drivers &ndash; polite and soft-spoken to customers at all times. Driver to not answer the phone while driving.&nbsp;</p>
                <p>&bull;&nbsp;&nbsp; Driver must maintain a duty slip for the trip and get customer signatures on duty-slip at end of the trip. Duty slip must include Trip start date &amp; time, Trip start odometer reading, trip end date and time, trip end odometer reading and customer signature acknowledging the same.</p>
            </td>
            <td width="241">
                <p>&bull;&nbsp;&nbsp; <strong><em>Inclusive of all service level requirements listed for Operator Relationship Tier 2 (Silver Level) </em></strong></p>
                <p>&bull;&nbsp;&nbsp; No dents, rust or blemishes on exterior of vehicle.&nbsp;</p>
                <p>&bull;&nbsp;&nbsp; Total driven mileage of vehicle not to exceed 50,000 kilometres</p>
                <p>&bull;&nbsp;&nbsp; Vehicle MUST be spotless clean inside and outside.</p>
                <p>&bull;&nbsp;&nbsp; Vehicle model must match the model of vehicle requested for the trip.&nbsp;</p>
                <p>&bull;&nbsp;&nbsp; Vehicle must have a pleasant-smelling car freshener, Audio system, USB charging cable and AUX cable</p>
                <p>&bull;&nbsp;&nbsp; Driver must be well-dressed, preferably in uniform &ndash; no jeans or round-neck t-shirts</p>
                <p>&bull;&nbsp;&nbsp; Report for pickup atleast 30mins ahead of scheduled trip start time. See applicable penalty listed in Annexure I</p>
                <p>&bull;&nbsp;&nbsp; At trip start time car must have 2x 500ml bottles of bottled water for the customer, newspaper of the day (1 in Hindi and 1 in English)</p>
                <p>&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td width="96" valign="top">
                <p>Benefits to Operator</p>
            </td>
            <td width="240" valign="top">
                <p>&bull; All standard benefits of being a Gozo Operator</p>
            </td>
            <td width="241">
                <p>&bull;&nbsp;&nbsp; All standard benefits of being a Gozo Operator</p>
                <p>&bull;&nbsp;&nbsp; Higher preference for booking assignments</p>
                <p>&bull;&nbsp;&nbsp; Access to high value trips booked under the Company&rsquo;s premium class of service</p>
            </td>
        </tr>
    </table>
	<h2>2)	Operator's Service Level Requirements (Applicable to all levels)</h2>
	<ol type="a" class="list_type">
		<li><b>Maintain complete compliance:</b> Operator must ensure that the following information is maintained on file with Gozo before a car or driver can be approved to be assigned to serve a trip for Gozo</li>
		<li><b>Provide complete data for vehicles and drivers:</b> Operator must ensure that proper documentation is submitted for all drivers and vehicles that are to be used for service delivery.
			<ol type="i" class="list_type">
				<li>Submit into the platform, the following Information about vehicles -
					<ol type="1" class="list_type">
						<li>Model and number plate of the vehicle</li>
						<li>Photo copy of valid insurance for the vehicle with a clearly provided insurance end-date</li>
						<li>Picture of front and rear license plate of the vehicle</li>
						<li>Photocopy of Pollution under control certificate with clearly readable validity end-date</li>
						<li>Photocopy of Registration certification for the vehicle with clearly readable validity end-date</li>
						<li>Photocopy of applicable commercial permits for the vehicle with clearly readable validity end-date</li>
					</ol>
				</li>
				<li>ii)	Submit into the platform, the following Information about drivers -
					<ol type="1" class="list_type">
						<li>Photocopy of Driver's License with a clear picture</li>
						<li>At least 2 proofs of address for driver</li>
						<li>(3)	Driver's police verification certificate</li>
					</ol>
				</li>
			</ol>
		</li>
		<li><b>Ensure that proper documentation is carried in vehicle at all times</b>
			<ol type="i" class="list_type">
				<li>Driver must possess all legal documents including but not limited to Registration certificate, Insurance documents, Pollution under control certificate, Commercial Taxi License, Vehicle fitness certificates, Interstate Permits and all such relevant documents as required by law</li>
				<li>Driver must carry his valid driver's license</li>
			</ol>
		</li>
		<li><b>Delays or Breakdowns that impact travellers:</b>
			<ol type="i" class="list_type">
				<li>In the event of a delay arising out of vehicle breakdown or any fault of the Operator or its representatives that results
					<ol type="1" class="list_type">
						<li>in travellers being stranded en route for more than one hour, Operator should offer refreshment to the travellers at the Operators cost.</li>
						<li>in travellers being stranded en route for more than 4 hours, Operator will provide the travellers with a compensatory meal.</li>
						<li>in the travellers to miss an onward Service such as flight or railway ticket, Operator shall be liable to refund the full value of such service as claimed by the traveller or customer. In addition, the Operator may be subject to additional penalties (as determined by Gozo).</li>
					</ol>
				</li>
				<li>In case of vehicle breakdown during the journey, Operator must arrange an alternate vehicle within 2 hours. Operator shall also at its own cost arrange for the traveller to wait at a nearby restaurant/hotel or a convenient place while alternate travel arrangements are made.</li>
				<li>The operator shall be solely and fully responsible for any claims or liabilities arising out of such events.</li>
			</ol>
		</li>
		<li><b>Driver requirements:</b> Operator shall adhere to the following as far as drivers are concerned. Operator shall ensure that the driver shall be duly qualified in accordance with any applicable law, rule or regulation. This shall include the driver holding a valid driving license authorizing the driver to drive a commercial passenger transport vehicle. To qualify, drivers must have driving records free of serious fault accidents and convictions of a serious nature for a period of at least 3 years.
			<ol type="i" class="list_type">
				<li>No Alcohol, Drugs or Smoking: The driver shall not consume, use or otherwise deal with alcohol or illegal drugs whilst on duty. Breach of this policy will result in the Operators relationship with Gozo being immediately terminated.</li>
				<li>ii)	No Mobile Phone: The use of a hand-held mobile phone is strictly prohibited whilst driving. Drivers should not make or receive mobile phone calls while driving, if in the case of an emergency a mobile phone call is required, then the driver must use a hands-free device (provided that this is in compliance with the law) and stop on the side, and only then take the calls.</li>
				<li>No prior conviction: The driver shall not have any prior conviction for any serious driving offence; including the influence of alcohol or drugs, dangerous driving, driving without insurance, driving while disqualified or causing death by reckless or dangerous driving. Any prosecution or driving conviction must be reported to Service Partner immediately in writing.</li>
				<li>No Tips: The driver shall not demand tips or use any coercion to extract tips and/or commission from the travellers. Tips and/or commission are completely at the discretion of the travellers.</li>
				<li>Cleanliness: The driver must keep the inside of any transport vehicle clean and well maintained including the windows and litter bins.</li>
				<li>Refueling: The driver must ensure that the vehicle is on a full tank before the clients are picked up at the start time of the trip. Driver shall not refuel the transport vehicle after picking up the clients at the start time of the trip.</li>
				<li>Knowledge of Routes: The drivers shall have a copy of the itinerary with him and check the route in advance. It is the driver's responsibility to know the location of the drop off prior to commencing the Service. The driver shall not study maps in front of the tour leader ( GPS)</li>
			</ol>
		</li>
		<li><b>Proper rest to drivers:</b> It is the Operators responsibility to ensure that Drivers are not overworked and have had proper rest each day.
			<ol type="i" class="list_type">
				<li>A driver is required to have MANDATORY minimum 2 hours of (non-driving) rest time for every 6 hours of driving time.</li>
				<li>A driver may drive a maximum of 15 hours in any 24-hour period.</li>
				<li>A driver may be on-duty (driving or not) for a maximum of 15 hours after having 8 consecutive hours off-duty</li>
				<li>A driver MUST HAVE 8 consecutive hours of off duty time after having total non-continuous time on-duty (driving or not) of 15 hours</li>
				<li>A driver may not drive for more than 84 hours in 7 consecutive days</li>
			</ol>
		</li>
	</ol>
	<h2>3) Penalties to be imposed upon Operator (Applicable to all Operator Relationship levels)</h2>
	<p>Gozo reserves the right to impose the following penalties on the Operator's accounts for violation of conditions that have been agreed in this agreement. The amount of penalty listed is applied per incident. One or more penalties may be applicable at the same time.</p>

	<div class="list_table" style="height: 800px;">Gozo bank account details
		<table width='100%'>
			<tr>
				<td><b>Incident</b></td>
				<td><b>Penalty amount applied to Operators account</b></td>
			</tr>
			<tr>
				<td>Operator cancels an assigned trip less than 12 (twelve) but more than 4 (four)hours before the scheduled trip start time</td>
				<td>Rs. 500 per incident</td>
			</tr>
			<tr>
				<td>Operator cancels an assigned trip less than 4 (four) hours before the scheduled trip start time</td>
				<td>Rs. 1000 per incident</td>
			</tr>
			<tr>
				<td>Operator uses an unregistered or unapproved driver or vehicle for an assigned trip</td>
				<td>Rs. 1000 per incident</td>
			</tr>
			<tr>
				<td>Driver or Vehicle that is used to serve the customer does not match the Driver or vehicle information assigned to the trip</td>
				<td>Rs. 1000 per incident</td>
			</tr>
			<tr>
				<td>Vehicle does not show up at the pickup location and customer complains of a 'no show'</td>
				<td>Rs. 2000 per incident</td>
			</tr>
			<tr>
				<td>Tier 2 Operators - vehicle late for pickup, car not well maintained, driver not soft-spoken or any reason where service level requirements are not met</td>
				<td>Rs. 500 per incident</td>
			</tr>
			<tr>
				<td>Tier 1 Operators - vehicle late for pickup, car not well maintained, driver not soft-spoken or any reason where service level requirements are not met</td>
				<td>Rs. 1000 per incident or 100% of the trip vendor amount (as decided by Gozo)</td>
			</tr>
		</table>
	</div>

	<h1 class="text-center blue-color">Annexure II - INSTRUCTIONS & OPERATOR DETAILS FORM</h1>
	<ol type="1" style="font-size: 18px; line-height: 30px;">
		<li>CAREFULLY READ THE ENTIRE AGREEMENT INCLUDING THE ANNEXURES</li>
		<li>FILL OUT THE DETAILS BELOW IN CAPITAL LETTERS</li>
		<li>INITIAL ON EVERY PAGE OF THE AGREEMENT TO INDICATE THAT YOU HAVE READ AND ACCEPT THE TERMS OF THE AGREEMENT.</li>
		<li>FILL OUT ANNEXURE 1</li>
		<li>SIGN THE AGREEMENT</li>
	</ol>
    <p>&nbsp;</p>
    <p>Operator&rsquo;s information: (PLEASE FILL IN CAPITAL LETTERS)</p>
	<table width="100%" border='1' bordercolor="c9c9c9" cellpadding="10">
        <tr>
            <td width="324">
                <p><strong><?= $data['vnd_firm_txt']; ?> : </strong></p>
            </td>
            <td width="276">
                <p><?= $data['name']; ?></p>
            </td>
        </tr>
        <tr>
            <td width="324">
				<?php
				if ($data['ctt_user_type'] == 2)
				{
					?>
					<p><strong>BUSINESS</strong> :</p>
					<?php
				}
				else
				{
					?>
					<p><strong>INDIVIDUAL</strong> :</p>
				<?php } ?>
            </td>
            <td width="276">
				<?php
				if ($data['ctt_user_type'] == 2)
				{
					?>
					<p><?= $data['ctt_business_name']; ?></p>
					<?php
				}
				else
				{
					?>
					<p><?= $data['name']; ?></p>
				<?php } ?>


            </td>
        </tr>
        
        <tr>
            <td width="324">
                <p><strong>BUSINESS ADDRESS : </strong></p>

            </td>
            <td width="276">
                <p><?= $data['ctt_address']; ?></p>
            </td>
        </tr>
        <tr>
            <td width="324">
                <p><strong>CITY :</strong></p>
            </td>
            <td width="276">
                <p><?= $data['city_name']; ?></p>
            </td>
        </tr>
        <tr>
            <td width="324">
                <p><strong>OPERATOR RELATIONSHIP TIER / LEVEL&nbsp; (See Annexure I for details) </strong></p>
            </td>
            <td width="276">
                <?php
                
                /*if ($data->vnd_rel_tier == 1)
                {
                        $data['vnd_relation_tier'] = 'Tier 1 (Gold Level)';
                }
                else if ($model->vnd_rel_tier == 0)
                {
                        $data['vnd_relation_tier'] = 'Tier 2 (Silver Level)';
                }*/
              
                   echo $data['vnd_relation_tier'];
                ?>
		
            </td>
        </tr>
    </table>
    <p>&nbsp;</p>
    <table width="100%" border='1' bordercolor="c9c9c9" cellpadding="10">
        <tr>
            <td width="30%">
                <strong>EMAIL&nbsp; </strong>
            </td>
            <td width="25%">
                <strong>PRIMARY&nbsp;</strong>
            </td>
            <td width="45%">
				<?= $data['vnd_email']; ?>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <strong>PHONE&nbsp; </strong>
            </td>
            <td width="25%">
                <strong>PRIMARY </strong>
            </td>
            <td width="45%">
				<?= $data['vnd_phone']; ?>
            </td>
        </tr>
<!--        <tr>
            <td width="30%">
                <strong>MOBILE&nbsp; </strong>
            </td>
            <td width="25%">
                <strong>PRIMARY </strong>
            </td>
            <td width="45%">
		<?= $data['vnd_land_phone']; ?>
            </td>
        </tr>-->
        <tr>
            <td width="30%">
                <strong>DRIVER LICENSE # </strong> 
            </td>
            <td colspan="2" width="70%">
				<?= $data['ctt_license_no']; ?>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <strong>VOTER ID # </strong> 
            </td>
            <td colspan="2" width="70%">
				<?= $data['ctt_voter_no']; ?>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <strong>PAN CARD # </strong> 
            </td>
            <td colspan="2" width="70%">
				<?= $data['ctt_pan_no']; ?>
            </td>
        </tr>

    </table>
</div>
</div>