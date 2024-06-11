<style>

    .affix{
        width:16%;
    }
    .ques{
        font-weight: bold;
    }

    .ans{
        padding-bottom: 15px;
    }
</style>

<div class="panel panel-white">
    <div class="panel-heading"><h3 class="m0">CashBack On Advance Payment: Terms & Conditions</h3></div>
    <div class="panel-body">                                           
        <ul style="list-style:circle;">
	    <?
	    if ($cashbackperc != '')
	    {
		$percen = '25%';
	    }
	    else
	    {
		$percen = '50%';
	    }
	    ?>

            <li><? echo $percen; ?> (Fifty percent) of the Booking Amount shall be credited to your Gozo Account in the form of Gozo Coins upon successful completion of your trip. . These Gozo Coins may be redeemed against payment for any future trip with aaocab and can be redeemed 15% of total amount in subsequent bookings, subject to terms and conditions outlined on our website with respect to Gozo Coins and Gozo Credits.</li>
        </ul>  
	<ul style="list-style:circle;">
	    <li><a href='#' onclick='showTcGozoCoins()'>Gozo Coins – Credits Program: Terms & Conditions</a></li> 
	</ul>
    </div>
</div>

