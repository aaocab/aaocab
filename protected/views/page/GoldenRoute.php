<style>
    ul{ padding: 0; padding-left: 15px; margin: 0;}
    li{ line-height: 24px;}
	.golden_img img{ width: 100%;}
</style>
<div class=" row p20 padding_zero">
    <div class="col-xs-12 p0">
        <article>
            <section class="">
                <p>For the cheapest one-way outstation rides always make your booking between 3-4days ahead. This allows us to find you a car for your one-way trip and also reserve it at the best price. We can do that if you book your car atleast 3-4days ahead on your route.</p>
               <div class="row mt20 golden_img">
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
				
				   <div class="col-xs-12 col-sm-4"><a href="<?=$url?>" target="_blank"><img src="/images/golden/<?php ($i<10?print('0'.$i):print($i))?>-300x250.gif" title="<?=ucfirst($car_array_alt[$i-1])?> cheapest oneway outstation cabs" alt="<?=ucfirst($car_array_alt[$i-1])?> Cheapest oneway outstation cabs"></a></div>
				<?php
					if($i%3==0) echo'</div><div class="row mt20 golden_img">';
				
				}
				?>
            </section>
        </article>
    </div>
</div>

