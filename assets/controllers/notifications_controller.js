import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['badge'];

    markAllRead() {
        this.element.querySelectorAll('.notif-item.unread').forEach((el) => el.classList.remove('unread'));
        if (this.hasBadgeTarget) this.badgeTarget.remove();
    }
}
