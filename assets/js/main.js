
// import barba from '@barba/core';


//Page Transitions
// Better to traverse the DOM thenleast possible
const loadingScreen = document.querySelector('.loading-screen')
const mainNavigation = document.querySelector('.main-navigation')

// Function to add and remove the page transition screen
function pageTransitionIn() {
  // GSAP methods can be chained and return directly a promise
  // but here, a simple tween is enough
  return gsap
    // .timeline()
    // .set(loadingScreen, { transformOrigin: 'bottom left'})
    // .to(loadingScreen, { duration: .5, scaleY: 1 })
    .to(loadingScreen, { duration: .5, scaleY: 1, transformOrigin: 'bottom left'});

    
}
// Function to add and remove the page transition screen
function pageTransitionOut(container) {
  // GSAP methods can be chained and return directly a promise
  return gsap
    .timeline({ delay: 1 }) // More readable to put it here
    .add('start') // Use a label to sync screen and content animation
    .to(loadingScreen, {
      duration: 0.5,
      scaleY: 0,
      skewX: 0,
      transformOrigin: 'top left',
      ease: 'power1.out'
    }, 'start')
    .to(loadingScreen, { duration: .5, scaleY: 1, transformOrigin: 'bottom left'});

    // .call(contentAnimation, [container], 'start')
}

// Function to animate the content of each page
function contentAnimation(container) {

  // Query from container
  $(container.querySelector('.green-heading-bg')).addClass('show')
  // GSAP methods can be chained and return directly a promise
  return gsap
    .timeline()
    .from(container.querySelector('.is-animated'), {
      duration: 0.5,
      translateY: 10,
      opacity: 0,
      stagger: 0.4
    })
    // .from(mainNavigation, { duration: .5, translateY: -10, opacity: 0})
}

// $(function() {
//   barba.init({
//     // We don't want "synced transition"
//     // because both content are not visible at the same time
//     // and we don't need next content is available to start the page transition
//     // sync: true,
//     transitions: [{
//       // NB: `data` was not used.
//       // But usually, it's safer (and more efficient)
//       // to pass the right container as a paramater to the function
//       // and get DOM elements directly from it
//        leave(data) {
//         // Not needed with async/await or promises
//         // const done = this.async();
//         data.current.container.remove()

//          pageTransitionIn()
//         // No more needed as we "await" for pageTransition
//         // And i we change the transition duration, no need to update the delay…
//         // await delay(1000)

//         // Not needed with async/await or promises
//         // done()

//         // Loading screen is hiding everything, time to remove old content!
//         const regex = new RegExp('/\/$/', 'gi');
//         let page = data.next.url.path.replace(/\/$/, '');
//         page = page.substr(1, page.length);
//         if (page === '') page = 'home';
//         $('body').removeClass(function (index, className) {
//           return (className.match(/(^|\s)page-\S+/g) || []).join(' ');
//         }).addClass('page-' + page);
      
//       },

//        enter(data) {
//          pageTransitionOut(data.next.container)
//       },
//       // Variations for didactical purpose…
//       // Better browser support than async/await
//       // enter({ next }) {
//       //   return pageTransitionOut(next.container);
//       // },
//       // More concise way
//       // enter: ({ next }) => pageTransitionOut(next.container),

//        once(data) {
//          contentAnimation(data.next.container);
//       }
//     }]
//   });

// });


//EndPage Transitions

gsap.registerPlugin(TextPlugin);
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
tl.from('.reveal2',{y:60, opacity:0, delay:0.5, duration:0.4, stagger:0.3});

jQuery(document).ready(function () {


  $(window).scroll(function () {
    $('.navbar').toggleClass('active', $(this).scrollTop() > 50);
    $('.navbar').toggleClass('fade', $(this).scrollTop() > 500);
    $('#menuToggler').addClass('white',$(this).scrollTop() > 50);
    $('#menuToggler').removeClass('white',$(this).scrollTop() > 500);

    if ($('.page-projekt').length) {
      $('#headerLogo').toggleClass('white', $(this).scrollTop() > 50);
      
    }
   


  });
  

  $('#sideMenu li').click(() => {
    $('#sideMenu').removeClass('active');
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
    TweenMax.to('.btn-smart__rect', 0.3, { scaleY: 1.03 });
  });

  $('.btn-smart').mousemove(function (e) {
    callParallax(e);
  });

  function callParallax(e) {
    parallaxIt(e, '.btn-smart__rect', $('.btn-smart'), 80);
    parallaxIt(e, '.btn-smart__button', $('.btn-smart'), 40);
  }

  function parallaxIt(e, target, parent, movement) {
    var $this = $(parent);
    var relX = e.pageX - $this.offset().left;
    var relY = e.pageY - $this.offset().top;

    TweenMax.to(target, 0.3, {
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
    if($('.navbar').hasClass('active')) {
      $('.navbar').removeClass('active');
      $('#menuToggler').removeClass('white');
    }

    //Side Menu animations
    if ($('#sideMenu').hasClass('active')) {
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


  });
});

