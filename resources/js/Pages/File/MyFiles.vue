<template>
    <AuthenticatedLayout>
        {{$page.url}}
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    name: "MyFiles",
    components: {AuthenticatedLayout}
}
</script>

<style scoped>

</style>
