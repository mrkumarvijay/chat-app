import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const token =
  localStorage.getItem('token') ||
  localStorage.getItem('access_token') ||
  '';

console.log('echo token exists:', !!token);

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
  wsPort: Number(import.meta.env.VITE_REVERB_PORT || 8080),
  forceTLS: false,
  enabledTransports: ['ws'],

  authEndpoint: '/broadcasting/auth',
  auth: {
    headers: {
      Authorization: token ? `Bearer ${token}` : '',
      Accept: 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
  },
});
