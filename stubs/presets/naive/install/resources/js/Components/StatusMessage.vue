<script setup>
import { NAlert } from 'naive-ui';

defineProps({
    message: { type: String, default: '' },
    severity: { type: String, default: 'success' },
});
</script>

<template>
    <NAlert v-if="message" :type="severity" :show-icon="false" class="mb-5">
        {{ message }}
    </NAlert>
</template>
