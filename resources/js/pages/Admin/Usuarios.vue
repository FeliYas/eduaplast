<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';

// Definición de las columnas
const columns = ['username', 'name', 'email', 'role', 'password'];

// Definición de rutas
const deleteRoute = '/admin/usuarios/delete/__ID__';
const createRoute = '/admin/usuarios/create';
const updateRoute = '/admin/usuarios/edit/__ID__';

const props = defineProps({
    logo: {
        type: String,
        required: true
    },
    usuarios: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <DashboardLayout :logo="logo">
        <div>
            <div class="py-3 text-xl text-gray-700">
                <h1>Usuarios</h1>
            </div>
            <!-- Línea -->
            <hr class="border-t-[3px] border-main-color rounded">
            <DataTable :columns="columns" :data="usuarios" :deleteRoute="deleteRoute" :createRoute="createRoute" :updateRoute="updateRoute" />
        </div>
    </DashboardLayout>
</template>
