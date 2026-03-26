import axios from 'axios';

// Make Axios available everywhere in the browser code.
window.axios = axios;

// Mark requests as AJAX so Laravel can spot them easily.
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
