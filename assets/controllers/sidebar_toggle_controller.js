import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ "toggle", "sidebar" ]

    connect()
    {
        window.addEventListener('show.bs.collapse', this.show.bind(this))
        window.addEventListener('hide.bs.collapse', this.hide.bind(this))
    }

    show()
    {
        this.toggleTarget.setAttribute('aria-label', 'Fermer le menu')
    }

    hide()
    {
        this.toggleTarget.setAttribute('aria-label', 'Ouvrir le menu')
    }
}