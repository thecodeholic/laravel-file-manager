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
import {useForm, usePage} from "@inertiajs/vue3";
import {showErrorDialog} from "@/event-bus.js";

// Uses
const page = usePage()

const fileUploadForm = useForm({
    files: [],
    parent_id: null
})

// Refs

// Props & Emit

// Methods
function onFileUploadChange(ev) {
    fileUploadForm.parent_id = page.props.folder?.id;
    fileUploadForm.files = ev.target.files
    fileUploadForm.post(route('file.upload'), {
        onError: errors => {
            let message = '';
            if (Object.keys(errors).length > 0) {
                message = errors[Object.keys(errors)[0]]
            } else {
                message = 'Error during file upload. Please try again later'
            }
            showErrorDialog(message)
        },
    })
}

// Hooks

</script>

<style scoped>

</style>
