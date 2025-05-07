<template>
  <DashboardLayout :logo="logo">
    <template #default>
      <div class="dashboard-page">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
          <h1 class="text-2xl font-semibold text-gray-800">Dashboard Administrativo</h1>
          <p class="text-gray-600 mt-2">Bienvenido al panel de administración de su sitio web.</p>
        </div>
      </div>
    </template>
  </DashboardLayout>
</template>

<script>
import DashboardLayout from '@/layouts/DashboardLayout.vue'

export default {
  name: 'DashboardPage',

  components: {
    DashboardLayout
  },

  props: {
    logo: Object
  },

  data() {
    return {
    }
  },

  mounted() {

  },
}
</script>