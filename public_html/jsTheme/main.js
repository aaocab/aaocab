


           

 $('.service-slider').owlCarousel({
    loop:true,
    margin:10,
    autoplay:true,
    dots:false,
    nav: true,
    navText: ["<img src='images/arrow-left.svg'>","<img src='images/arrow-right.svg'>"],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:3
        }
    }
})


 $('.slider_2').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    dots:false,
    nav: true,
    navText: ["<img src='images/arrow_white_left.png'>","<img src='images/arrow_white_right.png'>"],
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:2
        }
    }
})



 // $(".search_type_btn").click(function(){      
 //        $(".short_by_list").toggle();
 //    });  



$(window).scroll(function() {
    if ($(this).scrollTop()> 500) {
        $('#toTop').fadeIn();
    } else {
        $('#toTop').fadeOut();
    }
});



$("#toTop").click(function () {
   //1 second of animation time
   //html works for FFX but not Chrome
   //body works for Chrome but not FFX
   //This strange selector seems to work universally
   $("html, body").animate({scrollTop: 100}, 1000);
});



  $(".lazy").slick({
                    lazyLoad: 'ondemand', // ondemand progressive anticipated
                    infinite: true,
                    dots: false,
                    arrows: true,
                    slidesToShow: 1,
                    adaptiveHeight: false


});






