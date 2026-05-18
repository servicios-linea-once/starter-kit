<script setup>
defineProps({
    message: { type: String, default: '' },
});
</script>

<template>
    <small v-if="message" class="mt-1 block text-sm text-rose-300">{{ message }}</small>
</template>
