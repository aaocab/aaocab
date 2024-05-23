/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
document.write('<script src="/js/gozo/dco/common.js" type="text/javascript"></script>');


function submitselfie(hasImage = false) {
	var selfieimage = $('#uploadSelfie #Contact_ctt_profile_path').val();
	if (selfieimage == "" && !hasImage) {
		bootbox.alert({message: 'Please take a selfie with an ID card in your hand.', size: 'medium'});
		return false;
	}
	$("#uploadSelfie")[0].submit();
}
$("#optype").click(function () {
//	if (this.classList.contains("opened")) {
//		return;
//	}
//	$(".infoCards").removeClass("opened");
//	this.classList.add("opened");
//	$(".infoCards").addClass("collapsed");
//	$(".collapse").removeClass("show");

});


$("#accordion").on("hide.bs.collapse show.bs.collapse", e => {
	$(e.target)
			.prev()
			.find("i:last-child")
			.toggleClass("fa-minus fa-plus");
});


$(".backbtn").click(function () {
	location.href = "/operator/register";
});




