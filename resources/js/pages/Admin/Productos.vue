<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

defineOptions({
    layout: DashboardLayout
});

// Definición de las columnas
const columns = ['orden', 'titulo', 'descripcion', 'categoria_id', 'ficha', 'imagenes'];

// Definición de rutas dinámicas
const createRoute = route('productos.store');
const updateRoute = (id) => route('productos.update', { id });
const deleteRoute = (id) => route('productos.destroy', { id });
const imgsRoute = (id) => route('imagenes.dashboard', { id });

const props = defineProps({
    logo: {
        type: String,
        required: true
    },
    productos: {
        type: Array,
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
            <h1>Productos</h1>
        </div>
        <!-- Línea -->
        <hr class="border-t-[3px] border-main-color rounded">
        <DataTable :columns="columns" :data="productos" :categorias="categorias" :createRoute="createRoute" :updateRoute="updateRoute"
            :deleteRoute="deleteRoute" :imgsRoute="imgsRoute" />
    </div>
</template>
