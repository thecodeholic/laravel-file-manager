<template>
    <MenuItem v-slot="{ active }">
        <a href="#"
           class="relative"
           :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
            File Upload
            <input type="file" @change="onFileUploadChange" multiple
                   class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer">
        </a>
    </MenuItem>
</template>

<script setup>
// Imports
import {MenuItem} from "@headlessui/vue";
import {usePage} from "@inertiajs/vue3";
import {emitter, FILE_UPLOAD_STARTED} from "@/event-bus.js";

// Uses
const page = usePage()

// Refs

// Props & Emit

// Methods
function onFileUploadChange(ev) {
    emitter.emit(FILE_UPLOAD_STARTED, {files: ev.target.files})
}

// Hooks

</script>

<style scoped>

</style>
