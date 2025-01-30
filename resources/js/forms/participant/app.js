import { createApp } from 'vue';
import Participant from './Participant.vue';

const app = createApp({});
app.component('participant-form', Participant);

if (document.getElementById('participant-form')) {
  app.mount('#participant-form');
}
