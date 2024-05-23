<script type="text/javascript">
	$(document).ready(function()
	{
		window.sessionStorage.setItem("rdata", "<?= $this->pageRequest->getEncrptedData(); ?>");
		window.sessionStorage.setItem("returnURL", "<?= $returnURL; ?>");
		window.location.href = "<?= $this->getURL(["users/signin", "signup" => $signup]) ?>";
	});
</script>