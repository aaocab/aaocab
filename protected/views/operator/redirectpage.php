<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
		<?php
		echo $rdata;
		?>
        <h4>Processing ...</h4>
        <form id="checkout_form" name="checkout_form" method="post" action="/operator/register">
			<input type="hidden" name="Operator[rdata]" value="<?= $rdata; ?>">
        </form>
        <script type="text/javascript">
			// self executing function
			(
					function () {
						// auto submit form
						document.getElementById("checkout_form").submit();
					})();
        </script>
    </body>
</html>