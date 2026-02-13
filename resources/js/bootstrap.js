import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Laravel Echo and Pusher for WebSocket (Reverb)
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Initialize Echo only if environment variables are available
const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;
let reverbHost = import.meta.env.VITE_REVERB_HOST ?? window.location.hostname;
const reverbPort = import.meta.env.VITE_REVERB_PORT ?? 8080;
const reverbScheme = import.meta.env.VITE_REVERB_SCHEME ?? 'http';

// Strip protocol from host if present (wsHost should be just hostname/IP)
if (reverbHost) {
    reverbHost = reverbHost.replace(/^https?:\/\//, '').replace(/^wss?:\/\//, '');
    // Remove trailing slash if present
    reverbHost = reverbHost.replace(/\/$/, '');
}

if (reverbKey) {
    // Configure Echo for Reverb (offline WebSocket)
    // Note: Pusher JS requires cluster option, but Reverb doesn't use it
    // We set it to 'mt1' (a valid Pusher cluster) to satisfy Pusher JS validation
    // Reverb will ignore this and use wsHost/wsPort instead
    const isSecure = reverbScheme === 'https';
    const echoConfig = {
        broadcaster: 'pusher',
        key: reverbKey,
        cluster: 'mt1', // Required by Pusher JS but ignored by Reverb
        wsHost: reverbHost,
        wsPort: reverbPort,
        wssPort: reverbPort,
        forceTLS: isSecure,
        // Only enable the appropriate transport based on scheme
        // This prevents trying wss:// when server only supports ws://
        enabledTransports: isSecure ? ['wss'] : ['ws'],
        disableStats: true,
    };

    window.Echo = new Echo(echoConfig);

    // Connection status handlers
    window.Echo.connector.pusher.connection.bind('connected', () => {
        // WebSocket connected
    });

    window.Echo.connector.pusher.connection.bind('disconnected', () => {
        // WebSocket disconnected
    });

    window.Echo.connector.pusher.connection.bind('error', (err) => {
        // WebSocket error
    });
}
