console.log('sdsd');
jQuery(document).ready(function () {

    // let overlayMenu = $('.overlay-menu');
    // $('#menuToggler').click(() => {
    //     overlayMenu.fadeToggle('200');
    //     overlayMenu.toggleClass('opacity-1', 200);
    //     $('.navbar-wrapper').toggleClass('menu-open');
    //     $('#menuToggler').toggleClass('x');
    //     $('#headerLogo').toggleClass('bright-7');
    // });
    
        console.log($('#content').children('.wrapper')[0]);
    
        var slidepage = new slidePage({
            slideContainer: '.wrapper',
            slidePages: '.slide-item',
            page: 1,
            refresh: true,
            dragMode: false,
            useWheel: true,
            useSwipe: true,
            useAnimation : true,
        
            // Events
            before: function(origin,direction,target){},
            after: function(origin,direction,target){},
         });
});

