/**
 * Global Toastr Handler for Livewire Components
 * Handles all toast notifications across the application
 */

// Configure toastr options globally
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

// Global Livewire event listeners for Toastr
document.addEventListener('livewire:init', () => {
    // Success notifications
    Livewire.on('toast-success', (message) => {
        toastr.success(message, 'Success');
    });

    // Error notifications
    Livewire.on('toast-error', (message) => {
        toastr.error(message, 'Error');
    });

    // Warning notifications
    Livewire.on('toast-warning', (message) => {
        toastr.warning(message, 'Warning');
    });

    // Info notifications
    Livewire.on('toast-info', (message) => {
        toastr.info(message, 'Info');
    });

    // Generic notification (defaults to success)
    Livewire.on('toast', (data) => {
        const type = data.type || 'success';
        const message = data.message || data;
        const title = data.title || (type.charAt(0).toUpperCase() + type.slice(1));
        
        switch(type) {
            case 'success':
                toastr.success(message, title);
                break;
            case 'error':
                toastr.error(message, title);
                break;
            case 'warning':
                toastr.warning(message, title);
                break;
            case 'info':
                toastr.info(message, title);
                break;
            default:
                toastr.success(message, title);
        }
    });

    // Generic 'toastr' event listener (for consistency with our components)
    Livewire.on('toastr', (event) => {
        const data = event[0] || event;
        const type = data.type || 'success';
        const message = data.message || 'Action completed.';
        const title = data.title || (type.charAt(0).toUpperCase() + type.slice(1));

        switch(type) {
            case 'success':
                toastr.success(message, title);
                break;
            case 'error':
                toastr.error(message, title);
                break;
            case 'warning':
                toastr.warning(message, title);
                break;
            case 'info':
                toastr.info(message, title);
                break;
            default:
                toastr.success(message, title);
        }
    });
});

// Helper functions for manual Toastr calls (if needed)
window.showToast = {
    success: (message, title = 'Success') => toastr.success(message, title),
    error: (message, title = 'Error') => toastr.error(message, title),
    warning: (message, title = 'Warning') => toastr.warning(message, title),
    info: (message, title = 'Info') => toastr.info(message, title)
};
