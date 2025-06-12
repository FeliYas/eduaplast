<script setup>
import { ref, defineProps, inject } from 'vue';
import { useForm } from '@inertiajs/vue3';
import QuillEditor from '@/components/QuillEditor.vue'; 

const notification = inject('noti');

const props = defineProps({
    imagen: {
        type: String,
        required: true
    },
    titulo: {
        type: String,
        required: true
    },
    descripcion: {
        type: String,
        required: true
    },
    id: {
        type: Number,
        required: true
    },
    num: {
        type: Number,
        required: true
    }
});

const isEditing = ref(false);

const form = useForm({
    titulo: props.titulo,
    descripcion: props.descripcion
});

const toggleEdit = () => {
    isEditing.value = !isEditing.value;
    if (isEditing.value) {
        // Reset form when opening editor
        form.titulo = props.titulo;
        form.descripcion = props.descripcion;
    }
};

const submit = () => {
    form.post(route('tarjetanos.update', { id: props.id, num: props.num }), {
        preserveScroll: true,
        onSuccess: (page) => {
            // Accede al mensaje flash de la respuesta
            if (page.props.flash && page.props.flash.message) {
                notification({ message: page.props.flash.message, type: "success" });
            } else {
                notification({ message: "Actualizado correctamente", type: "success" });
            }
        },
    });
};

// Obtener el título predeterminado según el número
const getDefaultTitle = () => {
    if (props.num === 1) return "Misión";
    if (props.num === 2) return "Visión";
    return "Valores";
};
</script>

<template>
    <div class="w-1/3 bg-white p-3 rounded-lg shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md hover:border-main-color transform hover:-translate-y-1">
        <!-- Usar transition para animar el cambio entre los modos -->
        <transition 
            name="fade-slide"
            mode="out-in"
        >
            <!-- Vista normal -->
            <div v-if="!isEditing" class="flex flex-col h-full items-center text-center py-4" key="view">
                <!-- Imagen -->
                <div class="mb-4 w-24 h-24 overflow-hidden flex items-center justify-center">
                    <img :src="imagen" :alt="`Imagen de ${titulo || getDefaultTitle()}`" class="object-cover rounded-full">
                </div>
                
                <!-- Título -->
                <h3 class="text-xl font-medium text-gray-800 mb-4">{{ titulo || getDefaultTitle() }}</h3>
                
                <!-- Descripción -->
                <div class="text-sm text-gray-600" v-html="descripcion"></div>
                
                <!-- Botón editar -->
                <div class="mt-6">
                    <button @click="toggleEdit" class="btn-primary flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Editar
                    </button>
                </div>
            </div>

            <!-- Modo edición -->
            <div v-else class="p-2" key="edit">
                <form @submit.prevent="submit" class="text-black">
                    <div class="mb-2">
                        <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                        <input 
                            type="text" 
                            id="titulo" 
                            v-model="form.titulo"
                            class="p-2 bg-white block border border-gray-300 w-full rounded-lg shadow-sm transition-all duration-200 focus:border-main-color focus:ring focus:ring-main-color focus:ring-opacity-20" 
                            required
                        >
                    </div>

                    <div class="mb-2">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <QuillEditor 
                            :unique_ref="`descripcion_editor_${num}`" 
                            placeholder="Descripción" 
                            :initial_content="form.descripcion"
                            v-on:text_changed="form.descripcion = $event"
                            class="custom-quill-height">
                        </QuillEditor>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button 
                            type="button" 
                            @click="toggleEdit"
                            class="btn-secondary flex items-center justify-center"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            class="btn-primary flex items-center justify-center"
                            :disabled="form.processing"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ form.processing ? 'Guardando...' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </transition>
    </div>
</template>

<style scoped>
/* Estilos para la transición */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(10px);
}

.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Estilo personalizado para sobrescribir la altura máxima del editor */
:deep(.custom-quill-height .text-editor) {
  max-height: 50px !important;
}
</style>