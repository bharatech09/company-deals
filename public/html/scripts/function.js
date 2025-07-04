
$(function () {     
    // ACCORDION Toggle (Multiple Items)
    if($('.acc .acc-head').length > 0)
    {
        $('.acc .acc-head').click(function(){
            var parent = $(this).parent();        
            parent.toggleClass('active');
            parent.find('.acc-info').stop().slideToggle();
            parent.siblings().find('.acc-info').hide();
            parent.siblings().removeClass('active');
        }); 
    }    
}); 

// Js for Mobile Navigation
$(function () {
    //Right to Left Open Menu
    if($('.navToggle').length > 0){
        $('.navToggle').on('click', function () {
            $('.mobnav-grid').toggleClass('open-mobile-nav');            
            $('html').toggleClass('hidescroll');
        });
    };    
});

$(function () {
    $('.menu-link').on('click', function(event) {
        event.preventDefault();
        var $menuItem = $(this).parent('.menu-item');
        var $dropdown = $(this).next('.menu-info');

        // Close all open dropdowns except the one being clicked
        $('.menu-info').not($dropdown).slideUp();
        $('.menu-item').not($menuItem).removeClass('active');

        // Toggle the dropdown menu and add/remove active class
        if ($dropdown.is(':visible')) {
            $dropdown.slideUp();
            $menuItem.removeClass('active');
        } else {
            $dropdown.slideDown();
            $menuItem.addClass('active');
        }
    });

    // Close the dropdown if clicking outside of it
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.menu-item').length) {
            $('.menu-info').slideUp();
            $('.menu-item').removeClass('active');
        }
    });
});


// Js for Mobile Dashboard Filter
$(function () {
    //Left to Right Open Menu
    if($('.navToggle2').length > 0){
        $('.navToggle2').on('click', function () {
            $('.dashboard-nav').toggleClass('open-filternav');            
            $('html').toggleClass('hidescroll');
        });
    };    
});


// flickity Slider
$(function(){
    // Group Slider
    if ($('.groupSlider').length > 0) {
        $('.groupSlider').flickity({
            freeScroll: true,
            wrapAround: true,
            groupCells: 1,
            contain: true,
            autoPlay: true,
            autoPlay: 20000,
            pauseAutoPlayOnHover: false,
            pageDots: true,
            prevNextButtons: true,
        });
    } 

    // Single Slider
    if($('.slideSet').length > 0){
        $('.slideSet').flickity({
            //options            
            cellAlign: 'left',
            wrapAround: true,
            groupCells: "100%",
            contain: true,
            pageDots: true,
            prevNextButtons: true,
            imagesLoaded: true,
            adaptiveHeight: true, 
            draggable: true,
        });
    };    
});


// Counter Slider
$(function(){ 

    // var counters = $(".count");
    // var countersQuantity = counters.length;
    // var counter = [];

    // for (i = 0; i < countersQuantity; i++) {
    //     counter[i] = parseInt(counters[i].innerHTML);
    // }

    // var count = function(start, value, id) {
    //     var localStart = start;
    //     setInterval(function() {
    //         if (localStart < value) {
    //             localStart++;
    //             counters[id].innerHTML = localStart;
    //         }
    //     }, 40);
    // }

    // for (j = 0; j < countersQuantity; j++) {
    //     count(0, counter[j], j);
    // }
});


$(function(){ 
    var spaceSlider = document.getElementById('space_slider');
    noUiSlider.create(spaceSlider, {
        start: [ 100, 9999 ],
        connect: true,
        tooltips: true,
        range: {
            'min': 100,
            'max': 9999
        }
    });	
    
    var askPriceSlider = document.getElementById('ask_price_slider');
    noUiSlider.create(askPriceSlider, {
        start: [ 100, 9999 ],
        connect: true,
        tooltips: true,
        range: {
            'min': 100,
            'max': 9999
        }
    });	
});






