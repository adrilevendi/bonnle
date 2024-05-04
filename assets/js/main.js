
import barba from '@barba/core';
import PageTransitions from './modules/pageTransitions.js';

function dmp(argument) {
  return console.log(argument)
}

const page_transitions = new PageTransitions();
// page_transitions.init();



gsap.registerPlugin(TextPlugin);


// Hero text animation

var textTl = gsap.timeline({ delay: 2});

const initalHeroWord = $('#changeTextHero').text();

let sentences = [];
$('.change-hero-text-values').each(function(e) {

  sentences.push($(this).text());

});

//  textTl.to('#changeTextHero', {duration: 1.3, text: "", ease: "none", delay: 0.2})

//     textTl.to('#changeTextHero', {duration: 2, text: $(this).text(), ease: "none"});

function createTypewritingAnimation(sentences) {

  for (var i = sentences.length - 0; i > 0; i++) {
    if((i % 2) == 0) {
  textTl.to('#changeTextHero', {duration: 1, text: sentences[i], ease: "none"});

    } else {
    
      const reverseTween = gsap.to('#changeTextHero', {duration: 2, text: sentences[i], ease: "none"}).reverse(0);
  textTl.add(reverseTween);

    }
  }

}

dmp(sentences.length);
// createTypewritingAnimation(sentences);

// End Hero text animation



gsap.from('.reveal',{y:60, opacity:0, delay:0.1, duration:0.4, stagger:0.2});

var tl = gsap.timeline();
// gsap
// .timeline({ delay: 1 }) // More readable to put it here
// .add('start') // Use a label to sync screen and content animation
// .to(loadingScreen, {
//   duration: 0.5,
//   scaleY: 0,
//   skewX: 0,
//   transformOrigin: 'top left',
//   ease: 'power1.out'
// }, 'start')
// .to(loadingScreen, { duration: .5, scaleY: 1, transformOrigin: 'bottom left'});
tl.from('#projectTitle',{x:-160, delay:0.2, opacity:0.3, duration:1.2, stagger:0.2});
tl.to('#projectTitle',{x:2060, delay:0.2, opacity:0.3, duration:1.2, stagger:0.2});

tl.from('.reveal2',{y:60, opacity:0, delay:0.5, duration:0.4, stagger:0.3});

