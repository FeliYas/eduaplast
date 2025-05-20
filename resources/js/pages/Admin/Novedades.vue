<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

defineOptions({
    layout: DashboardLayout
});

// Definición de las columnas
const columns = ['orden', 'path', 'epigrafe', 'titulo', 'descripcion'];

// Definición de rutas
const createRoute = route('novedades.store');
const updateRoute = (id) => route('novedades.update', { id });
const deleteRoute = (id) => route('novedades.destroy', { id });

const props = defineProps({
    logo: {
        type: String,
        required: true
    },
    novedades: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <div>
        <div class="py-3 text-xl text-gray-700">
            <h1>Novedades</h1>
        </div>
        <!-- Línea -->
        <hr class="border-t-[3px] border-main-color rounded">
        <DataTable :columns="columns" :data="novedades" :createRoute="createRoute" :updateRoute="updateRoute"
            :deleteRoute="deleteRoute" />
    </div>
</template>
