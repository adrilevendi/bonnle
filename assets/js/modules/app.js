import $ from "jquery";
class App {

    run() {
        this.ready();
    }

    ready() {
        console.log("It's ready!");
    }

}

const app = new App();
export default app;
