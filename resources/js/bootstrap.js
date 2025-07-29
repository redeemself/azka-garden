// resources/js/bootstrap.js

// Import modules using ES module syntax
import _ from 'lodash';
import axios from 'axios';

// Make lodash and axios globally available
window._ = _;
window.axios = axios;

// Set default headers for axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Add additional bootstrap logic below if needed
