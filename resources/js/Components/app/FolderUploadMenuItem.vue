<template>
    <MenuItem v-slot="{ active }">
        <a href="#"
           class="relative"
           :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">
            Folder Upload
            <input type="file" @change="onFileUploadChange" webkitdirectory directory
                   class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer">
        </a>
    </MenuItem>
</template>

<script setup>
import {MenuItem} from "@headlessui/vue";
import {useForm, usePage} from "@inertiajs/vue3";

const page = usePage()

const folderUploadForm = useForm({
    files: [],
    folder_name: null,
    parent_id: null
})

function onFileUploadChange(ev) {
    folderUploadForm.parent_id = page.props.folder?.id;
    folderUploadForm.files = ev.target.files
    console.log(folderUploadForm.files);
    folderUploadForm.folder_name = ev.target.files[0].webkitRelativePath.split('/')[0];
    folderUploadForm.post(route('file.upload'))
}
</script>

<style scoped>

</style>
