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

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('header-menus', require('./components/manage/HeaderMenu.vue').default);
Vue.component('branch-modal-container', require('../components/manage/xrac/modals/ModalBranchContainer').default);
Vue.component('branch-selector', require('../components/manage/xrac/BranchSelector').default);

Vue.component('modal-view', require('../components/common/Modal.vue').default); 
Vue.component('swal_prompt', require('../components/common/Swal.vue').default); 

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin braadding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

var body = document.querySelector('body');
var message_container = document.createElement('DIV');
message_container.id = "branch-modal-container";
message_container.innerHTML = "<branch-modal-container /> ";
body.append(message_container);

 new Vue({
    el: '#branch-modal-container',
});

if(document.querySelector('#branch-selector')){
    new Vue({
        el: '#branch-selector',
    });
}




