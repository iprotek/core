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
Vue.component('message-notification', require('../components/manage/message/Message.vue').default);
Vue.component('message-container', require('../components/manage/message/ChatBoxCointainer.vue').default);
Vue.component('helpdesk-notification', require('../components/manage/message/helpdesk/Helpdesk.vue').default);
Vue.component('helpdesk-table', require('../components/manage/message/helpdesk/HelpdeskTable.vue').default);

Vue.component('add-edit-ticket-modal', require('../components/manage/message/helpdesk/AddEditTicketModal.vue').default);

Vue.component('modal-view', require('../components/common/Modal.vue').default); 
Vue.component('swal', require('../components/common/Swal.vue').default); 

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

var body = document.querySelector('body');
var message_container = document.createElement('DIV');
message_container.id = "message-container";
message_container.innerHTML = "<message-container></message-container><add-edit-ticket-modal></add-edit-ticket-modal> <swal ></swal> ";
body.append(message_container);
message_container.classList.add("sticky-bottom");
message_container.classList.add("w-100");
message_container.setAttribute("style", "position: fixed; bottom: 0px; padding-right:70px; z-index:10000; pointer-events:none;");


 new Vue({
    el: '#message-el',
});
 new Vue({
    el: '#helpdesk-el',
});


new Vue({
    el: '#message-container',
});





