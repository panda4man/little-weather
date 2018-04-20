import Vue from 'vue';
import App from './components/App.vue';
import './bootstrap';

const app = new Vue({
    el: '#app',
    render: h => h(App)
});
