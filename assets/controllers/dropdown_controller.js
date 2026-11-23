import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['menu'];

    connect() {
        this.onClickOutside = this.onClickOutside.bind(this);
        document.addEventListener('click', this.onClickOutside);
    }

    disconnect() {
        document.removeEventListener('click', this.onClickOutside);
    }

    toggle(event) {
        event.stopPropagation();
        const willOpen = !this.menuTarget.classList.contains('open');
        document.querySelectorAll('.notif-dropdown.open, .profile-dropdown.open').forEach((el) => {
            if (el !== this.menuTarget) el.classList.remove('open');
        });
        this.menuTarget.classList.toggle('open', willOpen);
    }

    onClickOutside(event) {
        if (!this.element.contains(event.target)) {
            this.menuTarget.classList.remove('open');
        }
    }
}
