<?php /* @var $umodel UnregisterOperator */ ;?>
<div class="col-xs-12 ">
							<div class="col-xs-12 search-cabs-box mb30">
								<div class="row">
									<div class="col-xs-12 col-sm-12">
										<div class="row p10">
											<div id="plzhide">
												</br></br>
												<div><h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please unsubscribe +91<?=$umodel->uo_phone;?> from future Taxi requests</br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;alerts from GOZO CABS.</h1></div><br/>
											<button type="button" style="background-color: #0766bb; color: #fff; padding : 30px 100px 30px 100px; font-size: 40px; margin: 0 50px 0 50px" class="btn btn-primary" onclick="unsubscribe('<?= $buvId ?>','1')">Proceed</button>
											<button type="button" style="background-color: #ff6700; color: #fff; padding : 30px 100px 30px 100px; font-size: 40px;" class="btn btn-primary" onclick="unsubscribe('','0')">Cancel</button>
											</div>
											<div id="plzshow" style=" display: none;"> 
												<p><h1>You have successfully unsubscribed.</h1></p><br/>
										
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
		
 <script src="/assets/js/jquery.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<script type="text/javascript">
	
function unsubscribe(buvID,buvtype)
{ 
	var buv_id=buvID;
	var buvtype=buvtype;
	if(buvID !='')
	{	
		var result = confirm("Are you sure you want to unsubscribe?");
		if (result) {
			$href = '<?= Yii::app()->createUrl('index/UnregUnsubscribe') ?>';
			 jQuery.ajax({type: 'GET', url: $href,
                data: {'buv_id': buv_id,'buvtype':buvtype},
                success: function (data)
                {
                   $('#plzhide').hide();
				   $('#plzshow').show();
                		
                },
				 error: function () {
                    alert(error);
                }
               
            });
			}
			}
			else{
			window.location.href = '<?= Yii::app()->createUrl('index/') ?>';
			}
}
</script>
