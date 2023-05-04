<template>
    <AuthenticatedLayout>
        <!-- component -->

        <nav class="flex mb-3" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li v-for="ans of ancestors.data" class="inline-flex items-center">
                    <Link v-if="!ans.parent_id" :href="route('myFiles')"
                          class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        My Files
                    </Link>
                    <div v-else class="flex items-center">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <Link :href="route('myFiles', {folder: ans.path})"
                              class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                            {{ ans.name }}
                        </Link>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex-1 overflow-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100 border-b">
                <tr>
                    <th scope="col"
                        class="text-sm font-medium text-gray-900 px-6 py-4 text-left w-[30px] max-w-[30px] pr-0">
                        <Checkbox @change="onDeleteAllChange" v-model:checked="deleteAll"/>
                    </th>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        Name
                    </th>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        Owner
                    </th>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        Last Modified
                    </th>
                    <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                        Size
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="file of allFiles.data" :key="file.id"
                    @click="toggleFileSelect(file)"
                    class="bg-white border-b transition duration-300 ease-in-out hover:bg-blue-100"
                    :class="(toBeDeleted[file.id] || deleteAll) ? 'bg-blue-50' : ''">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 pr-0">
                        <Checkbox @change="onDeleteChange(file)" v-model="toBeDeleted[file.id]"
                                  :checked="toBeDeleted[file.id] || deleteAll"/>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <a href="#" @dblclick.prevent="openFolder(file)" class="flex items-center">
                            <FileIcon :file="file"/>
                            {{ file.name }}
                        </a>
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{ file.owner }}</td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{
                            file.created_at
                        }}
                    </td>
                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">{{ file.size }}</td>
                </tr>
                </tbody>
            </table>
            <div v-if="!allFiles.data.length" class="py-8 text-center text-lg text-gray-400">
                There is no data in this folder
            </div>
            <div ref="loadMoreIntersect"></div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {Link, router, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {onMounted, onUpdated, ref, watch} from "vue";
import FileIcon from "@/Components/app/FileIcon.vue";
import Checkbox from "@/Components/Checkbox.vue";

const deleteAll = ref(false)
const toBeDeleted = ref({})

const page = usePage();

const props = defineProps({
    files: Object,
    folder: Object,
    ancestors: Object
})

const loadMoreIntersect = ref(null)
const allFiles = ref({
    data: props.files.data,
    next: props.files.links.next
})

function openFolder(file) {
    if (!file.is_folder) {
        return
    }

    router.visit(route('myFiles', {folder: file.path}))
}

function loadMore() {
    if (allFiles.value.next === null) {
        return
    }

    fetch(allFiles.value.next, {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(res => {
            allFiles.value.data = [...allFiles.value.data, ...res.data]
            allFiles.value.next = res.links.next
            onDeleteAllChange()
        })
}

function onDeleteAllChange() {
    allFiles.value.data.forEach(f => {
        toBeDeleted.value[f.id] = deleteAll.value;
    })
}

function toggleFileSelect(file) {
    toBeDeleted.value[file.id] = !toBeDeleted.value[file.id]
    onDeleteChange(file)
}

function onDeleteChange(file) {
    if (!toBeDeleted.value[file.id]) {
        deleteAll.value = false;
    } else {
        let checked = true;
        for (let id in toBeDeleted.value) {
            if (!toBeDeleted.value[id]) {
                checked = false;
                break;
            }
        }
        deleteAll.value = checked;
    }
}

onUpdated(() => {
    allFiles.value = {
        data: props.files.data,
        next: props.files.links.next
    }
})

onMounted(() => {
    const observer = new IntersectionObserver(entries => entries.forEach(entry => entry.isIntersecting && loadMore(), {
        rootMargin: "-250px 0px 0px 0px"
    }));

    observer.observe(loadMoreIntersect.value)
})
</script>

<style scoped>

</style>
