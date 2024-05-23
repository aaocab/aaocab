<style>
.downHere{ padding-top: 90px; padding-bottom: 40px;}

@media (min-width: 320px) and (max-width: 767px) {
.app-imgs{ text-align: center;}
}
@media (min-width: 768px) and (max-width: 991px) {
.app-imgs{ text-align: center;}
}
</style>
<div class="container">
	<div class="row">
		<div class="col-12 col-lg-10 offset-lg-1 pt15 pb15" style="background: #eff5ff;">
			<div class="row">
				<div class="col-lg-6 text-center downHere">
					<p class="font-18 weight500">Download app here:</p>
					<a href="<?= $android ?>" target="_top"><img src="/images/app-google.png" alt="" width="140" height="46" class=""></a>
					<a href="<?= $ios ?>" target="_top"><img src="/images/app-store.png" alt="" width="140" height="46" class="lozad entered loaded" data-ll-status="loaded"></a>
				</div>
				<div class="col-lg-6 app-imgs"><img src="/images/img-2022/app-img-a.png" alt="" width="260"></div>
			</div>
		</div>
	</div>
</div>

