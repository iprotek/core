
if(!window.Vue){
    require('bootstrap'); 
    window.Vue = require('vue').default;
}
 

//DASHBOARD
//Vue.component('dashboard', require('./components/admin/Reports.vue').default); 


//Suggestion 
Vue.component('searches-view', require('../../components/manage/iprotek-data/searches/Index.vue').default);
Vue.component('modal-view', require('../../components/common/Modal.vue').default); 
Vue.component('swal', require('../../components/common/Swal.vue').default); 

const app = new Vue({
 el: '#main-content'
});