/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import {store} from '../js/store/store';
import 'view-design/dist/styles/iview.css';
import {router} from '../js/router/router';
import ViewUI from 'view-design';
import common from './common';
import swal from 'sweetalert';

router.beforeEach((to, from, next) => {
  ViewUI.LoadingBar.start();
 
  next();
});

router.afterEach(route => {
  ViewUI.LoadingBar.finish();
});


window.Vue = require('vue');

Vue.use(ViewUI);
Vue.mixin(common);





Vue.component('main-app', require('./components/MainApp.vue').default);


const app = new Vue({
    store,
    router,
    el: '#app',
});
