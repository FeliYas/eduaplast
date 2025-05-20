<script setup>
import { ref, reactive, onMounted, inject } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import QuillEditor from '@/components/QuillEditor.vue';
import DeleteModal from '@/components/Modales/DeleteModal.vue';

defineOptions({
    layout: DashboardLayout
});

// Inyectar el sistema de notificaciones global
const notification = inject('noti');

// Props
const props = defineProps({
  sliders: Array,
  logo: String
});

// States
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const deletingSliderId = ref(null);

// Refs
const fileInput = ref(null);
const editFileInput = ref(null);

// Formulario de creación
const createForm = useForm({
    orden: '',
    titulo: '',
    descripcion: '',
    path: null
});

// Formulario de edición
const editForm = useForm({
    id: null,
    orden: '',
    titulo: '',
    descripcion: '',
    path: null
});

// Methods
const openCreateModal = () => {
  showCreateModal.value = true;
  createForm.fileLabel = 'Seleccionar archivo';
};

const closeCreateModal = () => {
  showCreateModal.value = false;
  resetCreateForm();
};

const resetCreateForm = () => {
  createForm.orden = '';
  createForm.titulo = '';
  createForm.descripcion = '';
  createForm.path = null;
  createForm.fileLabel = 'Seleccionar archivo';
  createForm.filePreview = null;
  if (fileInput.value) fileInput.value.value = '';
};

const handleFileChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;

  createForm.path = file;
  createForm.fileLabel = file.name;

  // Vista previa para imágenes
  if (isImage(file.name)) {
    const reader = new FileReader();
    reader.onload = (e) => {
      createForm.filePreview = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    createForm.filePreview = null;
  }
};

const submitCreateForm = () => {
    createForm.post(route('slider.store'), {
        preserveScroll: true,
        onSuccess: () => {
            notification({ message: "Slider creado correctamente", type: "success" });
            closeCreateModal();
        },
        onError: (errors) => {
            notification({ message: errors[0], type: "error" });
        }
    });
};

const openEditModal = (slider) => {
  editForm.id = slider.id;
  editForm.orden = slider.orden;
  editForm.titulo = slider.titulo;
  editForm.descripcion = slider.descripcion;
  editForm.currentPath = slider.path;
  editForm.path = null;
  editForm.fileLabel = 'Seleccionar nuevo archivo (opcional)';

  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
};

const handleEditFileChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;

  editForm.path = file;
  editForm.fileLabel = file.name;
};

const submitEditForm = () => {
    editForm.post(route('slider.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            notification({ message: "Slider actualizado correctamente", type: "success" });
            closeEditModal();
        },
        onError: (errors) => {
            notification({ message: errors[0], type: "error" });
        }
    });
};

const openDeleteModal = (id) => {
  deletingSliderId.value = id;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  deletingSliderId.value = null;
};

const submitDeleteForm = () => {
  router.delete(route('slider.destroy', deletingSliderId.value), {
    onSuccess: () => {
      notification({ message: "Slider eliminado correctamente", type: "success" });
      closeDeleteModal();
    }
  });
};

// Helper methods
const isImage = (path) => {
  const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
  const extension = getExtension(path);
  return imageExtensions.includes(extension.toLowerCase());
};

const isVideo = (path) => {
  const videoExtensions = ['mp4', 'webm', 'ogg'];
  const extension = getExtension(path);
  return videoExtensions.includes(extension.toLowerCase());
};

const getExtension = (path) => {
  return path.split('.').pop() || '';
};
</script>

