<script setup>
import {ref} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Link, useForm, usePage} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import SearchForm from "@/Components/app/SearchForm.vue";
import Navigation from "@/Components/app/Navigation.vue";
import UserSettingsDropdown from "@/Components/app/UserSettingsDropdown.vue";

const page = usePage();
const dragOver = ref(false);

const fileUploadForm = useForm({
    files: [],
    parent_id: null
})

function onDragOver() {
    dragOver.value = true
    console.log("Over");
}

function onDragLeave() {
    dragOver.value = false
}

function onDragStart() {
    dragOver.value = true
    console.log("onDragStart");
}

function onDragEnter() {
    dragOver.value = true
    console.log("onDragEnter");
}

function onDragEnd() {
    dragOver.value = false
    console.log("onDragEnd");
}

function handleDrop(event) {
    dragOver.value = false;
    const files = event.dataTransfer.files;

    fileUploadForm.parent_id = page.props.folder?.id;
    fileUploadForm.files = files
    fileUploadForm.post(route('file.upload'))
}

</script>

<template>
    <div class="h-screen bg-gray-50 flex w-full gap-4">
        <Navigation/>
        <!-- Page Content -->
        <main @drop.prevent="handleDrop"
              @dragleave.prevent="onDragLeave"
              @dragend.prevent="onDragEnd"
              @dragstart.prevent="onDragStart"
              @dragenter.prevent="onDragEnter"
              class="flex flex-col flex-1 px-4 overflow-hidden"
              :class="dragOver ? 'dropzone' : ''">
            <template v-if="dragOver" class="text-gray-500 text-center py-8 text-sm">
                Drop files here to upload
            </template>
            <template v-else>
                <div class="flex items-center justify-between w-full">
                    <SearchForm/>
                    <UserSettingsDropdown/>
                </div>
                <div class="flex-1 overflow-auto">
                    <slot/>
                </div>
            </template>
        </main>
    </div>
</template>

<style lang="css">
.dropzone {
    width: 100%;
    height: 100%;
    color: #8d8d8d;
    border: 2px dashed gray;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
