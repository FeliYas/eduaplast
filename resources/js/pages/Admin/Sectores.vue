<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

// Definición de las columnas
const columns = ['orden', 'path', 'titulo'];

// Definición de rutas
const createRoute = '/admin/sectores/store';
const updateRoute = '/admin/sectores/update/__ID__';
const deleteRoute = '/admin/sectores/destroy/__ID__';

const props = defineProps({
    logo: {
        type: String,
        required: true
    },
    sectores: {
        type: Array,
        required: true
    }
});

</script>

<template>
    <DashboardLayout :logo="logo">
        <div>
            <div class="py-3 text-xl text-gray-700">
                <h1>Sectores</h1>
            </div>
            <!-- Línea -->
            <hr class="border-t-[3px] border-main-color rounded">
            <DataTable :columns="columns" :data="sectores" :createRoute="createRoute" :updateRoute="updateRoute"
                :deleteRoute="deleteRoute" />
        </div>
    </DashboardLayout>
</template>
