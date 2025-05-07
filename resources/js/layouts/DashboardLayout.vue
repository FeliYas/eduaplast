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
    }
  },
  mounted() {
    
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