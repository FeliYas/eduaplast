<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

defineOptions({
    layout: DashboardLayout
});

// Definición de las columnas
const columns = ['orden', 'path', 'titulo'];

// Definición de rutas dinámicas
const createRoute = route('sectores.store');
const updateRoute = (id) => route('sectores.update', { id });
const deleteRoute = (id) => route('sectores.destroy', { id });

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
    <div>
        <div class="py-3 text-xl text-gray-700">
            <h1>Sectores</h1>
        </div>
        <!-- Línea -->
        <hr class="border-t-[3px] border-main-color rounded">
        <DataTable :columns="columns" :data="sectores" :createRoute="createRoute" :updateRoute="updateRoute"
            :deleteRoute="deleteRoute" />
    </div>
</template>
