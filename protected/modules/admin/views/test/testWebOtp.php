

<form action="/verify-otp" method="POST">
  <input type="text"
         inputmode="numeric"
         autocomplete="one-time-code"
         pattern="\d{6}"
         required >



<div class="row">
			<div class="col-3 p5"><?php echo CHtml::numberField('number1', '', array('onkeyup' => 'onKeyUpEvent(1, event)', 'onfocus' => 'onFocusEvent(1)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber1')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number2', '', array('onkeyup' => 'onKeyUpEvent(2, event)', 'onfocus' => 'onFocusEvent(2)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber2')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number3', '', array('onkeyup' => 'onKeyUpEvent(3, event)', 'onfocus' => 'onFocusEvent(3)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber3')) ?></div>
			<div class="col-3 p5"><?php echo CHtml::numberField('number4', '', array('onkeyup' => 'onKeyUpEvent(4, event)', 'onfocus' => 'onFocusEvent(4)', 'maxlength' => '1', 'class' => 'form-control font-30 text-center weight600 ootpNumber4')) ?></div>
			<div class="correctotp danger col-12 mt5"></div>
</div>



</form>
<script type="text/javascript">
//	function putOtp(otp)
//		{
//			var  arr = otp.split("");
//			$('.ootpNumber1').val(arr[0]);
//			$('.ootpNumber2').val(arr[1]);
//			$('.ootpNumber3').val(arr[2]);
//			$('.ootpNumber4').val(arr[3]);
//     
//		}
</script>

<script>

if ('OTPCredential' in window) {
  window.addEventListener('DOMContentLoaded', e => {
    const input = document.querySelector('input[autocomplete="one-time-code"]');
    if (!input) return;
    // Cancel the Web OTP API if the form is submitted manually.
    const ac = new AbortController();
    const form = input.closest('form');
    if (form) {
      form.addEventListener('submit', e => {
        // Cancel the Web OTP API.
        ac.abort();
      });
    }
    // Invoke the Web OTP API
    navigator.credentials.get({
      otp: { transport:['sms'] },
      signal: ac.signal
    }).then(otp => {
      input.value = otp.code;
	  putOtp(input.value);
      // Automatically submit the form when an OTP is obtained.
     // if (form) form.submit();
    }).catch(err => {
      console.log(err);
    });
  });
}
</script>