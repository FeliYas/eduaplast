<script setup>
    import { ref, computed, onMounted, inject } from 'vue';
    import { router } from '@inertiajs/vue3';
    import DeleteModal from '@/components/Modales/DeleteModal.vue';
    import QuillEditor from '@/components/QuillEditor.vue'; // Import QuillEditor

    const props = defineProps({
        columns: Array,
        data: Array,
        deleteRoute: {
            type: Function,
            required: true
        },
        updateRoute: {
            type: Function,
            required: true
        },
        createRoute: {
            type: [String, Function], // createRoute puede ser string o función
            required: true
        },
        categorias: Array,
        toggleDestacadoRoute: {
            type: Function,
            required: true
        },
        imgsRoute: {
            type: [String, Function],
            required: true
        },
        productoId: Number // Add productoId prop
    });

    // Inyectar el sistema de notificaciones global
    const notification = inject('noti');

    const showCreateModal = ref(false);
    const showEditModal = ref(false);
    const showDeleteModal = ref(false);
    const showPassword = ref(false);
    const showEditPassword = ref(false);
    const currentItemId = ref(null);
    const editFormData = ref({});
    const createFormData = ref({});
    const fileInputLabel = ref('Elija una imagen');
    const editFileInputLabel = ref('Elija una nueva imagen');
    const fichaInputLabel = ref('Elija un PDF');
    const editFichaInputLabel = ref('Elija un nuevo PDF');

    // Para la vista previa de imágenes
    const editImagePreview = ref('');

    const openCreateModal = () => {
        // Reset form data
        createFormData.value = {};
        props.columns.forEach(column => {
            if (column !== 'id' && column !== 'destacado') {
                createFormData.value[column] = '';
            }
            if (column === 'role') {
                createFormData.value[column] = 'user'; //
            }
        });
        // Set producto_id in createFormData
        createFormData.value.producto_id = props.productoId;
        showPassword.value = false;
        showCreateModal.value = true;
    };

    const closeCreateModal = () => {
        showCreateModal.value = false;
    };

    const openEditModal = (item) => {
        editFormData.value = { ...item };

        // Si hay una imagen, configurar la vista previa
        if (item.path) {
            editImagePreview.value = item.path;
        } else {
            editImagePreview.value = '';
        }

        showEditPassword.value = false;
        showEditModal.value = true;
    };

    const togglePasswordVisibility = () => {
        showPassword.value = !showPassword.value;
    };

    const toggleEditPasswordVisibility = () => {
        showEditPassword.value = !showEditPassword.value;
    };

    const closeEditModal = () => {
        showEditModal.value = false;
    };

    const openDeleteModal = (id) => {
        currentItemId.value = id;
        showDeleteModal.value = true;
    };

    const closeDeleteModal = () => {
        showDeleteModal.value = false;
    };

    const handleCreateFileChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            fileInputLabel.value = file.name;
            createFormData.value.path = file;
        } else {
            fileInputLabel.value = 'Elija una imagen';
        }
    };

    const handleEditFileChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            editFileInputLabel.value = file.name;
            editFormData.value.path = file;
        } else {
            editFileInputLabel.value = 'Elija una nueva imagen';
        }
    };
    const handleCreateFichaChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            fileInputLabel.value = file.name;
            createFormData.value.path = file;
        } else {
            fileInputLabel.value = 'Elija una imagen';
        }
    };
    const handleEditFichaChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            editFileInputLabel.value = file.name;
            editFormData.value.path = file;
        } else {
            editFileInputLabel.value = 'Elija una nueva imagen';
        }
    };
    const openFicha = (fichaPath) => {
        if (fichaPath) {
            // Abrir en una nueva pestaña para descargar/ver el PDF
            window.open(fichaPath, '_blank');
        }
    };
    const submitCreateForm = () => {
        // Crear FormData para enviar archivos
        const formData = new FormData();

        Object.keys(createFormData.value).forEach(key => {
            formData.append(key, createFormData.value[key]);
        });

        // Usar la ruta, que puede ser una string o una función
        const createUrl = typeof props.createRoute === 'function' ? props.createRoute() : props.createRoute;

        router.post(createUrl, formData, {
            onSuccess: (page) => {
            // Accede al mensaje flash de la respuesta
            if (page.props.flash && page.props.flash.message) {
                notification({ message: page.props.flash.message, type: "success" });
            } else {
                notification({ message: "Creado correctamente", type: "success" });
            }
            closeCreateModal();
            },
            onError: (errors) => {
                notification({ message: errors[0], type: "error" });
            }
        });
    };

    const submitEditForm = () => {
        const formData = new FormData();

        Object.keys(editFormData.value).forEach(key => {
            if (key !== 'id') {
                // Si el campo es 'path' y no se seleccionó un archivo nuevo, no lo agregamos
                if (key === 'path' && typeof editFormData.value[key] === 'string') {
                    return;
                }
                // Si el campo es 'ficha' y está vacío, no lo agregamos
                if (key === 'ficha' && (!editFormData.value[key] || editFormData.value[key] === '')) {
                    return;
                }
                formData.append(key, editFormData.value[key]);
            }
        });

        formData.append('_method', 'PUT');

        // Usar directamente la función updateRoute
        const updateUrl = props.updateRoute(editFormData.value.id);

        router.post(updateUrl, formData, {
            onSuccess: (page) => {
            // Accede al mensaje flash de la respuesta
            if (page.props.flash && page.props.flash.message) {
                notification({ message: page.props.flash.message, type: "success" });
            } else {
                notification({ message: "Actualizado correctamente", type: "success" });
            }
            closeEditModal();
            },
            onError: (errors) => {
                notification({ message: errors[0], type: "error" });
            }
        });
    };

    const getCategoriaName = (categoriaId) => {
        if (!props.categorias || !categoriaId) return 'N/A';
        const categoria = props.categorias.find(cat => cat.id === categoriaId);
        return categoria ? categoria.titulo.charAt(0).toUpperCase() + categoria.titulo.slice(1) : 'N/A';
    };

    const submitDeleteForm = () => {
        // Usar directamente la función deleteRoute
        const deleteUrl = props.deleteRoute(currentItemId.value);

        router.delete(deleteUrl, {
            onSuccess: (page) => {
            // Accede al mensaje flash de la respuesta
            if (page.props.flash && page.props.flash.message) {
                notification({ message: page.props.flash.message, type: "success" });
            } else {
                notification({ message: "Eliminado correctamente", type: "success" });
            }
            closeDeleteModal();
            },
        });
    };

    const toggleDestacado = (id, isChecked) => {
        // Usar directamente la función toggleDestacadoRoute
        const toggleUrl = props.toggleDestacadoRoute(id);

        fetch(toggleUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: id,
                destacado: isChecked ? 1 : 0
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                notification({ message: "Destacado actualizado correctamente", type: "success" });
            } else {
                console.error("Error al actualizar el destacado");
            }
        });
    };