<template>
    <div class="h-full">
      <div class="py-3 text-xl text-gray-700">
        <h1>Home Slider</h1>
      </div>
      <!-- Línea -->
      <hr class="border-t-[3px] border-main-color rounded">

      <!-- Mostrar Sliders existentes -->
      <div class="bg-gray-100 rounded-xl shadow-lg overflow-hidden mt-4 p-6">
        <div class="flex justify-start items-center mb-6 text-main-color">
          <!-- Botón Agregar -->
          <button class="btn-primary btn-icon-left" @click="openCreateModal">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
            </svg>
            Agregar
          </button>
        </div>

        <!-- Lista de sliders -->
        <div class="grid grid-cols-3 gap-6">
          <div v-for="slider in sliders" :key="slider.id" class="bg-gray-50 p-4 rounded-md border border-main-color flex flex-col justify-between">
            <!-- Imagen o Video -->
            <img v-if="isImage(slider.path)" :src="slider.path" :alt="slider.titulo"
              class="object-cover w-full h-[246px] border border-gray-300">
            <video v-else-if="isVideo(slider.path)" class="object-cover w-full h-[246px]" controls>
              <source :src="slider.path" :type="`video/${getExtension(slider.path)}`">
              Tu navegador no soporta el formato de video.
            </video>

            <div class="mt-6 flex-grow">
              <div class="flex items-start gap-4">
                <h3 class="text-2xl font-medium text-black max-w-3/4">{{ slider.titulo }}</h3>
                <span class="text-black font-medium text-2xl">-</span>
                <p class="text-black mt-1.5">{{ slider.orden.toUpperCase() }}</p>
              </div>

              <div class="text-gray-500 line-clamp-3 h-[96px] py-3 custom-editor" v-html="slider.descripcion"></div>
            </div>

            <div class="flex gap-2 mt-2">
              <!-- Botones de acción -->
              <button type="button" @click="openEditModal(slider)"
                class="hover:bg-orange-100 p-2 rounded-full transition-all duration-200 cursor-pointer">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z"
                    stroke="#f86903" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  <path
                    d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13"
                    stroke="#f86903" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </button>
              <button type="button" @click="openDeleteModal(slider.id)"
                class="text-red-600 hover:bg-red-100 p-2 rounded-full transition-all duration-200 cursor-pointer">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal de creación con transición -->
      <Transition enter-active-class="transition duration-300 ease-out" enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-200 ease-in"
        leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
        <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
          <!-- Overlay con transición -->
          <Transition enter-active-class="transition-opacity duration-300" enter-from-class="opacity-0"
            enter-to-class="opacity-60" leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-60" leave-to-class="opacity-0">
            <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm" @click="closeCreateModal"></div>
          </Transition>

          <!-- Modal -->
          <div class="relative w-full max-w-[700px] z-50 bg-white rounded-lg shadow-lg overflow-hidden">
            <form @submit.prevent="submitCreateForm" class="bg-white rounded-lg shadow-lg w-full overflow-hidden">
              <!-- Header -->
              <div class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                <h2 class="text-xl font-semibold flex items-center gap-2">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round" />
                  </svg>
                  Crear Slider
                </h2>
              </div>

              <div class="p-6 text-black">
                <!-- Campo Orden y Título en la misma fila horizontal -->
                <div class="flex mb-4 w-full gap-4">
                  <div class="w-1/2">
                    <label for="orden" class="block font-medium text-gray-700 mb-1">
                      Orden *
                    </label>
                    <input v-model="createForm.orden" id="orden" type="text" required
                      class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                      placeholder="Posición de orden">
                  </div>

                  <div class="w-1/2">
                    <label for="titulo" class="block font-medium text-gray-700 mb-1">
                      Título *
                    </label>
                    <input v-model="createForm.titulo" id="titulo" type="text" required
                      class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                      placeholder="Título del slider">
                  </div>
                </div>

                <!-- Campo Descripción -->
                <div class="mb-4">
                  <label for="descripcion" class="block font-medium text-gray-700 mb-1">
                    Descripcion *
                  </label>
                  <QuillEditor unique_ref="descripcion_primario" placeholder="Descripcion"
                    :initial_content="createForm.descripcion" v-on:text_changed="createForm.descripcion = $event">
                  </QuillEditor>
                </div>

                <!-- Campo Archivo (Path) -->
                <div class="mb-4">
                  <label for="path" class="block font-medium text-gray-700 mb-1">
                    Archivo (Imagen o Video) *
                  </label>
                  <div class="relative w-full">
                    <label for="path" id="customFileLabel"
                      class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                      {{ createForm.fileLabel }}
                    </label>
                    <input type="file" id="path" ref="fileInput" @change="handleFileChange" class="hidden" required>
                  </div>
                  <p class="text-xs text-gray-400 mt-1">
                    Formatos permitidos: JPEG, PNG, JPG, GIF, SVG, MP4, WEBM, OGG. Recomendacion: 1400x750 px
                  </p>

                  <!-- Vista previa del archivo -->
                  <div v-if="createForm.filePreview" class="mt-3">
                    <p class="block text-sm text-gray-700">Vista previa:</p>
                    <img :src="createForm.filePreview" alt="Vista previa de la imagen"
                      class="mt-2 max-h-40 border border-main-color rounded-md bg-gray-200 p-2">
                  </div>
                </div>
              </div>

              <!-- Footer con botones -->
              <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                <button type="button" @click="closeCreateModal" class="btn-secondary btn-icon-left">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Cancelar
                </button>
                <button type="submit" class="btn-primary btn-icon-left">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Crear
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>

      <!-- Modal de edición con transición -->
      <Transition enter-active-class="transition duration-300 ease-out" enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-200 ease-in"
        leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
        <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
          <!-- Overlay con transición -->
          <Transition enter-active-class="transition-opacity duration-300" enter-from-class="opacity-0"
            enter-to-class="opacity-60" leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-60" leave-to-class="opacity-0">
            <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm" @click="closeEditModal"></div>
          </Transition>

          <!-- Modal -->
          <div class="relative w-full max-w-[700px] z-50 bg-white rounded-lg shadow-lg overflow-hidden">
            <form @submit.prevent="submitEditForm" class="bg-white rounded-lg shadow-lg w-full overflow-hidden" >
              <!-- Header -->
              <div class="bg-main-color bg-opacity-10 px-6 py-2 border-b border-main-color border-opacity-20">
                <h2 class="text-xl font-semibold flex items-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Editar Slider
                </h2>
              </div>

              <!-- Formulario -->
              <div class="p-4 text-black">
                <!-- Campo Orden y Título -->
                <div class="flex mb-2 w-full gap-4">
                  <div class="w-1/2">
                    <label for="edit_orden" class="block text-sm font-medium text-gray-700 mb-1">
                      Orden *
                    </label>
                    <input v-model="editForm.orden" id="edit_orden" type="text" required
                      class="text-sm w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color">
                  </div>

                  <div class="w-1/2">
                    <label for="edit_titulo" class="block text-sm font-medium text-gray-700 mb-1">
                      Título *
                    </label>
                    <input v-model="editForm.titulo" id="edit_titulo" type="text" required
                      class="text-sm w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color">
                  </div>
                </div>

                <!-- Campo Descripción -->
                <div class="mb-2">
                  <label for="edit_descripcion" class="block text-gray-700 text-mg mb-1">Descripción *</label>
                  <QuillEditor unique_ref="descripcion_primario" placeholder="Descripcion"
                    :initial_content="editForm.descripcion" v-on:text_changed="editForm.descripcion = $event">
                  </QuillEditor>
                </div>

                <!-- Archivo actual -->
                <div class="mb-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Archivo actual</label>
                  <div class="w-full h-[200px] border border-main-color rounded-md bg-gray-200 p-2">
                    <img v-if="isImage(editForm.currentPath)" :src="editForm.currentPath"
                      :alt="editForm.titulo" class="w-full h-full object-contain">
                    <video v-else-if="isVideo(editForm.currentPath)" class="w-full h-full object-contain" controls>
                      <source :src="editForm.currentPath"
                        :type="`video/${getExtension(editForm.currentPath)}`">
                      Tu navegador no soporta el formato de video.
                    </video>
                  </div>
                </div>

                <!-- Campo Archivo (Path) -->
                <div class="mb-2">
                  <label for="edit_path" class="block text-sm font-medium text-gray-700 mb-1">Nuevo archivo</label>
                  <div class="relative w-full">
                    <label for="edit_path"
                      class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                      {{ editForm.fileLabel }}
                    </label>
                    <input type="file" id="edit_path" ref="editFileInput" @change="handleEditFileChange" class="hidden">
                  </div>
                  <p class="text-xs text-gray-500 mt-1">
                    Formatos permitidos: JPEG, PNG, JPG, GIF, SVG, MP4, WEBM, OGG.
                  </p>
                </div>
              </div>

              <!-- Footer con botones -->
              <div class="px-4 py-3 bg-gray-50 flex justify-end gap-3">
                <button type="button" @click="closeEditModal" class="btn-secondary btn-icon-left">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Cancelar
                </button>
                <button type="submit" class="btn-primary btn-icon-left">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Actualizar
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>

      <!-- Usando el componente DeleteModal -->
      <DeleteModal v-if="showDeleteModal" :show="showDeleteModal" @close="closeDeleteModal" @confirm="submitDeleteForm"
        title="¿Estás seguro de que querés eliminar este slider?" message="Esta acción no se puede deshacer." />
    </div>
</template>