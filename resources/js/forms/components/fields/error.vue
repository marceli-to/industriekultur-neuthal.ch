<template>
  <div v-if="error" :class="classes" class="text-crimson absolute top-3 right-0">{{ error }}</div>
</template>
<script setup>

const props = defineProps({
  error: {
    type: String,
    default: ''
  },
  classes: {
    type: String,
    default: ''
  }
});
</script>