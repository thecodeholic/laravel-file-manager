<template>
    <Modal :show="modelValue" @show="onShow" @close="closeModal" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Create New Folder
            </h2>

            <div class="mt-6">
                <InputLabel for="folderName" value="folderName" class="sr-only"/>

                <TextInput
                    type="text"
                    id="folderName"
                    ref="folderNameInput"
                    v-model="form.name"
                    class="mt-1 block w-full"
                    :class="form.errors.name ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''"
                    placeholder="Folder Name"
                    @keyup.enter="createFolder"
                />

                <InputError :message="form.errors.name" class="mt-2"/>
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="createFolder"
                >
                    Submit
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import {useForm} from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3'
import {nextTick, ref} from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";

// Uses
const page = usePage()
const form = useForm({
    name: '',
    parent_id: null
});

// Refs
const folderNameInput = ref(null);

// Props and emit
const {modelValue} = defineProps({
    modelValue: Boolean
})
const emit = defineEmits(['update:modelValue'])

// Methods
function onShow() {
    nextTick(() => folderNameInput.value.focus())
}

const createFolder = () => {
    form.parent_id = page.props.folder?.id;
    form.post(route('folder.create'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal()
            form.reset()
        },
        onError: () => folderNameInput.value.focus(),
    });
};

const closeModal = () => {
    emit('update:modelValue', false)

    form.clearErrors()
    form.reset();
};

// Hooks

</script>

<style scoped>

</style>
