<template>
  <template v-if="!hasOpenSeats">
    <waiting-list-alert>
      Zur Zeit ist das Limit für diese Veranstaltung erreicht. Sie können sich aber dennoch auf die Warteliste setzen lassen.
    </waiting-list-alert>
  </template>
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
  <form 
    @submit.prevent="submitForm" 
    class="space-y-10 lg:space-y-25"
    v-if="isLoaded">
    <form-group v-if="hasSalutation">
      <form-text-field 
        v-model="form.salutation" 
        :error="errors.salutation"
        @update:error="errors.salutation = $event"
        :placeholder="errors.salutation ? errors.salutation : 'Anrede' + (requiresSalutation ? ' *' : '')"
        label="Anrede"
        aria-label="Anrede"
      />
    </form-group>
    <form-group v-if="hasFirstname">
      <form-text-field 
        v-model="form.firstname" 
        :error="errors.firstname"
        @update:error="errors.firstname = $event"
        :placeholder="errors.firstname ? errors.firstname : 'Vorname' + (requiresFirstname ? ' *' : '')"
        label="Vorname"
        aria-label="Vorname"
      />
    </form-group>
    <form-group v-if="hasName">
      <form-text-field 
        v-model="form.name" 
        :error="errors.name"
        @update:error="errors.name = $event"
        :placeholder="errors.name ? errors.name : (requiresName ? 'Name *' : 'Name')"
        label="Name"
        aria-label="Name"
      />
    </form-group>
    <form-group v-if="hasEmail">
      <form-text-field 
        type="email"
        v-model="form.email" 
        :error="errors.email"
        @update:error="errors.email = $event"
        :placeholder="errors.email ? errors.email : (requiresEmail ? 'E-Mail *' : 'E-Mail')"
        label="E-Mail"
        aria-label="E-Mail"
      />
    </form-group>
    <form-group v-if="hasPhone">
      <form-text-field 
        type="text"
        v-model="form.phone" 
        :error="errors.phone"
        @update:error="errors.phone = $event"
        :placeholder="errors.phone ? errors.phone : (requiresPhone ? 'Telefon *' : 'Telefon')"
        label="Telefon"
        aria-label="Telefon"
      />
    </form-group>
    <form-group v-if="hasStreet">
      <form-text-field 
        type="text"
        v-model="form.street" 
        :error="errors.street"
        @update:error="errors.street = $event"
        :placeholder="errors.street ? errors.street : (requiresStreet ? 'Strasse/Nr. *' : 'Strasse/Nr.')"
        label="Strasse/Nr."
        aria-label="Strasse/Nr."
      />
    </form-group>
    <form-group v-if="hasZip">
      <form-text-field 
        type="text"
        v-model="form.zip" 
        :error="errors.zip"
        @update:error="errors.zip = $event"
        :placeholder="errors.zip ? errors.zip : (requiresZip ? 'PLZ *' : 'PLZ')"
        label="PLZ"
        aria-label="PLZ"
      />
    </form-group>
    <form-group v-if="hasLocation">
      <form-text-field
        type="text"
        v-model="form.location"
        :error="errors.location"
        @update:error="errors.location = $event"
        :placeholder="errors.location ? errors.location : (requiresLocation ? 'Ort *' : 'Ort')"
        label="Ort"
        aria-label="Ort"
      />
    </form-group>
    <form-group v-if="hasNumberAdults">
      <form-text-field
        v-model="form.number_adults"
        :error="errors.number_adults"
        @update:error="errors.number_adults = $event"
        :placeholder="errors.number_adults ? errors.number_adults : 'Anzahl Erwachsene *'"
        label="Anzahl Erwachsene"
        aria-label="Anzahl Erwachsene"
      />
    </form-group>
    <form-group v-if="hasNumberTeenagers">
      <form-text-field 
        type="text"
        v-model="form.number_teenagers" 
        :error="errors.number_teenagers"
        @update:error="errors.number_teenagers = $event"
        :placeholder="errors.number_teenagers ? errors.number_teenagers : 'Anzahl Jugendliche *'"
        label="Anzahl Jugendliche"
        aria-label="Anzahl Jugendliche"
      />
    </form-group>
    <form-group v-if="hasNumberKids">
      <form-text-field
        v-model="form.number_kids"
        :error="errors.number_kids"
        @update:error="errors.number_kids = $event"
        :placeholder="errors.number_kids ? errors.number_kids : 'Anzahl Kinder *'"
        label="Anzahl Kinder"
        aria-label="Anzahl Kinder"
      />
    </form-group>

    <form-group v-if="hasRemarks">
      <form-textarea-field
        v-model="form.remarks"
        :error="errors.remarks"
        @update:error="errors.remarks = $event"
        :placeholder="errors.remarks ? errors.remarks : (requiresRemarks ? 'Bemerkungen *' : 'Bemerkungen')"
        label="Bemerkungen"
        aria-label="Bemerkungen"
      />
    </form-group>
    <form-group class="gap-y-10 flex flex-col">
      <form-checkbox
        v-model="form.newsletter"
        id="newsletter-contact"
        name="newsletter"
        label="Ja, ich möchte mich für den Newsletter anmelden."
      />
      <form-checkbox
        v-model="form.privacy"
        :error="errors.privacy"
        @update:error="errors.privacy = $event"
        id="privacy-contact"
        name="privacy"
        label="Ich habe die <a href='/datenschutz' class='decoration-1'>Datenschutzerklärung</a> gelesen und stimme dieser zu.*"
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
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import FormGroup from '@/forms/components/fields/group.vue';
import FormTextField from '@/forms/components/fields/text.vue';
import FormTextareaField from '@/forms/components/fields/textarea.vue';
import FormButton from '@/forms/components/fields/button.vue';
import FormCheckbox from '@/forms/components/fields/checkbox.vue';
import SuccessAlert from '@/forms/components/alerts/success.vue';
import ErrorAlert from '@/forms/components/alerts/error.vue';
import WaitingListAlert from '@/forms/components/alerts/waitinglist.vue';
import { useFormScroll } from '@/composables/useFormScroll';