jQuery(document).ready(function () {

  // console.log("Scrolltop: ", $("#menuToggler").scrollTop());

  $(window).scroll(function () {
    console.log("Scrolltop: ", $(this).scrollTop());
    $('.navbar').toggleClass('active', $(this).scrollTop() > 50);
    $('.navbar').toggleClass('minimize', $(this).scrollTop() > 500);
    $('#menuToggler').toggleClass('white', $(this).scrollTop() > 50);
    // $('.navbar').toggleClass('fade', $(this).scrollTop() > 500);

    if($(this).scrollTop() > 50) {
      $(this).addClass('white');


    } else {
      $('#headerLogo').removeClass('white');
    }

    if($('#menuToggler').scrollTop() > 500) {
      // $('#menuToggler').addClass('white');

    } else {
      // $('#menuToggler').removeClass('white');

    }

    if ($('.page-projekt').length) {
      // $('#headerLogo').toggleClass('white', $(this).scrollTop() > 50);
      
    }
   


  });
  

  $('#sideMenu li').click(() => {
    $('#sideMenu').removeClass('active');
    $('#headerLogo').removeClass('active');
    $('#menuToggler').removeClass('active');

  });

  gsap.registerPlugin(ScrollTrigger);

  // gsap.to("#developRect", {
  //   scrollTrigger: {
  //     trigger: "#developRect",
  //     start: "top center",
  //     // end: "center center",
  //     scrub: true
  //   },
  //   // x: 300,
  //   rotation: 90,
  //   duration: 3
  // });

  gsap.to("#designGraphicScreenshot1", {
    scrollTrigger: {
      trigger: "#designGraphicScreenshot1",
      start: "top center",
      // end: "center center",
      scrub: true
    },
    y: 100,
    duration: 1
  });

  gsap.to("#designGraphicScreenshot2", {
    scrollTrigger: {
      trigger: "#designGraphicScreenshot2",
      start: "top center",
      // end: "center center",
      scrub: true
    },
    y: -100,
    duration: 1
  });

  gsap.to("#designGraphicRect", {
    scrollTrigger: {
      trigger: "#designGraphicScreenshot1",
      start: "center center",
      // end: "center center",
      scrub: true
    },
    // x: 200,
    rotation: -100,
    duration: 1
  });





  //button follow cursor
  $('.btn-smart').mouseleave(function (e) {
    TweenMax.to(this, 0.3, { height: 150, width: 250 });
    TweenMax.to('.btn-smart__rect, .btn-smart__button', 0.3, { scale: 1, x: 0, y: 0 });
  });

  $('.btn-smart').mouseenter(function (e) {
    TweenMax.to(this, 0.3, { height: 200, width: 250 });
    TweenMax.to($(e.target).find('.btn-smart__rect'), 0.3, { scaleY: 1.03 });
    console.log($(e.target).find('.btn-smart__rect'));

  });

  $('.btn-smart').mousemove(function (e) {
    callParallax(e);
  });

  function callParallax(e) {
    parallaxIt(e, '.btn-smart__rect', $('.btn-smart'), 80);
    parallaxIt(e, '.btn-smart__button', $('.btn-smart'), 40);
  }

  function parallaxIt(e, target, parent, movement) {
    var $this = $(e.target);
    var relX = e.pageX - $this.offset().left;
    var relY = e.pageY - $this.offset().top;
    var rect = $this.find('.btn-smart__rect');
    TweenMax.to(rect, 0.3, {
      x: (relX - $this.width() / 2) / $this.width() * movement,
      y: (relY - $this.height() / 2) / $this.height() * movement,
      ease: Power2.easeOut
    });
  }


  //Animate Works
  $('.work__wrapper').mouseleave(function (e) {
    // TweenMax.to(this, 0.3, {height: 150, width: 230});
    // TweenMax.to('.work__wrapper, .work__image', 0.3,{scale:1, x: 0, y: 0});
  });

  $('.work__wrapper').mouseenter(function (e) {
    // TweenMax.to(this, 0.3, {height: 200, width: 200});
    // TweenMax.to(this, 0.3, { scale: 1.3 });
  });
  $('.work__wrapper').mousemove(function (e) {
    // parallaxWork(e, '.work__image', this, 40);
  });


  function parallaxWork(e, target, parent, movement, trigger) {
    // console.log($(parent));

    var $this = $(parent);
    var trigger = '';
    var relX = e.pageX - $this.offset().left;
    var relY = e.pageY - $this.offset().top;
    TweenMax.to(parent + " " + target, 0.3, {
      x: (relX - $this.width() / 2) / $this.width() * movement,
      y: (relY - $this.height() / 2) / $this.height() * movement,
      ease: Power2.easeOut
    });
  }

  $('#menuToggler').click(function () {
    $('#sideMenu').toggleClass('active');
    $(this).toggleClass('active');  
    $('body').toggleClass('overflow-hidden');
    if($('.navbar').hasClass('active')) {
      $('.navbar').removeClass('active');
      $('#menuToggler').removeClass('white');
      $('#headerLogo').removeClass('white');

    }

    //Side Menu animations
    if ($('#sideMenu').hasClass('active')) {
      $('#headerLogo').addClass('white');
      var tl = gsap.timeline();
      tl.from("#sideMenu li", {
        opacity: 0,
        // x: 200,
        y: 190,
        duration: 0.4,
        stagger:0.1,
        delay: 0.6,
        ease: Power2.Out,
      });
     
    }


    //EndSide Menu animations

    //Hero text change

  });
});

