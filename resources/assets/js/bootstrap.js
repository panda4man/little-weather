import Axios from 'axios';
import Vue from 'vue';
import momenttz from 'moment-timezone';

Vue.prototype.$http = Axios;
Vue.prototype.$http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    Vue.prototype.$http.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
