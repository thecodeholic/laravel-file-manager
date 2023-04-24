<script setup>
import {ref} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Link} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import SearchForm from "@/Components/app/SearchForm.vue";
import Navigation from "@/Components/app/Navigation.vue";
import UserSettingsDropdown from "@/Components/app/UserSettingsDropdown.vue";

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 flex w-full gap-4">
            <Navigation/>
            <!-- Page Content -->
            <main class="flex-1">
                <div class="pr-4 flex items-center justify-between w-full" >
                    <SearchForm/>
                    <UserSettingsDropdown />
                </div>
                <slot/>
            </main>
        </div>
    </div>
</template>
