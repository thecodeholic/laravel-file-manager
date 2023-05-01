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
import {MenuItem} from "@headlessui/vue";
import {useForm, usePage} from "@inertiajs/vue3";

const page = usePage()

const fileUploadForm = useForm({
    files: [],
    parent_id: null
})

function onFileUploadChange(ev) {
    fileUploadForm.parent_id = page.props.folder?.id;
    fileUploadForm.files = ev.target.files
    fileUploadForm.post(route('file.upload'))
}
</script>

<style scoped>

</style>
