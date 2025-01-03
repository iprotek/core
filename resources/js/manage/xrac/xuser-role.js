
require('bootstrap');

window.Vue = require('vue').default;
 

//DASHBOARD
//Vue.component('dashboard', require('./components/admin/Reports.vue').default); 
Vue.component('xuser-role-index', require('../../components/manage/xrac/XuserRole.vue').default);
Vue.component('swal', require('../../components/common/Swal.vue').default); 
Vue.component('modal-view', require('../../components/common/Modal.vue').default);  


const app = new Vue({
 el: '#main-content'
});
