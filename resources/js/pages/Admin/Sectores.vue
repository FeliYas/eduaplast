<script setup>
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DataTable from '@/components/DataTable.vue';
import { useToast } from "vue-toastification";
import { onMounted } from 'vue';

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

const notification = ({ message = "", type = "success" }) => {
    const toast = useToast();
    toast(
        message,
        {
            type: type,
            position: "bottom-right",
            maxToasts: 2,
            timeout: 5000,
            closeOnClick: true,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: true,
            draggablePercent: 0.6,
            showCloseButtonOnHover: true,
            hideProgressBar: false,
            closeButton: "button",
            icon: true,
            rtl: false
        }
    );
};

onMounted(() => {
    notification({ message: "Sectores cargados correctamente" });
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
