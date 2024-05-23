<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
		<?
		if (isset($param_list['checkoutPageUrl']))
		{
			$url = $param_list['checkoutPageUrl'];
			ob_start();
			header("Location: $url");
			exit();
		}
		else
		{
			echo $param_list['message'];
			if ($param_list['txn_url'])
			{
				?>
				<form  method="post" enctype='application/json' action="<? echo $param_list['txn_url'] ?>" name="frmTransaction1" id="frmTransaction1">
					<?php
					unset($param_list['txn_url']);
					foreach ($param_list as $name => $value) :
						?>
						<input 
							type="hidden"  
							name="<?= $name ?>" 
							value="<?= $value; ?>">
						<?php endforeach; ?>
						<?
						/* ?>
						  <input name="submitted" value="Submit" type="submit" />
						  <? */
						?>

				</form>
				<script type="text/javascript">
		            // self executing function
		            (function () {
		                // auto submit form
		                document.getElementById("frmTransaction1").submit();
		            })();
				</script>

				<?
			}
		}
		?>
    </body>
</html>