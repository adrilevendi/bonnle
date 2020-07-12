jQuery(document).ready(function () {

    // init controller
    var controller = new ScrollMagic.Controller();

    // create a scene
    new ScrollMagic.Scene({
        duration: 100, // the scene should last for a scroll distance of 100px
        offset: 50 // start this scene after scrolling for 50px
    })
        .addTo(controller) // assign the scene to the controller
        .addIndicators();

    gsap.to("#bigCiricleIntro", {
        duration: 2, x: -50,
        ease: 'power1.out',
        onComplete: function () {
            gsap.to("#bigCiricleIntro", { duration: 2, x: 0, ease: 'power1.inOut' });
        }
    });


    var rotateRectScene = new ScrollMagic.Scene({triggerElement: "#developRect", duration: 200})
					.addTo(controller)
					.addIndicators() // add indicators (requires plugin)
					.on("update", function (e) {
                        // console.log(e)
                        // $('body').css("background", "rgba("+Math.random()+", 73, 25, "+Math.random()+")");
                        
					})
					// .on("enter leave", function (e) {
					// 	$("#state").text(e.type == "enter" ? "inside" : "outside");
					// })
					// .on("start end", function (e) {
					// 	$("#lastHit").text(e.type == "start" ? "top" : "bottom");
					// })
					.on("progress", function (e) {
                        
                        // $('.graphic-rect--shape').css("transform", "rotate("+26.33+e.progress.toFixed(3)+"deg)");
                        // $('.graphic-rect--shape').css("background", "rgba(#fd0054,"+Math.random()+")");
                        const current_margin = parseInt($('#developRect').css("margin-top"));
                        // console.log('current: ', current_margin);
                        if (e.target.controller().info("scrollDirection") === 'FORWARD') {
                        $('#developRect').css("margin-top",current_margin - e.progress.toFixed(3) * 10);

                        } else {
                        $('#developRect').css("margin-top",current_margin + e.progress.toFixed(3) * 10);
                            
                        }
                        // console.log(e.progress.toFixed(3));
                        // console.log(current_margin);
                        console.log();

					});
});

