<script setup>
import Message from 'primevue/message';

defineProps({
    message: { type: String, default: '' },
    severity: { type: String, default: 'success' },
});
</script>

<template>
    <Message v-if="message" :severity="severity" :closable="false" class="mb-5">
        {{ message }}
    </Message>
</template>