const { scrollToForm } = useFormScroll();
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
const hasNumberTeenagers = ref(false);
const hasNumberKids = ref(false);

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
  newsletter: false,
  privacy: false
});

const errors = ref({
  salutation: '',
  name: '',
  firstname: '',
  email: '',
  phone: '',
  street: '',
  zip: '',
  location: '',
  remarks: '',
  number_people: '',
  number_adults: '',
  number_teenagers: '',
  number_kids: '',
  newsletter: '',
  privacy: '',
});

// Watch for non-numeric values
watch(() => form.value.number_adults, (newValue) => {
  if (newValue === null || newValue === '') return;
  if (isNaN(newValue) || newValue == 0) {
    form.value.number_adults = '1';
  }
  errors.value.number_teenagers = '';
  errors.value.number_kids = '';
});

watch(() => form.value.number_teenagers, (newValue) => {
  if (newValue === null || newValue === '') return;
  if (isNaN(newValue) || newValue == 0) {
    form.value.number_teenagers = '1';
  }
  errors.value.number_adults = '';
  errors.value.number_kids = '';
});

watch(() => form.value.number_kids, (newValue) => {
  if (newValue === null || newValue === '') return;
  if (isNaN(newValue) || newValue == 0) {
    form.value.number_kids = '1';
  }
  errors.value.number_adults = '';
  errors.value.number_teenagers = '';
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
    hasNumberTeenagers.value = response.data.has_number_teenagers;
    hasNumberKids.value = response.data.has_number_kids;
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
    newsletter: false,
    privacy: false
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
    newsletter: '',
    privacy: '',
  };
  
  isSubmitting.value = false;
  formSuccess.value = true;
  scrollToForm();
}

function handleError(error) {
  isSubmitting.value = false;
  formError.value = true;
  if (error.response && error.response.data && typeof error.response.data.errors === 'object') {
    Object.keys(error.response.data.errors).forEach(key => {
      errors.value[key] = error.response.data.errors[key];
    });
  }
  scrollToForm();
}
</script>