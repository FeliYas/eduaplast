<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

// Definición de las columnas
const columns = ['orden', 'path'];

// Definición de rutas
const createRoute = '/admin/productos/productos/imagenes/store';
const updateRoute = '/admin/productos/productos/imagenes/update/__ID__';
const deleteRoute = '/admin/productos/productos/imagenes/delete/__ID__';

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
    <DashboardLayout :logo="logo">
        <div>
            <div class="py-3 text-xl text-gray-700">
                <h1>Imagenes de {{ producto.titulo }}</h1>
            </div>
            <!-- Línea -->
            <hr class="border-t-[3px] border-main-color rounded">
            <DataTable :columns="columns" :data="imagenes" :createRoute="createRoute" :updateRoute="updateRoute"
                :deleteRoute="deleteRoute" :productoId="producto.id" />
        </div>
    </DashboardLayout>
</template>
