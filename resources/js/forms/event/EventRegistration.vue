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
  <template v-if="!hasOpenSeats">
    <p class="text-crimson">
      <strong>Zur Zeit ist das Limit für diese Veranstaltung erreicht. Sie können sich aber dennoch auf die Warteliste setzen lassen.</strong></p>
  </template>
  <form 
    @submit.prevent="submitForm" 
    class="space-y-10 lg:space-y-25"
    v-if="isLoaded">
    <form-group v-if="hasSalutation">
      <form-text-field 
        v-model="form.salutation" 
        :error="errors.salutation"
        @update:error="errors.salutation = $event"
        :placeholder="requiresSalutation ? 'Anrede *' : 'Anrede'"
      />
    </form-group>
    <form-group v-if="hasFirstname">
      <form-text-field 
        v-model="form.firstname" 
        :error="errors.firstname"
        @update:error="errors.firstname = $event"
        :placeholder="requiresFirstname ? 'Vorname *' : 'Vorname'"
      />
    </form-group>
    <form-group v-if="hasName">
      <form-text-field 
        v-model="form.name" 
        :error="errors.name"
        @update:error="errors.name = $event"
        :placeholder="requiresName ? 'Name *' : 'Name'"
      />
    </form-group>
    <form-group v-if="hasEmail">
      <form-text-field 
        type="email"
        v-model="form.email" 
        :error="errors.email"
        @update:error="errors.email = $event"
        :placeholder="requiresEmail ? 'E-Mail *' : 'E-Mail'"
      />
    </form-group>
    <form-group v-if="hasPhone">
      <form-text-field 
        type="text"
        v-model="form.phone" 
        :error="errors.phone"
        @update:error="errors.phone = $event"
        :placeholder="requiresPhone ? 'Telefon *' : 'Telefon'"
      />
    </form-group>
    <form-group v-if="hasStreet">
      <form-text-field 
        type="text"
        v-model="form.street" 
        :error="errors.street"
        @update:error="errors.street = $event"
        :placeholder="requiresStreet ? 'Strasse/Nr. *' : 'Strasse/Nr.'"
      />
    </form-group>
    <form-group v-if="hasZip">
      <form-text-field 
        type="text"
        v-model="form.zip" 
        :error="errors.zip"
        @update:error="errors.zip = $event"
        :placeholder="requiresZip ? 'PLZ *' : 'PLZ'"
      />
    </form-group>
    <form-group v-if="hasLocation">
      <form-text-field
        type="text"
        v-model="form.location"
        :error="errors.location"
        @update:error="errors.location = $event"
        :placeholder="requiresLocation ? 'Ort *' : 'Ort'"
      />
    </form-group>
    <form-group v-if="hasNumberAdults">
      <form-text-field
        v-model="form.number_adults"
        :error="errors.number_adults"
        @update:error="errors.number_adults = $event"
        :placeholder="requiresNumberAdults ? 'Anzahl Erwachsene *' : 'Anzahl Erwachsene'"
      />
    </form-group>
    <form-group v-if="hasNumberTeenagers">
      <form-text-field 
        type="text"
        v-model="form.number_teenagers" 
        :error="errors.number_teenagers"
        @update:error="errors.number_teenagers = $event"
        :placeholder="requiresNumberTeenagers ? 'Anzahl Jugendliche *' : 'Anzahl Jugendliche'"
      />
    </form-group>
    <form-group v-if="hasNumberKids">
      <form-text-field
        v-model="form.number_kids"
        :error="errors.number_kids"
        @update:error="errors.number_kids = $event"
        :placeholder="requiresNumberKids ? 'Anzahl Kinder *' : 'Anzahl Kinder'"
      />
    </form-group>

    <form-group v-if="hasRemarks">
      <form-textarea-field
        v-model="form.remarks"
        :error="errors.remarks"
        placeholder="Bemerkungen">
      </form-textarea-field>
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
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import FormGroup from '@/forms/components/fields/group.vue';
import FormTextField from '@/forms/components/fields/text.vue';
import FormTextareaField from '@/forms/components/fields/textarea.vue';
import FormButton from '@/forms/components/fields/button.vue';
import SuccessAlert from '@/forms/components/alerts/success.vue';
import ErrorAlert from '@/forms/components/alerts/error.vue';

const props = defineProps({
  eventId: {
    type: String,
    required: true,
  },
});

