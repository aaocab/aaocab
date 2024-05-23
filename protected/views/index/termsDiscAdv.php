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
    <div class="panel-heading"><h3 class="m0">Instant Discount On Advance Payment: Terms & Conditions</h3></div>
    <div class="panel-body">                                           
        <ul style="list-style:circle;">
            <? if($cashbackperc!=''){
                $percen='2.5%';
            }else{
                 $percen='5%';
            }?>
            <li><? echo $percen;?> (Five percent) of the Booking Amount shall be offered as instant discount if you choose to confirm your Booking by paying online within 4 (four) hours of creation of the Booking. You are required to pay at least the minimum amount outlined in your Booking details sent over sms and email. Please note that this discount offer cannot be clubbed with any other Promo Code or offers.</li>
        </ul>                    
    </div>
</div>

