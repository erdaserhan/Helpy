import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    connect() {
        this.updateViewportWidthSidebar = this.updateViewportWidthSidebar.bind(this); // Bind once
        window.addEventListener("resize", this.updateViewportWidthSidebar);
        this.updateViewportWidthSidebar();
    }

    // disconnect() {
    //     window.removeEventListener("resize", this.updateViewportWidthSidebar);
    // }

    updateViewportWidthSidebar() {
        const sidebar = this.element;
        if (window.innerWidth <= 823) {
            sidebar.classList.remove("show");
        } else {
            sidebar.classList.add("show");
        }
    }
}