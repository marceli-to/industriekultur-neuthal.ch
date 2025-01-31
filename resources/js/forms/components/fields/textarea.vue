<template>
  <div class="relative">
    <textarea
      :value="modelValue"
      :placeholder="placeholder"
      @input="$emit('update:modelValue', $event.target.value)"
      @focus="$emit('update:error', '')"
      :class="[
        { '!border-crimson placeholder:!text-crimson': error },
        'placeholder:text-black/50'
      ]">
    </textarea>
    <!-- <Error :error="error" /> -->
  </div>
</template>

<script setup>
import Error from './error.vue';
const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: ''
  }
});

defineEmits(['update:modelValue', 'update:error']);
</script>
