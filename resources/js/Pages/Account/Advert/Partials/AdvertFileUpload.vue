
<script setup>
import { ref } from "vue";

const props = defineProps({
    uploadUrl: {
        type: String,
        required: true,
    },
});

const files = ref({});
const isDraggedOver = ref(false);
const fileInput = ref(null);

const triggerFileInput = () => fileInput.value.click();

const handleFileInput = (event) => {
    Array.from(event.target.files).forEach(addFile);
};

const addFile = (file) => {
    const objectURL = URL.createObjectURL(file);
    files.value[objectURL] = {
        name: file.name,
        size: file.size,
        isImage: file.type.startsWith("image/"),
    };
};

const removeFile = (url) => {
    delete files.value[url];
};

const dragEnterHandler = () => (isDraggedOver.value = true);

const dragLeaveHandler = () => (isDraggedOver.value = false);

const dragOverHandler = (event) => {
    event.preventDefault();
};

const dropHandler = (event) => {
    Array.from(event.dataTransfer.files).forEach(addFile);
    isDraggedOver.value = false;
};

const formatFileSize = (size) => {
    if (size > 1048576) return `${(size / 1048576).toFixed(1)} MB`;
    if (size > 1024) return `${(size / 1024).toFixed(1)} KB`;
    return `${size} B`;
};

</script>


<template>
        <main class="container mx-auto h-full">
            <article
                class="relative h-full flex flex-col bg-white  rounded-md"
                @drop.prevent="dropHandler"
                @dragover.prevent="dragOverHandler"
                @dragleave="dragLeaveHandler"
                @dragenter="dragEnterHandler"
            >


                <section class="h-full overflow-auto w-full flex flex-col">
                    <header class="border-dashed border-2 border-gray-400 p-3  justify-center items-center">
                        <ul id="gallery" class="flex flex-column flex-wrap -m-1">
                            <li v-for="(file, url) in files" :key="url" class="block p-1 w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/6 xl:w-1/8 h-32">
                                <article class="group w-full h-full rounded-md focus:outline-none focus:shadow-outline bg-gray-100 cursor-pointer relative shadow-sm">
                                    <img v-if="file.isImage" :src="url" :alt="file.name" class="img-preview w-full h-full sticky object-cover rounded-md bg-fixed" />
                                    <section class="flex flex-col rounded-md text-xs break-words w-full h-full z-20 absolute top-0 py-2 px-3">
                                        <div class="flex">
                                            <p class="p-1 size text-xs focus:outline-none bg-gray-100 rounded-md text-gray-800">{{ formatFileSize(file.size) }}</p>
                                            <button class="delete ml-auto focus:outline-none bg-gray-100 p-1 rounded-md text-gray-800" @click="removeFile(url)">
                                                <svg class="pointer-events-none fill-current w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                    <path class="pointer-events-none" d="M3 6l3 18h12l3-18h-18zm19-4v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.316c0 .901.73 2 1.631 2h5.711z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </section>
                                </article>
                            </li>
                        </ul>
                        <div class=" py-6 flex flex-col justify-center items-center">
                            <p class="mb-3 font-semibold text-gray-900">
                                <span>Drag and drop your</span>&nbsp;<span>files anywhere or</span>
                            </p>
                            <input ref="fileInput" type="file" multiple class="hidden" @change="handleFileInput" />
                            <button class="mt-2 rounded-sm px-3 py-1 bg-gray-200 hover:bg-gray-300 focus:shadow-outline focus:outline-none" @click="triggerFileInput">
                                Upload a file
                            </button>
                        </div>
                    </header>
                </section>
            </article>
        </main>
</template>

<style>
.hasImage:hover section {
    background-color: rgba(5, 5, 5, 0.4);
}
.hasImage:hover button:hover {
    background: rgba(5, 5, 5, 0.45);
}

#overlay p,
i {
    opacity: 0;
}
#overlay.draggedover p,
#overlay.draggedover i {
    opacity: 1;
}

.group:hover{
    color: #2b6cb0;
}
</style>
