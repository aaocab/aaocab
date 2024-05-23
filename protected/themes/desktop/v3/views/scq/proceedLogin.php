<script type="text/javascript">
	$(document).ready(function()
	{
		window.sessionStorage.setItem("returnURL", "<?= $returnURL; ?>");
		window.location.href = "<?= $this->getURL(["users/signin", "signup" => $signup]) ?>";
	});
</script>