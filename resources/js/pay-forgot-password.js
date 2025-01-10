
require('bootstrap');

window.Vue = require('vue').default;
 

//DASHBOARD
//Vue.component('dashboard', require('./components/admin/Reports.vue').default); 


//Suggestion  
Vue.component('forgot-password-link', require('./components/pay-account/forgot-password/ForgotPasswordLink.vue').default);

Vue.component('swal', require('./components/common/Swal.vue').default);
Vue.component('modal-view', require('./components/common/Modal.vue').default); 

const app = new Vue({
 el: '#main-content'
});