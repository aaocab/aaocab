<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
		<?php
		?>
        <h4>Processing ...</h4>
		<form  method="post" action="<? echo $param_list['ebs_gateway'] ?>" name="frmTransaction" id="frmTransaction">
			<?php
			unset($param_list['ebs_gateway']);
			unset($param_list['hash']);
			foreach ($param_list as $name => $value) :
				?>
				<input type="hidden" name="<?= $name ?>" value="<?= $value; ?>">
			<?php endforeach; ?>
			<? /*
			<input name="submitted" value="Submit" type="submit" />
					*/
			?>

		</form>
        <script type="text/javascript">
            // self executing function
            (function () {
                // auto submit form
              document.getElementById("frmTransaction").submit();
            })();
        </script>
    </body>
</html>