<div class="content-boxed-widget">
	<div class="text-center flash_banner float-none marginauto">
	<h2>Cheapest oneway outstation cabs</h2>
		<p>For the cheapest one-way outstation rides always make your booking between 3-4 days ahead. This allows us to find you a car for your one-way trip and also reserve it at the best price. We can do that if you book your car atleast 3-4 days ahead on your route.</p>
              
				<?php
				
				$numberOfCar=16;
				$car_array=array('bhopal-indore','chandigarh-ludhiana','ahmedabad-vadodara','amritsar-ludhiana','allahabad-lucknow_airport','allahabad-lucknow','agra-jaipur','chandigarh-delhi',
								'lucknow_airport-kanpur','kanpur_nagar-lucknow','allahabad-varanasi','kanyakumari-thiruvananthapuram',
					            'coorg_madikeri-mysore','cochin_airport-munnar','coimbatore-kochi','chennai-vellore','ahmedabad-vadodara',
								'ahmedabad-anand','ahmedabad-rajkot');
				$car_array_alt=array('Bhopal-Indore','Chandigarh-Ludhiana','Ahmedabad-Vadodara','Amritsar-Ludhiana','Allahabad-Lucknow airport','Allahabad-Lucknow','Agra-Jaipur','Chandigarh-Delhi',
								'Lucknow airport-Kanpur','Kanpur_nagar-Lucknow','Allahabad-Varanasi','Kanyakumari-Thiruvananthapuram',
					            'Coorg_madikeri-Mysore','Cochin airport-Munnar','Coimbatore-Kochi','Chennai-Vellore','Ahmedabad-Vadodara',
								'Ahmedabad-Anand','Ahmedabad-Rajkot');
				
				for($i=1;$i<=count($car_array);$i++)
				{
					
					$url=Yii::app()->getBaseUrl(true).'/book-taxi/'.$car_array[$i-1];
				?>
				
				   <div class="">
					<a href="<?=$url?>"><img style="width: 100%;" src="/images/golden/<?php ($i<10?print('0'.$i):print($i))?>-300x250.gif" title="<?=ucfirst($car_array_alt[$i-1])?> cheapest oneway outstation cabs" alt="<?=ucfirst($car_array_alt[$i-1])?> Cheapest oneway outstation cabs"></a>
					</div>
				   <div class="clear"></div>
				<?php
					if($i%3==0) echo'</div><div class="row mt20 golden_img">';
				
				}?>		
	
</div>
</div>