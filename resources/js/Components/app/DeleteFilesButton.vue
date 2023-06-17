<template>
    <button @click="onDeleteClick"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="w-4 h-4 mr-2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
        </svg>
        Delete
    </button>

    <ConfirmationDialog :show="showDeleteDialog"
                        message="Are you sure you want to delete selected files?"
                        @cancel="onDeleteCancel"
                        @confirm="onDeleteConfirm">

    </ConfirmationDialog>
</template>

<script setup>
// Imports
import {ref} from "vue";
import ConfirmationDialog from "@/Components/ConfirmationDialog.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {showErrorDialog, showSuccessNotification} from "@/event-bus.js";

// Uses
const page = usePage();
const deleteFilesForm = useForm({
    all: null,
    ids: [],
    parent_id: null
})


// Refs

const showDeleteDialog = ref(false)

// Props & Emit

const props = defineProps({
    deleteAll: {
        type: Boolean,
        required: false,
        default: false
    },
    deleteIds: {
        type: Array,
        required: false
    }
})
const emit = defineEmits(['delete'])

// Computed

// Methods

function onDeleteClick() {
    if (!props.deleteAll && !props.deleteIds.length) {
        showErrorDialog('Please select at least one file to delete')
        return
    }
    showDeleteDialog.value = true;
}

function onDeleteCancel(){
    showDeleteDialog.value = false;
}

function onDeleteConfirm(){
    deleteFilesForm.parent_id = page.props.folder.id
    if (props.deleteAll) {
        deleteFilesForm.all = true
    } else {
        deleteFilesForm.ids = props.deleteIds
    }

    deleteFilesForm.delete(route('file.delete'), {
        onSuccess: () => {
            showDeleteDialog.value = false
            emit('delete')
            showSuccessNotification('Selected files have been deleted')
        }
    })

    console.log("Delete", props.deleteAll, props.deleteIds);
}

// Hooks

</script>

<style scoped>

</style>
