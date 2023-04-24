<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'flex items-center px-5 rounded-lg py-2 text-sm font-medium leading-5 text-indigo-700 bg-indigo-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'flex items-center px-5 rounded-lg py-2 text-sm font-medium leading-5 text-gray-700 hover:bg-gray-200 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
