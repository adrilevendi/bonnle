jQuery(document).ready(function () {

    let overlayMenu = $('.overlay-menu');
    $('#menuToggler').click(() => {
        overlayMenu.fadeToggle('200');
        overlayMenu.toggleClass('opacity-1', 200);
        $('.navbar-wrapper').toggleClass('menu-open');
        $('#menuToggler').toggleClass('x');
        $('#headerLogo').toggleClass('bright-7');
    });

    // Handling click events for dropdowns servizi
    $('.servizi').children('a')
        .attr('type', 'button')
        .attr('disabled', 'disabled')
        .removeAttr('href');
    const dropdownBtn = "<span class='expand-dropdown'>+</span>";
    overlayMenu.find('.servizi').find('a').append(dropdownBtn);
    //overlayMenu.find('.servizi').append( '<ul class="list-unstyled dropdown-list"></ul>');

    var openedService = false;

    overlayMenu.find('.servizi').click(function () {
        if(openedService === true) {
            $(this).parent().find('.dropdown-list__item').fadeOut(100);
            openedService =false;
        }   else {


            const children = JSON.parse($(this).children('a').attr('data-children'));
            // console.log(children);
            let items = '';
            for (child in children) {
                items += "<li class='nav__item" + children[child].classes.join(' ') + " dropdown-list__item'><a class='nav__link dropdown-list__link' href='" + children[child].url + "'>" + children[child].name + "</a></li>";
            }
            let list = '<ul class="list-unstyled dropdown-list">' + items + '</ul>';
            // console.log(items);
            $(this).after(items);
            openedService = true;
        }
    });


    $('.navbar-wrapper').find('.servizi').click(function () {
        $('.navbar__drop-wrapper').fadeToggle(200);
        const children = JSON.parse($(this).children('a').attr('data-children'));
          // console.log(children);
          let items = '';
        for (child in children) {
            items += "<li class='"+children[child].classes.join(' ')+"'><a href='"+children[child].url+"'>"+children[child].name+"</a></li>";
        }
        // console.log(items);
        $('.dropdown').children('ul').html(items);
    })

    overlayMenu.find('a').click( function(e) {
        if($(this).attr("href") == "") {
            e.preventDefault();
            const heightValue = $(this).next().children().height() + 50;
            if($(this).hasClass("active") === true) {
                $(this).removeClass("active");
                $(this).next().height("0px");
            } else {
                $(this).next().height(heightValue);
                $(this).addClass("active");
            }
        }
    });



});

