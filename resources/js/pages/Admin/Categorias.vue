<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

defineOptions({
    layout: DashboardLayout
});

// Definición de las columnas
const columns = ['orden', 'path', 'titulo', 'destacado'];

// Definición de rutas dinámicas
const createRoute = route('categorias.store');
const updateRoute = (id) => route('categorias.update', { id });
const deleteRoute = (id) => route('categorias.destroy', { id });
const destacadoRoute = (id) => route('categorias.toggleDestacado', { id });

const props = defineProps({
    logo: {
        type: String,
        required: true
    },
    categorias: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <div>
        <div class="py-3 text-xl text-gray-700">
            <h1>Categorias</h1>
        </div>
        <!-- Línea -->
        <hr class="border-t-[3px] border-main-color rounded">
        <DataTable :columns="columns" :data="categorias" :createRoute="createRoute" :updateRoute="updateRoute"
            :deleteRoute="deleteRoute" :toggleDestacadoRoute="destacadoRoute"/>
    </div>
</template>
