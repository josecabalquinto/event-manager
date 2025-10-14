import axios from 'axios';

export function useCsrfToken() {
    const refreshCsrfToken = async () => {
        try {
            const response = await axios.get('/csrf-token');
            const token = response.data.token;

            // Update meta tag
            const metaTag = document.head.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                metaTag.setAttribute('content', token);
            }

            // Update axios default headers
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

            return token;
        } catch (error) {
            console.error('Failed to refresh CSRF token:', error);
            return null;
        }
    };

    const getCurrentCsrfToken = () => {
        const metaTag = document.head.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : null;
    };

    const handleCsrfError = async (callback) => {
        const token = await refreshCsrfToken();
        if (token && callback) {
            // Retry the callback with fresh token
            return callback();
        } else {
            // If refresh failed, reload the page
            window.location.reload();
        }
    };

    return {
        refreshCsrfToken,
        getCurrentCsrfToken,
        handleCsrfError
    };
}