
        <div class="myprofile_right" style="min-height:510px;">
			<p class="review_Enter">Search View</p>
			<div class="table-responsive" style="padding:0 30px 0 0;">
				<table class="table table-hover table-bordered" style="padding:10px;">
					<tbody>
						<tr>
							<td style="width:200px; border:1px solid #ffffff; font-weight:600">Searched For</td>
							<td style="border:1px solid #ffffff;"><?=$userSearch['looking_for']?></td>
						</tr>

						<tr>
                            <td style="width:200px; border:1px solid #ffffff; font-weight:600">Searched At</td>
							<td style="border:1px solid #ffffff;">
								<?
									if($userSearch['looking_loc']!=''){
										echo $userSearch['looking_loc'];
									}else{
										echo "--";
									}
								?>
							</td>
                        </tr>
						
						<tr>
							<td style="width:200px; border:1px solid #ffffff; font-weight:600">Size</td>
							<td style="border:1px solid #ffffff;">
								<?
									if($userSearch['size']!=''){
										echo $userSearch['size'];
									}else{
										echo "Do not know";
									}
								?>
							</td>
						</tr>
						
						<tr>
							<td style="width:200px; border:1px solid #ffffff; font-weight:600">Type of Work</td>
							<td style="border:1px solid #ffffff;">
								<?
									if($userSearch['work']!=''){
										echo $userSearch['work'];
									}else{
										echo "Do not know";
									}
								?>
							</td>
						</tr>
						
						<tr>
							<td style="width:200px; border:1px solid #ffffff; font-weight:600">Exprience Required</td>
							<td style="border:1px solid #ffffff;">
								<?
									if($userSearch['exp_required']!=''){
										echo $userSearch['exp_required'];
									}else{
										echo "Do not know";
									}
								?>
							</td>
						</tr>
						
						<tr>
							<td style="width:200px; border:1px solid #ffffff; font-weight:600">Preferred Time</td>
							<td style="border:1px solid #ffffff;"><?=$userSearch['time_pref']?></td>
						</tr>
						
						<tr>
							<td style="width:200px; border:1px solid #ffffff; font-weight:600">Requested Date</td>
							<td style="border:1px solid #ffffff;"><?=$userSearch['date_work']?></td>
						</tr>
						
						<tr>
							<td style="width:200px; border:1px solid #ffffff; font-weight:600">Status</td>
							<td style="border:1px solid #ffffff;">
								<?
									if($userSearch['status']==1){
										echo "Cancelled";
									}else{
										echo "Open";
									}
								?>
							</td>
						</tr>
						
					</tbody>
				</table>
			</div>
			<input class="signupbtn" onclick="goBack()" type="button" name="sub" value="Back" />
		</div>       
<script type="text/javascript">
	$(document).ready(function(){
		$("#formId").validation();
	});
	
	function validateCheckHandler(){
		if( $("#formId").validation( {errorClass:'validationErr'} ) ) {
			return true;
		} else {
			return false;
		}
	}
	
	function goBack() {
		window.history.back()
	}
</script>