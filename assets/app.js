import './styles/custom.scss';

window.$ = require('jquery');
window.bootstrap = require('bootstrap');
window.fortawesome = require('@fortawesome/fontawesome-free/js/all.js');

const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
