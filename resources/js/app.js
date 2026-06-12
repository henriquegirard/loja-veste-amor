import Vue from 'vue';
import 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.js';

window.Vue = Vue;
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// The Vue instance is initialized directly in the blade files as requested by the architecture doc.
