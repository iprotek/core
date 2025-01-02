
require('bootstrap');

window.Vue = require('vue').default;
 

//DASHBOARD
//Vue.component('dashboard', require('./components/admin/Reports.vue').default); 
Vue.component('xrole-index', require('../../components/manage/xrac/Xrole.vue').default);
Vue.component('swal', require('../../components/common/Swal.vue').default);  


const app = new Vue({
 el: '#main-content'
});
