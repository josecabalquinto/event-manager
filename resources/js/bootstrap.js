import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Function to get fresh CSRF token from DOM
function getCSRFTokenFromDOM() {
    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        return token.content;
    }
    return null;
}

// Function to refresh CSRF token from server
async function refreshCSRFTokenFromServer() {
    try {
        const response = await fetch('/csrf-token');
        const data = await response.json();

        // Update meta tag
        const metaTag = document.head.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            metaTag.setAttribute('content', data.token);
        }

        // Update axios headers
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = data.token;

        return data.token;
    } catch (error) {
        console.error('Failed to refresh CSRF token:', error);
        return null;
    }
}

// Configure initial CSRF token
const initialToken = getCSRFTokenFromDOM();
if (initialToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = initialToken;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Add request interceptor to ensure fresh token
window.axios.interceptors.request.use(function (config) {
    const token = getCSRFTokenFromDOM();
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token;
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

// Add response interceptor to handle 419 errors with retry
window.axios.interceptors.response.use(function (response) {
    return response;
}, async function (error) {
    if (error.response && error.response.status === 419) {
        console.log('CSRF token mismatch detected, attempting to refresh...');

        // Try to refresh token from server
        const newToken = await refreshCSRFTokenFromServer();

        if (newToken && error.config && !error.config._retry) {
            error.config._retry = true;
            error.config.headers['X-CSRF-TOKEN'] = newToken;

            // Retry the original request
            return window.axios.request(error.config);
        } else {
            // If refresh failed or already retried, reload the page
            console.log('CSRF token refresh failed, reloading page...');
            window.location.reload();
        }
    }
    return Promise.reject(error);
});
