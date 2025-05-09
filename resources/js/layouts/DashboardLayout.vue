<script setup>
import { Head } from '@inertiajs/vue3'
</script>
<template>

  <Head>
    <title>Dashboard</title>
  </Head>
  <div class="dashboard-layout">
    <Sidebar :logo="logo" :collapsed="sidebarCollapsed" />

    <div class="content-area" :class="{ 'with-sidebar': !sidebarCollapsed }">
      <NavbarAdmin :userName="userName" :userEmail="userEmail" @toggle-sidebar="toggleSidebar" @logout="handleLogout" />

      <main class="p-6 bg-white h-screen overflow-y-auto">
        <slot></slot>
      </main>
    </div>
  </div>
</template>


<script>
import Sidebar from '@/components/Sidebar.vue';
import NavbarAdmin from '@/components/NavbarAdmin.vue';
import { useToast } from 'vue-toastification';
import 'vue-toastification/dist/index.css';

export default {
  components: {
    Sidebar,
    NavbarAdmin
  },
  props: {
    userName: {
      type: String,
      default: 'Usuario'
    },
    userEmail: {
      type: String,
      default: 'usuario@example.com'
    },
    logo: {
      type: Object,
      required: true
    },
  },
  data() {
    return {
      sidebarCollapsed: false
    };
  },
  methods: {
    toggleSidebar() {
      this.sidebarCollapsed = !this.sidebarCollapsed;
    },
    handleLogout() {
      // Implement logout logic here
      // If using Laravel authentication:
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '/logout'; // Adjust route as needed

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
      }

      document.body.appendChild(form);
      form.submit();
    },
    notification({ message = "", type = "success" }) {
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
    }
  },
  mounted() {
    this.notification({ message: "Sectores cargados correctamentes" });
  }
}
</script>

<style scoped>
.dashboard-layout {
  display: flex;
  min-height: 100vh;
}

.content-area {
  flex: 1;
  margin-left: 250px;
  /* Same as sidebar width */
  transition: margin-left 0.3s;
}

.content-area.with-sidebar {
  margin-left: 250px;
}

.content-area:not(.with-sidebar) {
  margin-left: 0;
}

@media (max-width: 768px) {
  .content-area {
    margin-left: 0;
  }
}
</style>