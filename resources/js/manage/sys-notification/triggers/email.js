/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

if(!window.Vue){
    require('bootstrap');
    window.Vue = require('vue').default;
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


//Vue.component('header-menus', require('./components/manage/HeaderMenu.vue').default);
Vue.component('sys-notification-trigger-email', require('../../../components/manage/notification/scheduler/triggers/email/IndexList.vue').default);
Vue.component('modal-view', require('../../../components/common/Modal.vue').default); 
Vue.component('swal', require('../../../components/common/Swal.vue').default); 


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#main-content',
});
