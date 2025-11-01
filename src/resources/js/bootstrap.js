import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// es6 module
import MicroModal from 'micromodal';
MicroModal.init({
   disableScroll: true
}
);