import { createApp } from 'vue'
import Swal from '../components/common/Swal.vue'
import SmsSenderView from '../components/manage/sms-sender/Index.vue'
import ModalView from '../components/common/Modal.vue'

const el = document.getElementById('main-content')
const props = JSON.parse(el.dataset.props)

const app = createApp(SmsSenderView, props)
// Register component globally (Vue 3 way)
app.component('modal-view', ModalView)
app.component('swal', Swal)
// Mount app
app.mount('#main-content');