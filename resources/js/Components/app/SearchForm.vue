<template>
    <form class="w-[600px] h-[80px] flex items-center">
        <TextInput
            type="text"
            class="mt-1 block w-full"
            v-model="form.search"
            autocomplete="search"
            placeholder="Search for files and folders"
        />
    </form>
</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";

const form = useForm({
    search: ''
})

</script>

<style scoped>

</style>
