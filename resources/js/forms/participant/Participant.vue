<template>
  <template v-if="formSuccess">
    <success-alert>
      Vielen Dank für Ihre Anfrage!
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
        v-model="form.salutation" 
        placeholder="Anrede"
      />
    </form-group>
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
        v-model="form.street" 
        :error="errors.street"
        @update:error="errors.street = $event"
        placeholder="Strasse/Nr. *"
      />
    </form-group>
    <form-group>
      <form-text-field 
        v-model="form.zip" 
        :error="errors.zip"
        @update:error="errors.zip = $event"
        placeholder="PLZ *"
      />
    </form-group>
    <form-group>
      <form-text-field 
        v-model="form.location" 
        :error="errors.location"
        @update:error="errors.location = $event"
        placeholder="Ort *"
      />
    </form-group>
    <form-group>
      <form-text-field 
        v-model="form.phone" 
        :error="errors.phone"
        @update:error="errors.phone = $event"
        placeholder="Telefon *"
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
      <form-textarea-field
        v-model="form.message"
        :error="errors.message"
        placeholder="Bemerkungen">
      </form-textarea-field>
    </form-group>
    <form-group class="!mt-35">
      <form-button 
        type="submit" 
        :label="'Abschicken'"
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
import Error from '@/forms/components/fields/error.vue';
import SuccessAlert from '@/forms/components/alerts/success.vue';
import ErrorAlert from '@/forms/components/alerts/error.vue';

const isSubmitting = ref(false);
const formSuccess = ref(false);
const formError = ref(false);

const form = ref({
  salutation: null,
  name: null,
  firstname: null,
  street: null,
  location: null,
  zip: null,
  phone: null,
  email: null,
  message: null,
});

const errors = ref({
  salutation: '',
  name: '',
  firstname: '',
  street: '',
  location: '',
  zip: '',
  phone: '',
  email: '',
  message: '',
});

async function submitForm() {
  isSubmitting.value = true;
  formSuccess.value = false;
  formError.value = false;
  try {
    const response = await axios.post('/api/participant/submission', {
      ...form.value
    });
    handleSuccess();
  } catch (error) {
    handleError(error);
  }
}

function handleSuccess() {
  form.value = {
    salutation: null,
    name: null,
    firstname: null,
    street: null,
    location: null,
    zip: null,
    email: null,
    message: null,
    phone: null
  };
  
  errors.value = {
    salutation: '',
    name: '',
    firstname: '',
    street: '',
    location: '',
    zip: '',
    email: '',
    message: '',
    phone: '',
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