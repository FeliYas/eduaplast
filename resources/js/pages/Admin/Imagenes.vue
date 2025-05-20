<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

defineOptions({
    layout: DashboardLayout
});

// Definición de las columnas
const columns = ['orden', 'path'];

// Definición de rutas
const createRoute = route('imagenes.store');
const updateRoute = (id) => route('imagenes.update', { id });
const deleteRoute = (id) => route('imagenes.destroy', { id });

const props = defineProps({
    logo: {
        type: String,
        required: true
    },
    imagenes: {
        type: Array,
        required: true
    },
    producto: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div>
        <div class="py-3 text-xl text-gray-700">
            <h1>Imagenes de {{ producto.titulo }}</h1>
        </div>
        <!-- Línea -->
        <hr class="border-t-[3px] border-main-color rounded">
        <DataTable :columns="columns" :data="imagenes" :createRoute="createRoute" :updateRoute="updateRoute"
            :deleteRoute="deleteRoute" :productoId="producto.id" />
    </div>
</template>
