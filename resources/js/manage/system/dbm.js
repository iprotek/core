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

Vue.component('dbm-view', require('../../components/manage/system/dbm/Index.vue').default);
//Vue.component('branch-selector', require('../components/manage/xrac/BranchSelector').default);

Vue.component('modal-view', require('../../components/common/Modal.vue').default); 
Vue.component('swal', require('../../components/common/Swal.vue').default); 

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin braadding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 
new Vue({
    el: '#main-content',
}); 




