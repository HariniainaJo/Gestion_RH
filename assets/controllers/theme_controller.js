import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['lightBtn', 'darkBtn'];

    connect() {
        this.applyButtons(this.currentTheme());
    }

    setLight() { this.set('light'); }
    setDark() { this.set('dark'); }

    set(theme) {
        localStorage.setItem('zanova-theme', theme);
        document.documentElement.setAttribute('data-theme', theme);
        this.applyButtons(theme);
    }

    currentTheme() {
        return document.documentElement.getAttribute('data-theme') || 'light';
    }

    applyButtons(theme) {
        if (this.hasLightBtnTarget) this.lightBtnTarget.classList.toggle('active', theme === 'light');
        if (this.hasDarkBtnTarget) this.darkBtnTarget.classList.toggle('active', theme === 'dark');
    }
}