</script>

<template>
    <div class="py-4">

        <div v-if="createRoute" class="flex justify-start items-center mb-6">
            <button class="btn-primary flex items-center gap-2" @click="openCreateModal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Agregar
            </button>
        </div>

        <div class="grid-table-container">
            <!-- Encabezado de la grid -->
            <div class="grid-header">
                <template v-for="column in columns" :key="column">
                    <div v-if="column !== 'password'" class="grid-header-cell">
                        <template v-if="column === 'categoria_id'">
                            Categoria
                        </template>
                        <template v-else-if="column === 'path'">
                            Imagen
                        </template>
                        <template v-else>
                            {{ column.charAt(0).toUpperCase() + column.slice(1) }}
                        </template>
                    </div>
                </template>
                <div class="grid-header-cell">
                    Acciones
                </div>
            </div>

            <!-- Contenido de la grid -->
            <div class="grid-body">
                <!-- Mensaje cuando no hay datos -->
                <div v-if="!data || data.length === 0" class="grid-no-data">
                    No hay datos disponibles
                </div>

                <!-- Filas de datos -->
                <template v-else>
                    <div v-for="row in data" :key="row.id" class="grid-row">
                        <template v-for="column in columns" :key="column">
                            <div v-if="column !== 'password' && column !== 'destacado'"
                                :class="['grid-cell', column === 'path' ? 'grid-image-cell' : '']">
                                <!-- Celda de imagen -->
                                <template v-if="column === 'path'">
                                    <img v-if="row.path" :src="row.path" alt="Imagen del producto"
                                        class="grid-image">
                                    <div v-else class="grid-no-image">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </template>
                                <!-- Campo categoria_id con nombre de categoría -->
                                <template v-else-if="column === 'categoria_id'">
                                    {{ getCategoriaName(row[column]) }}
                                </template>
                                <!-- Campo imagenes con enlace -->
                                <template v-else-if="column === 'imagenes'">
                                    <div class="flex justify-center">
                                        <a :href="imgsRoute(row.id)"
                                            class="text-blue-900 hover:text-blue-500 transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6 hover:scale-110 transform transition duration-200"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </template>
                                <!-- Celda de ficha -->
                                <template v-else-if="column === 'ficha'">
                                    <div class="flex justify-center">
                                        <template v-if="row.ficha">
                                            <button @click="openFicha(row.ficha)"
                                                class="text-blue-900 hover:text-blue-500 flex items-center gap-1 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs">Ver PDF</span>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <span class="text-gray-400 flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                                <span class="text-xs">Sin ficha</span>
                                            </span>
                                        </template>
                                    </div>
                                </template>
                                <!-- Campo descripcion con v-html -->
                                <template v-else-if="column === 'descripcion'">
                                    <div class="custom-editor max-h-[100px] overflow-y-auto" v-html="row[column]"></div>
                                </template>
                                <!-- Otras celdas con datos -->
                                <template v-else>
                                    {{ row[column] ? row[column].charAt(0).toUpperCase() + row[column].slice(1) : '' }}
                                </template>
                            </div>
                            <!-- Destacado toggle -->
                            <template v-if="column === 'destacado'">
                                <div class="grid-cell">
                                    <div class="flex justify-center">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer" :checked="row.destacado"
                                                @change="toggleDestacado(row.id, $event.target.checked)">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#374977]">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </template>
                        </template>

                        <!-- Celda de acciones -->
                        <div class="grid-cell grid-actions-cell">
                            <div class="grid-actions">
                                <button v-if="updateRoute" @click="openEditModal(row)"
                                    class="action-button edit-button">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.2799 6.40005L11.7399 15.94C10.7899 16.89 7.96987 17.33 7.33987 16.7C6.70987 16.07 7.13987 13.25 8.08987 12.3L17.6399 2.75002C17.8754 2.49308 18.1605 2.28654 18.4781 2.14284C18.7956 1.99914 19.139 1.92124 19.4875 1.9139C19.8359 1.90657 20.1823 1.96991 20.5056 2.10012C20.8289 2.23033 21.1225 2.42473 21.3686 2.67153C21.6147 2.91833 21.8083 3.21243 21.9376 3.53609C22.0669 3.85976 22.1294 4.20626 22.1211 4.55471C22.1128 4.90316 22.0339 5.24635 21.8894 5.5635C21.7448 5.88065 21.5375 6.16524 21.2799 6.40005V6.40005Z"
                                            stroke="#f86903" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M11 4H6C4.93913 4 3.92178 4.42142 3.17163 5.17157C2.42149 5.92172 2 6.93913 2 8V18C2 19.0609 2.42149 20.0783 3.17163 20.8284C3.92178 21.5786 4.93913 22 6 22H17C19.21 22 20 20.2 20 18V13"
                                            stroke="#f86903" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <button v-if="deleteRoute" @click="openDeleteModal(row.id)"
                                    class="action-button delete-button">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4 6H20M16 6L15.7294 5.18807C15.4671 4.40125 15.3359 4.00784 15.0927 3.71698C14.8779 3.46013 14.6021 3.26132 14.2905 3.13878C13.9376 3 13.523 3 12.6936 3H11.3064C10.477 3 10.0624 3 9.70951 3.13878C9.39792 3.26132 9.12208 3.46013 8.90729 3.71698C8.66405 4.00784 8.53292 4.40125 8.27064 5.18807L8 6M18 6V16.2C18 17.8802 18 18.7202 17.673 19.362C17.3854 19.9265 16.9265 20.3854 16.362 20.673C15.7202 21 14.8802 21 13.2 21H10.8C9.11984 21 8.27976 21 7.63803 20.673C7.07354 20.3854 6.6146 19.9265 6.32698 19.362C6 18.7202 6 17.8802 6 16.2V6M14 10V17M10 10V17"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <!-- Modal de Crear -->
        <Transition name="modal">
            <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
                <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm" @click="closeCreateModal"></div>
                <Transition name="modal-content">
                    <div class="relative w-full max-w-md z-50 bg-white rounded-lg shadow-lg max-h-[90vh] overflow-y-auto overflow-x-hidden">
                        <form @submit.prevent="submitCreateForm" enctype="multipart/form-data">
                            <!-- Header -->
                            <div
                                class="bg-main-color bg-opacity-10 px-6 py-4 border-b border-main-color border-opacity-20">
                                <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    Crear
                                </h2>
                            </div>

                            <!-- Formulario -->
                            <div class="p-6 text-black">
                                <div v-for="column in columns" :key="column" class="mb-4 ">

                                    <template v-if="column === 'imagenes'"></template>
                                    <template v-else-if="column === 'destacado'"></template>

                                    <!-- Manejo de campo path/imagen -->
                                    <template v-else-if="column === 'path'">
                                        <label :for="column" class="block font-medium text-gray-700 mb-1">Imagen</label>
                                        <div class="relative w-full">
                                            <label :for="column"
                                                class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                                {{ fileInputLabel }}
                                            </label>
                                            <input type="file" :id="column" @change="handleCreateFileChange"
                                                class="hidden">
                                        </div>
                                    </template>

                                    <!-- Campo categoria_id con select de categorías -->
                                    <template v-else-if="column === 'categoria_id'">
                                        <label :for="column" class="block font-medium text-gray-700 mb-1">
                                            Categoria
                                        </label>
                                        <select :id="column" v-model="createFormData[column]"
                                            class="w-full border border-main-color px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                            required>
                                            <option value="" disabled>Seleccione una categoría</option>
                                            <option v-for="categoria in categorias" :key="categoria.id"
                                                :value="categoria.id">
                                                {{ categoria.titulo }}
                                            </option>
                                        </select>
                                    </template>

                                    <!-- Campo ficha-->
                                    <template v-else-if="column === 'ficha'">
                                        <label :for="column" class="block font-medium text-gray-700 mb-1">Ficha Técnica
                                            - PDF (Opcional)</label>
                                        <div class="relative w-full">
                                            <label :for="column"
                                                class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                                {{ fichaInputLabel }}
                                            </label>
                                            <input type="file" :id="column" @change="handleCreateFichaChange"
                                                accept="application/pdf" class="hidden">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Máximo 2MB, formato PDF</p>
                                    </template>

                                    <!-- Campo role con select -->
                                    <template v-else-if="column === 'role'">
                                        <label :for="column" class="block font-medium text-gray-700 mb-1">
                                            Role
                                        </label>
                                        <select :id="column" v-model="createFormData[column]"
                                            class="w-full border border-main-color px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                            required>
                                            <option value="user">User</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </template>

                                    <!-- Campo password con toggle para mostrar/ocultar -->
                                    <template v-else-if="column === 'password'">
                                        <label :for="column" class="block font-medium text-gray-700 mb-1">
                                            Password
                                        </label>
                                        <div class="relative">
                                            <input :type="showPassword ? 'text' : 'password'" :id="column"
                                                v-model="createFormData[column]"
                                                class="w-full border border-main-color px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color pr-10"
                                                required>
                                            <button type="button"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 cursor-pointer"
                                                @click="togglePasswordVisibility">
                                                <!-- Icono de ojo abierto cuando password oculto -->
                                                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <!-- Icono de ojo tachado cuando password visible -->
                                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943-9.543-7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Campo descripcion con QuillEditor -->
                                    <template v-else-if="column === 'descripcion'">
                                        <label :for="column" class="block font-medium text-gray-700 mb-1">
                                            Descripcion
                                        </label>
                                        <QuillEditor :unique_ref="`create_${column}`" placeholder="Descripcion"
                                            :initial_content="createFormData[column]"
                                            v-on:text_changed="createFormData[column] = $event" />
                                    </template>

                                    <!-- Campo de texto normal para otros casos -->
                                    <template v-else>
                                        <label :for="column" class="block font-medium text-gray-700 mb-1">
                                            {{ column.charAt(0).toUpperCase() + column.slice(1) }}
                                        </label>
                                        <input :id="column" :name="column" v-model="createFormData[column]" type="text"
                                            class="w-full border border-main-color px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                            required>
                                    </template>
                                </div>
                            </div>

                            <!-- Footer con botones -->
                            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                                <button type="button" @click="closeCreateModal"
                                    class="btn-secondary px-4 py-2 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancelar
                                </button>
                                <button type="submit" class="btn-primary px-4 py-2 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Crear
                                </button>
                            </div>
                        </form>
                    </div>
                </Transition>
            </div>
        </Transition>

        <!-- Modal de Editar -->
        <Transition name="modal">
            <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
                <Transition name="modal-backdrop">
                    <div class="absolute inset-0 bg-black opacity-60 backdrop-blur-sm" @click="closeEditModal"></div>
                </Transition>
                <Transition name="modal-content">
                    <div class="relative w-full max-w-xl z-50 bg-white rounded-lg shadow-lg max-h-[90vh] overflow-y-auto overflow-x-hidden">
                        <form @submit.prevent="submitEditForm" enctype="multipart/form-data">
                            <!-- Header -->
                            <div
                                class="bg-main-color bg-opacity-10 px-5 py-3 border-b border-main-color border-opacity-20">
                                <h2 class="text-white text-xl font-semibold flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar
                                </h2>
                            </div>

                            <!-- Formulario -->
                            <div class="p-4 text-black">
                                <div v-for="column in columns" :key="column" class="mb-4">

                                    <template v-if="column === 'imagenes'"></template>
                                    <template v-else-if="column === 'destacado'"></template>

                                    <!-- Manejo de campos específicos -->
                                    <template v-else-if="column === 'path'">
                                        <label :for="`edit_${column}`"
                                            class="block font-medium text-gray-700 mb-1">Imagen</label>
                                        <div class="relative w-full">
                                            <label :for="`edit_${column}`"
                                                class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                                {{ editFileInputLabel }}
                                            </label>
                                            <input type="file" :id="`edit_${column}`" @change="handleEditFileChange"
                                                class="hidden">
                                        </div>

                                        <div v-if="editImagePreview" class="mt-2">
                                            <p class="block text-gray-700">Imagen actual:</p>
                                            <img :src="editImagePreview" alt="Imagen actual"
                                                class="w-full h-40 object-contain border border-main-color rounded-md bg-gray-200 p-2">
                                        </div>
                                    </template>
                                    
                                    <!-- Manejo de campo ficha -->
                                    <template v-else-if="column === 'ficha'">
                                        <label :for="`edit_${column}`"
                                            class="block font-medium text-gray-700 mb-1">Ficha Técnica - PDF
                                            (Opcional)</label>
                                        <div class="relative w-full">
                                            <label :for="`edit_${column}`"
                                                class="block border border-main-color rounded-md bg-white px-4 py-2 w-full text-gray-600 cursor-pointer text-center">
                                                {{ editFichaInputLabel }}
                                            </label>
                                            <input type="file" :id="`edit_${column}`" @change="handleEditFichaChange"
                                                accept="application/pdf" class="hidden">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Máximo 2MB, formato PDF</p>

                                        <!-- Mostrar ficha actual si existe -->
                                        <div v-if="editFormData.ficha && typeof editFormData.ficha === 'string'"
                                            class="mt-2">
                                            <p class="block text-gray-700">Ficha actual:</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <button @click="openFicha(editFormData.ficha)" type="button"
                                                    class="text-blue-500 hover:underline text-sm">
                                                    Ver ficha actual
                                                </button>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Campo categoria_id con select de categorías -->
                                    <template v-else-if="column === 'categoria_id'">
                                        <label :for="`edit_${column}`" class="block font-medium text-gray-700 mb-1">
                                            Categoria
                                        </label>
                                        <select :id="`edit_${column}`" v-model="editFormData[column]"
                                            class="w-full border border-main-color px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color"
                                            required>
                                            <option value="" disabled>Seleccione una categoría</option>
                                            <option v-for="categoria in categorias" :key="categoria.id"
                                                :value="categoria.id">
                                                {{ categoria.titulo }}
                                            </option>
                                        </select>
                                    </template>

                                    

                                    <!-- Campo role con select -->
                                    <template v-else-if="column === 'role'">
                                        <label :for="`edit_${column}`" class="block font-medium text-gray-700 mb-1">
                                            Role
                                        </label>
                                        <select :id="`edit_${column}`" v-model="editFormData[column]"
                                            class="w-full border border-main-color px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color">
                                            <option value="user">User</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </template>

                                    <!-- Campo password con toggle para mostrar/ocultar -->
                                    <template v-else-if="column === 'password'">
                                        <label :for="`edit_${column}`" class="block font-medium text-gray-700 mb-1">
                                            Password
                                        </label>
                                        <div class="relative">
                                            <input :type="showEditPassword ? 'text' : 'password'" :id="`edit_${column}`"
                                                v-model="editFormData[column]"
                                                class="w-full border border-main-color px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color pr-10"
                                                placeholder="Dejar vacío para mantener la contraseña actual">
                                            <button type="button"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 cursor-pointer"
                                                @click="toggleEditPasswordVisibility">
                                                <!-- Icono de ojo abierto cuando password oculto -->
                                                <svg v-if="!showEditPassword" xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <!-- Icono de ojo tachado cuando password visible -->
                                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268-2.943-9.543-7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Campo descripcion con QuillEditor -->
                                    <template v-else-if="column === 'descripcion'">
                                        <label :for="`edit_${column}`" class="block font-medium text-gray-700 mb-1">
                                            Descripcion
                                        </label>
                                        <QuillEditor :unique_ref="`edit_${column}`" placeholder="Descripcion"
                                            :initial_content="editFormData[column]"
                                            v-on:text_changed="editFormData[column] = $event" />
                                    </template>

                                    <!-- Campo de texto normal para otros casos -->
                                    <template v-else>
                                        <label :for="`edit_${column}`" class="block font-medium text-gray-700 mb-1">
                                            {{ column.charAt(0).toUpperCase() + column.slice(1) }}
                                        </label>
                                        <input :name="column" v-model="editFormData[column]" :id="`edit_${column}`"
                                            type="text"
                                            class="w-full border border-main-color px-4 py-2 rounded-md focus:ring-2 focus:ring-opacity-50 focus:ring-main-color focus:border-main-color">
                                    </template>
                                </div>
                            </div>

                            <!-- Footer con botones -->
                            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                                <button type="button" @click="closeEditModal"
                                    class="btn-secondary px-4 py-2 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancelar
                                </button>
                                <button type="submit" class="btn-primary px-4 py-2 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </Transition>
            </div>
        </Transition>

        <!-- Componente Modal de Eliminación -->
        <DeleteModal v-if="showDeleteModal" :show="showDeleteModal" @close="closeDeleteModal"
            @confirm="submitDeleteForm" title="¿Estás seguro de que querés eliminar este elemento?"
            message="Esta acción no se puede deshacer." />
    </div>
