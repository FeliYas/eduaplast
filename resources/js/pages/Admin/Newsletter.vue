<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

// Definición de las columnas
const columns = ['email'];

// Definición de rutas
const deleteRoute = '/admin/newsletter/destroy/__ID__';
const props = defineProps({
    logo: {
        type: String,
        required: true
    },
    mails: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <DashboardLayout :logo="logo">
        <div>
            <div class="py-3 text-xl text-gray-700">
                <h1>Newsletter</h1>
            </div>
            <!-- Línea -->
            <hr class="border-t-[3px] border-main-color rounded">
            <DataTable :columns="columns" :data="mails" :deleteRoute="deleteRoute" />
        </div>
    </DashboardLayout>
</template>
