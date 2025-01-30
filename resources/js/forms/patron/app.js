import { createApp } from 'vue';
import Patron from './Patron.vue';

const app = createApp({});
app.component('patron-form', Patron);

if (document.getElementById('patron-form')) {
  app.mount('#patron-form');
}
