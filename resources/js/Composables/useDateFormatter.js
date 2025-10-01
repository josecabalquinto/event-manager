/**
 * Date formatting utilities for the Event Manager application
 */

export function useDateFormatter() {
    /**
     * Format event date and time for display
     * @param {string} eventDate - Date string (e.g., "2025-10-10")
     * @param {string} eventTime - Time string (e.g., "14:30:00")
     * @returns {string} Formatted date and time (e.g., "October 10, 2025 at 2:30PM")
     */
    const formatEventDateTime = (eventDate, eventTime) => {
        try {
            // Parse the date
            const date = new Date(eventDate);

            // Format the date part
            const dateOptions = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const formattedDate = date.toLocaleDateString('en-US', dateOptions);

            // Handle time formatting
            let timeStr = '';
            if (eventTime) {
                // Parse time string (assuming format like "14:30:00")
                const [hours, minutes] = eventTime.split(':');
                const hour24 = parseInt(hours);
                const hour12 = hour24 === 0 ? 12 : hour24 > 12 ? hour24 - 12 : hour24;
                const ampm = hour24 >= 12 ? 'PM' : 'AM';
                timeStr = ` at ${hour12}${minutes !== '00' ? ':' + minutes : ''}${ampm}`;
            }

            return `${formattedDate}${timeStr}`;
        } catch (error) {
            console.error('Date formatting error:', error);
            return `${eventDate} ${eventTime || ''}`.trim();
        }
    };

    /**
     * Format full datetime for timestamps
     * @param {string} datetime - ISO datetime string
     * @returns {string} Formatted datetime (e.g., "Oct 10, 2025 at 2:30PM")
     */
    const formatDateTime = (datetime) => {
        try {
            if (!datetime) return 'Not available';

            const date = new Date(datetime);
            const dateOptions = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            const timeOptions = {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            };

            const formattedDate = date.toLocaleDateString('en-US', dateOptions);
            const formattedTime = date.toLocaleTimeString('en-US', timeOptions);

            return `${formattedDate} at ${formattedTime}`;
        } catch (error) {
            console.error('DateTime formatting error:', error);
            return datetime;
        }
    };

    /**
     * Format date only (no time)
     * @param {string} dateString - Date string
     * @returns {string} Formatted date (e.g., "October 10, 2025")
     */
    const formatDate = (dateString) => {
        try {
            const date = new Date(dateString);
            const dateOptions = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return date.toLocaleDateString('en-US', dateOptions);
        } catch (error) {
            console.error('Date formatting error:', error);
            return dateString;
        }
    };

    /**
     * Format time only (no date)
     * @param {string} timeString - Time string (e.g., "14:30:00")
     * @returns {string} Formatted time (e.g., "2:30PM")
     */
    const formatTime = (timeString) => {
        try {
            if (!timeString) return '';

            const [hours, minutes] = timeString.split(':');
            const hour24 = parseInt(hours);
            const hour12 = hour24 === 0 ? 12 : hour24 > 12 ? hour24 - 12 : hour24;
            const ampm = hour24 >= 12 ? 'PM' : 'AM';

            return `${hour12}${minutes !== '00' ? ':' + minutes : ''}${ampm}`;
        } catch (error) {
            console.error('Time formatting error:', error);
            return timeString;
        }
    };

    return {
        formatEventDateTime,
        formatDateTime,
        formatDate,
        formatTime
    };
}