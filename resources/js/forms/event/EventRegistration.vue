<template>
  <template v-if="formSuccess">
    <success-alert>
      Vielen Dank für Ihre Anmeldung!
    </success-alert>
  </template>
  <template v-if="formError">
    <error-alert>
      Bitte überprüfen Sie die eingegebenen Daten.
    </error-alert>
  </template>
  <form @submit.prevent="submitForm" class="space-y-10 lg:space-y-25">
    <form-group>
      <form-text-field 
        v-model="form.firstname" 
        :error="errors.firstname"
        @update:error="errors.firstname = $event"
        placeholder="Vorname *"
      />
    </form-group>
    <form-group>
      <form-text-field 
        v-model="form.name" 
        :error="errors.name"
        @update:error="errors.name = $event"
        placeholder="Name *"
      />
    </form-group>
    <form-group>
      <form-text-field 
        type="email"
        v-model="form.email" 
        :error="errors.email"
        @update:error="errors.email = $event"
        placeholder="E-Mail *"
      />
    </form-group>

    <form-group>
      <form-text-field 
        type="text"
        v-model="form.number_of_people" 
        :error="errors.number_of_people"
        @update:error="errors.number_of_people = $event"
        placeholder="Anzahl Personen *"
      />
    </form-group>

    <form-group class="!mt-35">
      <form-button 
        type="submit" 
        :label="'Anmelden'"
        :disabled="isSubmitting"
        :submitting="isSubmitting"
      />
    </form-group>
  </form>
</template>
<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import FormGroup from '@/forms/components/fields/group.vue';
import FormTextField from '@/forms/components/fields/text.vue';
import FormTextareaField from '@/forms/components/fields/textarea.vue';
import FormLabel from '@/forms/components/fields/label.vue';
import FormButton from '@/forms/components/fields/button.vue';
import FormSelectField from '@/forms/components/fields/select.vue';
import FormRadioField from '@/forms/components/fields/radio.vue';
import FormToc from '@/forms/components/fields/toc.vue';
import Error from '@/forms/components/fields/error.vue';
import SuccessAlert from '@/forms/components/alerts/success.vue';
import ErrorAlert from '@/forms/components/alerts/error.vue';

const props = defineProps({
  eventId: {
    type: String,
    required: true,
  },
});

const isSubmitting = ref(false);
const formSuccess = ref(false);
const formError = ref(false);

const form = ref({
  event_id: props.eventId,
  name: null  ,
  firstname: null,
  email: null,
  number_of_people: null,
});

const errors = ref({
  name: '',
  firstname: '',
  email: '',
  number_of_people: '',
});

// Watch number_of_people for non-numeric values
watch(() => form.value.number_of_people, (newValue) => {
  if (newValue === null || newValue === '') return;
  if (isNaN(newValue)) {
    form.value.number_of_people = '0';
  }
});

async function submitForm() {
  isSubmitting.value = true;
  formSuccess.value = false;
  formError.value = false;
  try {
    const response = await axios.post('/api/event/register', {
      ...form.value
    });
    handleSuccess();
  } catch (error) {
    handleError(error);
  }
}

function handleSuccess() {
  form.value = {
    event_id: props.eventId,
    name: null,
    firstname: null,
    email: null,
    number_of_people: null,
  };
  
  errors.value = {
    name: '',
    firstname: '',
    email: '',
    number_of_people: '',
  };
  
  isSubmitting.value = false;
  formSuccess.value = true;
}

function handleError(error) {
  isSubmitting.value = false;
  formError.value = true;

  if (error.response && error.response.data && typeof error.response.data.errors === 'object') {
    Object.keys(error.response.data.errors).forEach(key => {
      errors.value[key] = error.response.data.errors[key];
    });
  }
}
</script>