const isSubmitting = ref(false);
const isLoaded = ref(false);
const formSuccess = ref(false);
const formError = ref(false);

const hasOpenSeats = ref(false);
const hasSalutation = ref(false);
const requiresSalutation = ref(false);
const hasName = ref(false);
const requiresName = ref(false);
const hasFirstname = ref(false);
const requiresFirstname = ref(false);
const hasEmail = ref(false);
const requiresEmail = ref(false);
const hasPhone = ref(false);
const requiresPhone = ref(false);
const hasStreet = ref(false);
const requiresStreet = ref(false);
const hasZip = ref(false);
const requiresZip = ref(false);
const hasLocation = ref(false);
const requiresLocation = ref(false);
const hasRemarks = ref(false);
const requiresRemarks = ref(false);
const hasNumberAdults = ref(false);
const requiresNumberAdults = ref(false);
const hasNumberTeenagers = ref(false);
const requiresNumberTeenagers = ref(false);
const hasNumberKids = ref(false);
const requiresNumberKids = ref(false);

const form = ref({
  event_id: props.eventId,
  salutation: null,
  name: null,
  firstname: null,
  email: null,
  phone: null,
  street: null,
  zip: null,
  location: null,
  remarks: null,
  number_people: null,
  number_adults: null,
  number_teenagers: null,
  number_kids: null,
});

const errors = ref({
  name: '',
  firstname: '',
  email: '',
  number_of_people: '',
});

// Watch for non-numeric values
watch(() => form.value.number_adults, (newValue) => {
  if (newValue === null || newValue === '') return;
  if (isNaN(newValue)) {
    form.value.number_adults = '0';
  }
});

watch(() => form.value.number_teenagers, (newValue) => {
  if (newValue === null || newValue === '') return;
  if (isNaN(newValue)) {
    form.value.number_teenagers = '0';
  }
});

watch(() => form.value.number_kids, (newValue) => {
  if (newValue === null || newValue === '') return;
  if (isNaN(newValue)) {
    form.value.number_kids = '0';
  }
});

onMounted(async () => {
  try {
    const response = await axios.get(`/api/event/${props.eventId}`);
    isLoaded.value = true;
    hasOpenSeats.value = response.data.has_open_seats;
    hasSalutation.value = response.data.has_salutation;
    requiresSalutation.value = response.data.requires_salutation;
    hasName.value = response.data.has_name;
    requiresName.value = response.data.requires_name;
    hasFirstname.value = response.data.has_firstname;
    requiresFirstname.value = response.data.requires_firstname;
    hasEmail.value = response.data.has_email;
    requiresEmail.value = response.data.requires_email;
    hasPhone.value = response.data.has_phone;
    requiresPhone.value = response.data.requires_phone;
    hasStreet.value = response.data.has_street;
    requiresStreet.value = response.data.requires_street;
    hasZip.value = response.data.has_zip;
    requiresZip.value = response.data.requires_zip;
    hasLocation.value = response.data.has_location;
    requiresLocation.value = response.data.requires_location;
    hasRemarks.value = response.data.has_remarks;
    requiresRemarks.value = response.data.requires_remarks;
    hasNumberAdults.value = response.data.has_number_adults;
    requiresNumberAdults.value = response.data.requires_number_adults;
    hasNumberTeenagers.value = response.data.has_number_teenagers;
    requiresNumberTeenagers.value = response.data.requires_number_teenagers;
    hasNumberKids.value = response.data.has_number_kids;
    requiresNumberKids.value = response.data.requires_number_kids;
  } 
  catch (error) {
    console.error(error);
  }
});

async function submitForm() {
  isSubmitting.value = true;
  formSuccess.value = false;
  formError.value = false;

  form.value.number_people = Number(form.value.number_adults || 0) + Number(form.value.number_teenagers || 0) + Number(form.value.number_kids || 0);
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
    salutation: null,
    name: null,
    firstname: null,
    email: null,
    phone: null,
    street: null,
    zip: null,
    location: null,
    remarks: null,
    number_adults: null,
    number_teenagers: null,
    number_kids: null,
  };
  
  errors.value = {
    salutation: '',
    name: '',
    firstname: '',
    email: '',
    phone: '',
    street: '',
    zip: '',
    location: '',
    remarks: '',
    number_adults: '',
    number_teenagers: '',
    number_kids: '',
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