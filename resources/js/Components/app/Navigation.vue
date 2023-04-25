<template>
    <nav class="min-w-[200px]">
        <div class="h-[80px] px-3 flex items-center gap-3">
            <Link :href="route('myFiles')">
                <ApplicationLogo
                    class="block h-9 w-auto fill-current text-gray-800"
                />
            </Link>
            LaraStorage
        </div>
        <div class="px-3">
            <CreateNewDropdown></CreateNewDropdown>

            <div class="py-3">
                <NavLink :href="route('myFiles')" :active="$page.url === '/my-files'">
                    My Files
                </NavLink>
                <NavLink :href="route('sharedWithMe')" :active="$page.url === '/shared-with-me'">
                    Shared with Me
                </NavLink>
                <NavLink :href="route('sharedByMe')" :active="$page.url === '/shared-by-me'">
                    Shared by Me
                </NavLink>
                <NavLink :href="route('trash')"  :active="$page.url === '/trash'">
                    Trash
                </NavLink>
            </div>

        </div>
    </nav>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import CreateNewDropdown from "@/Components/app/CreateNewDropdown.vue";
import NavLink from "@/Components/NavLink.vue";
</script>

<style scoped>

</style>
