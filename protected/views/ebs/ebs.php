<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
		<?php
		?>
        <h4>Processing ...</h4>
		<form  method="post" action="<? echo $ebs_post['ebs_gateway'] ?>" name="frmTransaction" id="frmTransaction">
			<?php
			unset($ebs_post['ebs_gateway']);
			unset($ebs_post['hash']);
			foreach ($ebs_post as $name => $value) :
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