</template>

<style scoped>
/* Estilos para la tabla grid */
.grid-table-container {
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}

/* Estilos para el encabezado */
.grid-header {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    background-color: rgb(229, 231, 235);
    /* bg-gray-200 */
    border-bottom: 1px solid #e2e8f0;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.75rem;
    color: var(--main-color, #333);
    letter-spacing: 0.05em;
}

.grid-header-cell {
    padding: 1rem 1.5rem;
    text-align: center;
}

/* Estilos para el cuerpo de la tabla */
.grid-body {
    background-color: rgb(243, 244, 246);
    /* bg-gray-100 */
}

/* Mensaje cuando no hay datos */
.grid-no-data {
    padding: 1rem;
    text-align: center;
    grid-column: 1 / -1;
    color: rgb(55, 65, 81);
    /* text-gray-700 */
    font-size: 0.875rem;
}

/* Filas de datos */
.grid-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    align-items: center;
    transition: background-color 0.2s;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.grid-row:hover {
    background-color: rgb(229, 231, 235);
    /* hover:bg-gray-200 */
}

.grid-row:last-child {
    border-bottom: none;
}

/* Celdas */
.grid-cell {
    padding: 1rem 1.5rem;
    text-align: center;
    font-size: 0.875rem;
    color: rgb(55, 65, 81);
    /* text-gray-700 */
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Celda de imagen */
.grid-image-cell {
    height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.grid-image {
    max-height: 80px;
    max-width: 100%;
    object-fit: contain;
}

.grid-no-image {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: rgb(156, 163, 175);
    /* text-gray-400 */
}

/* Celda de acciones */
.grid-actions-cell {
    padding: 0.5rem;
}

.grid-actions {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.action-button {
    padding: 0.5rem;
    border-radius: 9999px;
    transition: all 0.2s;
    cursor: pointer;
    background-color: transparent;
    border: none;
}

.edit-button:hover {
    background-color: rgba(248, 105, 3, 0.1);
    /* hover:bg-orange-100 */
}

.delete-button {
    color: rgb(220, 38, 38);
    /* text-red-600 */
}

.delete-button:hover {
    background-color: rgba(254, 226, 226, 1);
    /* hover:bg-red-100 */
}

/* Ajuste para pantallas pequeñas */
@media (max-width: 768px) {
    .grid-header {
        display: none;
    }

    .grid-row {
        display: grid;
        grid-template-columns: 1fr;
        padding: 1rem 0;
        gap: 0.5rem;
        position: relative;
    }

    .grid-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .grid-cell:before {
        content: attr(data-label);
        font-weight: 600;
        margin-right: 0.5rem;
    }

    .grid-actions-cell {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding-top: 0.75rem;
    }
}

/* Animaciones para modales */
.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>