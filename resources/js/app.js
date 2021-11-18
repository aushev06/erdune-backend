require('./bootstrap');
require('./components/forms/UserForm');
// require('./components/forms/theme.form');
require('./components/forms/Theme');
require('./components/forms/PostForm');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

