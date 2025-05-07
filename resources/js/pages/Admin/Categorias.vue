<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

// Definición de las columnas
const columns = ['orden', 'path', 'titulo'];

// Definición de rutas
const createRoute = '/admin/productos/categorias/store';
const updateRoute = '/admin/productos/categorias/update/__ID__';
const deleteRoute = '/admin/productos/categorias/destroy/__ID__';

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
    <DashboardLayout :logo="logo">
        <div>
            <div class="py-3 text-xl text-gray-700">
                <h1>Categorias</h1>
            </div>
            <!-- Línea -->
            <hr class="border-t-[3px] border-main-color rounded">
            <DataTable :columns="columns" :data="categorias" :createRoute="createRoute" :updateRoute="updateRoute"
                :deleteRoute="deleteRoute" />
        </div>
    </DashboardLayout>
</template>
