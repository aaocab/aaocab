<?php
$this->beginContent('//layouts/head');
if ($this->layout == 'column1')
{
	$style = "background-color: inherit";
}
$fixedTop				 = ($this->fixedTop) ? "navbar-fixed-top" : "";
$bgBanner				 = ($this->fixedTop) ? "bg-banner" : "";
?>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T73295"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->


    <div class="container-fluid">
		<?php
		$time					 = Filter::getExecutionTime();
		$GLOBALS['time97']		 = $time;
		?>
		<script type="application/ld+json">
<?php
$organisationSchemaRaw	 = StructureData::getOrganisation();
echo json_encode($organisationSchemaRaw, JSON_UNESCAPED_SLASHES);
?>
		</script>
		<?php
		echo $content;

		$time				 = Filter::getExecutionTime();
		$GLOBALS['time98']	 = $time;

		echo $this->renderPartial("/index/footer");
		?>
    </div>
	<?php
	$time				 = Filter::getExecutionTime();
	$GLOBALS['time99']	 = $time;

	$this->endContent();
	