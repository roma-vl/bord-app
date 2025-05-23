<script setup>
import { ref, watch } from 'vue';
import { getFullPathForImage } from '@/helpers.js';

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
});
const emit = defineEmits(['update:modelValue']);

const files = ref({});
const isDraggedOver = ref(false);
const fileInput = ref(null);

const triggerFileInput = () => fileInput.value.click();

const handleFileInput = (event) => {
  Array.from(event.target.files).forEach((file) => {
    const url = URL.createObjectURL(file);
    files.value[url] = {
      type: 'new',
      file,
      name: file.name,
      size: file.size,
      isImage: file.type.startsWith('image/'),
      previewUrl: url,
    };
  });
  updateModelValue();
};

const removeFile = (key) => {
  delete files.value[key];
  updateModelValue();
};

const dropHandler = (event) => {
  event.preventDefault();
  Array.from(event.dataTransfer.files).forEach((file) => {
    const url = URL.createObjectURL(file);
    files.value[url] = {
      type: 'new',
      file,
      name: file.name,
      size: file.size,
      isImage: file.type.startsWith('image/'),
      previewUrl: url,
    };
  });
  isDraggedOver.value = false;
  updateModelValue();
};

const dragOverHandler = (event) => event.preventDefault();
const dragEnterHandler = () => (isDraggedOver.value = true);
const dragLeaveHandler = () => (isDraggedOver.value = false);

const updateModelValue = () => {
  const list = Object.values(files.value).map((f) => {
    if (f.type === 'existing') {
      return { type: 'existing', file: f.file, id: f.id };
    } else {
      return f.file;
    }
  });
  emit('update:modelValue', list);
};

const formatFileSize = (size) => {
  if (size > 1048576) return `${(size / 1048576).toFixed(1)} MB`;
  if (size > 1024) return `${(size / 1024).toFixed(1)} KB`;
  return `${size} B`;
};

watch(
  () => props.modelValue,
  (newVal) => {
    const newFiles = {};
    newVal.forEach((item) => {
      if (item instanceof File) {
        const url = URL.createObjectURL(item);
        newFiles[url] = {
          type: 'new',
          file: item,
          name: item.name,
          size: item.size,
          isImage: item.type.startsWith('image/'),
          previewUrl: url,
        };
      } else if (item.type === 'existing') {
        const url = 'existing-' + item.id;
        newFiles[url] = {
          type: 'existing',
          file: item.file,
          id: item.id,
          name: item.file,
          size: null,
          isImage: true,
          previewUrl: getFullPathForImage(item.file),
        };
      }
    });
    files.value = newFiles;
  },
  { immediate: true }
);
</script>

<template>
  <div
    class="border-2 border-dashed p-4 rounded-md"
    :class="{ 'border-blue-500': isDraggedOver }"
    @dragover="dragOverHandler"
    @dragenter="dragEnterHandler"
    @dragleave="dragLeaveHandler"
    @drop="dropHandler"
  >
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div
        v-for="(file, key) in files"
        :key="key"
        class="relative border rounded overflow-hidden"
      >
        <img
          v-if="file.isImage"
          :src="file.previewUrl"
          alt="Image"
          class="w-full h-32 object-cover"
        >
        <div
          v-else
          class="p-2 text-sm"
        >
          {{ file.name }}
        </div>
        <div class="absolute top-1 right-1">
          <button
            type="button"
            class="bg-red-600 text-white rounded-full px-2 py-1 text-xs"
            @click="removeFile(key)"
          >
            ✕
          </button>
        </div>
        <div
          v-if="file.size"
          class="text-xs p-1"
        >
          {{ formatFileSize(file.size) }}
        </div>
      </div>
    </div>

    <div
      class="text-center cursor-pointer mt-4 mb-6"
      @click="triggerFileInput"
    >
      <p>
        Перетягніть файли сюди або <span class="text-blue-600 underline">натисніть для вибору</span>
      </p>
      <input
        ref="fileInput"
        type="file"
        class="hidden"
        multiple
        @change="handleFileInput"
      >
    </div>
  </div>
</template>

<style scoped>
.border-blue-500 {
  border-color: #3b82f6;
}
</style>
