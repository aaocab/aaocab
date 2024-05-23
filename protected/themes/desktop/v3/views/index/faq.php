<?php $getFaqCat = BotFaq::getCategory(); ?>
<div class="container">
	<div class="row title-widget">
		<div class="col-12">
            <span class="merriw heading-line"><?php echo $this->pageTitle; ?></span>
		</div>
	</div>
</div>
<div class="container">
<div class="row mt15">
	<div class="col-12 style-ul-panel">
		<nav>
					<ul class="pl0">
						<?php
						foreach ($getFaqCat as $cat)
						{
							?>
							<li class="nav-item">
								<a class="btn btn-primary font-12 mt5 pl10 pr10 mr10 hvr-push" aria-current="page" href="#category<?php echo $cat['bof_id']; ?>"><?php echo $cat['bof_category']; ?></a>
							</li>
						<?php } ?>
					</ul>
		</nav>
	</div>
</div>
</div>
<div class="container mb-3 faq-collapse">
	<?php
	foreach ($getFaqCategory as $category)
	{
		?>
		<div class="row">
			<div class="col-12">
				<p class="font-20 merriw mt-2" id="category<?php echo $category['bof_id']; ?>"><?php echo $category['bof_category']; ?></p>
				<div class="collapsible collapse-icon accordion-icon-rotate">
					<?php
					$getCategoryData = BotFaq::getCategoryData($category['bof_category']);
					foreach ($getCategoryData as $categoryData)
					{
						?>
						<div class="card collapse-header">
							<div id="headingCollapse<?php echo $categoryData['bof_id']; ?>" class="card-header" data-toggle="collapse" role="button" data-target="#collapse<?php echo $categoryData['bof_id']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $categoryData['bof_id']; ?>">
								<span class="collapse-title">
									<?php echo $categoryData['bof_question']; ?>
								</span>
							</div>
							<div id="collapse<?php echo $categoryData['bof_id']; ?>" role="tabpanel" aria-labelledby="headingCollapse<?php echo $categoryData['bof_id']; ?>" class="collapse">
								<div class="card-body">
									<?php echo nl2br($categoryData['bof_answer']); ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>


