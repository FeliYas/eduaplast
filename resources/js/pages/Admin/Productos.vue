<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

// Definición de las columnas
const columns = ['orden', 'titulo', 'descripcion', 'categoria_id', 'ficha', 'imagenes'];

// Definición de rutas
const createRoute = '/admin/productos/productos/store';
const updateRoute = '/admin/productos/productos/update/__ID__';
const deleteRoute = '/admin/productos/productos/delete/__ID__';
const imgsRoute = '/admin/productos/productos/imagenes';

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
    <DashboardLayout :logo="logo">
        <div>
            <div class="py-3 text-xl text-gray-700">
                <h1>Productos</h1>
            </div>
            <!-- Línea -->
            <hr class="border-t-[3px] border-main-color rounded">
            <DataTable :columns="columns" :data="productos" :categorias="categorias" :createRoute="createRoute" :updateRoute="updateRoute"
                :deleteRoute="deleteRoute" :imgsRoute="imgsRoute" />
        </div>
    </DashboardLayout>
</template>
