//require('bootstrap');

window.Vue = require('vue').default;
 

//DASHBOARD
//Vue.component('dashboard', require('./components/admin/Reports.vue').default); 


//Suggestion 
Vue.component('company-details-view', require('../components/manage/company-details/Index.vue').default); 
Vue.component('data-table', require('../components/common/DataTable.vue').default); 
Vue.component('modal-view', require('../components/common/Modal.vue').default); 
Vue.component('swal', require('../components/common/Swal.vue').default); 
Vue.component('page-footer', require('../components/common/PageFooter.vue').default); 


const app = new Vue({
 el: '#main-content'
});
