
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
    var counters = $(".count");
    var countersQuantity = counters.length;
    var counter = [];

    for (i = 0; i < countersQuantity; i++) {
        counter[i] = parseInt(counters[i].innerHTML);
    }

    var count = function(start, value, id) {
        var localStart = start;
        setInterval(function() {
            if (localStart < value) {
                localStart++;
                counters[id].innerHTML = localStart;
            }
        }, 40);
    }

    for (j = 0; j < countersQuantity; j++) {
        count(0, counter[j], j);
    }
});

    $(document).ready(function () {
    $(document).on("keypress",'input[type="number"]', function (e) {
        $("div.text-danger").remove();
        $(this).removeClass("text-danger");
        let charCode = e.which ? e.which : e.keyCode;
        let inputVal = $(this).val();
        let min = parseFloat($(this).attr("min"));
        // Allow control keys (backspace, arrow keys, etc.)
        if (e.ctrlKey || e.metaKey || charCode === 8 || charCode === 37 || charCode === 39) {
            return true;
        }

        // Allow numbers (0–9)
        if (charCode >= 48 && charCode <= 57) {
            return true;
        }

        // Allow minus sign (-) only at the beginning
        if(min < 0){
            if (charCode === 45 && inputVal === "") {
                return true;
            }

        }
        $(this).addClass("text-danger");
        if(min < 0){
            $(this).parent("div").after('<div class="text-danger">only -ive or +ive number are allowed.</div>');
        }else{
            $(this).parent("div").after('<div class="text-danger">only +ive number are allowed.</div>');
        }
        $(this).focus();
        // Prevent other characters
        e.preventDefault();
        return false;
    });

    // Validate on keyup to ensure the value respects the min attribute
    $("input[type='number']").on("keyup", function () {
        let min = parseFloat($(this).attr("min"));
        let value = parseFloat($(this).val());

        if (!isNaN(min) && value < min) {
            $(this).val(min);
        }
    });
    $(document).on('keypress', '.threedigit', function(event) {
        if(this.value.length >= 3){
            this.value = this.value.slice(0, 2)
            $(this).addClass("text-danger");
            $(this).parent("div").after('<div class="text-danger">only three digit number are allowed.</div>');
            $(this).focus();

        }
    });
    $(document).on('keypress', '.fourdigit', function(event) {

        if(this.value >= 0 && this.value.length >= 4){
            this.value = this.value.slice(0, 3)
            $(this).addClass("text-danger");
            $(this).parent("div").after('<div class="text-danger">only four digit number are allowed.</div>');
            $(this).focus();
        }else if(this.value < 0 && this.value.length >= 5){
            this.value = this.value.slice(0, 4)
            $(this).addClass("text-danger");
            $(this).parent("div").after('<div class="text-danger">only four digit number are allowed.</div>');
            $(this).focus();
        }
    });

    $(document).on('submit', 'form', function(event) {
        var children = $(this).find(".pricewithunit");
        if(children.length > 0){
            $(this).find("div.text-danger").remove();
            $(this).find("select").removeClass("text-danger");
            $(children).each(function() {
                var firstNumberInput = $(this).find('input[type="number"]').first();
                var firstSelect = $(this).find('select').first();
                if( firstNumberInput.val() != ""  && firstSelect.val() == ""){
                    firstSelect.addClass("text-danger");
                    firstSelect.parent("div").after('<div class="text-danger">Please select unit of amount.</div>');
                    firstSelect.focus();
                    event.preventDefault(); 
                }
                
            });
        }    
    });

});