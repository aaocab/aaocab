<div class="container-fluid pt-3 bg-gray faq-collapse pb-4">
	<div class="container p0 pb-2">
		<div class="row">
			<div class="col-12"><p class="font-20 mt-1 merriw mb5"><b>Frequently asked questions (FAQ)</b></p></div>
			<div class="col-12">
				<section id="collapsible">
                    <div class="collapsible">
						<?php
						foreach ($getFaqCategory as $category)
						{
						?>
                        <div class="card collapse-header p5">
<!--                            <div id="headingCollapse<?php echo $category['bof_id']; ?>" class="card-header" data-toggle="collapse" role="button" data-target="#collapse<?php echo $category['bof_id']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $category['bof_id']; ?>">
                                <span class="collapse-title">
                                   <?php echo $category['bof_category']; ?>
                                </span>
                            </div>-->
							<p class="font-20 merriw mt10" id="category<?php echo $category['bof_id']; ?>"><?php echo $category['bof_category']; ?></p>
							<?php
							$getCategoryData = BotFaq::getCategoryData($category['bof_category'], 10);
							foreach ($getCategoryData as $categoryData)
							{
							?>
							<div id="headingCollapse<?php echo $categoryData['bof_id']; ?>" class="card-header" data-toggle="collapse" role="button" data-target="#collapse<?php echo $categoryData['bof_id']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $categoryData['bof_id']; ?>">
                                <span class="collapse-title">
                                   <?php echo $categoryData['bof_question']; ?>
                                </span>
                            </div>
                            <div id="collapse<?php echo $categoryData['bof_id']; ?>" role="tabpane<?php echo $categoryData['bof_id']; ?>" aria-labelledby="headingCollapse<?php echo $categoryData['bof_id']; ?>" class="collapse">
                                <div class="card-body">
                                   <?php echo nl2br($categoryData['bof_answer']); ?>
                                </div>
                            </div>
							<?php } ?>
                        </div>
						<?php } ?>
						<div class="col- text-center mt-2"><a href="/faq" class="btn btn-primary font-14 hvr-push">All FAQ's</a></div>
<!--                        <div class="card collapse-header">
                            <div id="headingCollapse4" class="card-header" data-toggle="collapse" role="button" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                <span class="collapse-title">
                                    Collapse Item 4
                                </span>
                            </div>
                            <div id="collapse4" role="tabpanel" aria-labelledby="headingCollapse4" class="collapse">
                                <div class="card-body">
                                    Pie dragée muffin. Donut cake liquorice marzipan carrot cake topping powder candy. Sugar plum
                                    brownie brownie cotton candy.
                                    Tootsie roll cotton candy pudding bonbon chocolate cake lemon drops candy. Jelly marshmallow
                                    chocolate cake carrot cake bear claw ice cream chocolate. Fruitcake apple pie pudding jelly beans
                                    pie candy canes candy canes jelly-o. Tiramisu brownie gummi bears soufflé dessert cake.
                                </div>
                            </div>
                        </div>-->
                    </div>
                </section>
			</div>
		</div>
	</div>
</div>