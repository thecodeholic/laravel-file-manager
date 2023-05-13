<template>
    <div class="h-screen bg-gray-50 flex w-full gap-4">
        <Navigation/>
        <!-- Page Content -->
        <main @drop.prevent="handleDrop"
              @dragover.prevent="onDragOver"
              @dragleave.prevent="onDragLeave"
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
                <div class="flex-1 flex flex-col overflow-hidden">
                    <slot/>
                </div>
            </template>
        </main>
    </div>

    <ErrorDialog/>
    <FormProgress :form="fileUploadForm"/>
    <Notification/>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import {useForm, usePage} from '@inertiajs/vue3';
import SearchForm from "@/Components/app/SearchForm.vue";
import Navigation from "@/Components/app/Navigation.vue";
import UserSettingsDropdown from "@/Components/app/UserSettingsDropdown.vue";
import ErrorDialog from "@/Components/ErrorDialog.vue";
import {
    emitter,
    showErrorDialog,
    FILE_UPLOAD_STARTED,
    showSuccessNotification
} from "@/event-bus.js";
import FormProgress from "@/Components/FormProgress.vue";
import Notification from "@/Components/Notification.vue";

const page = usePage();
const dragOver = ref(false);

const fileUploadForm = useForm({
    files: [],
    folder_name: '',
    parent_id: null
})

function onDragOver() {
    dragOver.value = true
}

function onDragLeave() {
    dragOver.value = false
}

function handleDrop(event) {
    dragOver.value = false;
    const files = event.dataTransfer.files;
    if (!files.length) {
        return;
    }

    uploadFiles({files})
}

function uploadFiles({files, folder_name = null}) {
    fileUploadForm.parent_id = page.props.folder?.id;
    if (folder_name) {
        fileUploadForm.folder_name = folder_name;
    }
    fileUploadForm.files = files

    fileUploadForm.post(route('file.upload'), {
        onSuccess: () => {
            showSuccessNotification('Successfully uploaded')
        },
        onError: errors => {
            let message = '';
            if (Object.keys(errors).length > 0) {
                message = errors[Object.keys(errors)[0]]
            } else {
                message = 'Error during file upload. Please try again later'
            }
            showErrorDialog(message)
        },
        onFinish: () => {
            fileUploadForm.clearErrors()
            fileUploadForm.reset()
        }
    })
}

// Hooks
onMounted(() => {
    emitter.on(FILE_UPLOAD_STARTED, uploadFiles)
})
</script>

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
