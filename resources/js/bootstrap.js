/**
 * Bootstrap JS - Core setup file
 *
 * @updated 2025-07-30 05:57:06 by mulyadafa
 * @fixed Removed problematic imports to avoid module resolution errors
 */

// IMPORTANT: No module imports here - use window globals
// Do NOT use: import axios from 'axios';

// Set up axios defaults from the global window.axios
if (window.axios) {
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    // Set up CSRF token for AJAX requests
    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
}

// Check if libraries are loaded correctly
document.addEventListener('DOMContentLoaded', function () {
    console.log('Bootstrap.js initialized');
});
