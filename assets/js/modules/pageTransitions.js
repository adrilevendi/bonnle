import $ from "jquery";
import barba from '@barba/core';

class PageTransitions {

    run() {
        this.ready();
        console.log("Page Trasdnsitions");

    }

    ready() {
        console.log("Page Transitions");
        $('body').css('background', 'black');
    }

}

const pageTransitions = new PageTransitions();
export default pageTransitions;
