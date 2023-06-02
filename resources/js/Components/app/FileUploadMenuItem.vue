<template>
    <MenuItem  v-slot="{ active }">
        <a href="#" class="text-gray-700 block px-4 py-2 text-sm relative">
            Upload Files
            <input @change="onChange" type="file" class="absolute left-0 top-0 bottom-0 right-0 cursor-pointer opacity-0" multiple>
        </a>
    </MenuItem>
</template>

<script setup>
// Imports
import {MenuItem} from "@headlessui/vue";
import {emitter, FILE_UPLOAD_STARTED} from "@/event-bus.js";

// Uses

// Refs

// Props & Emit

// Computed

// Methods
function onChange(ev){
    emitter.emit(FILE_UPLOAD_STARTED, ev.target.files)
}

// Hooks

</script>

<style scoped>

</style>
