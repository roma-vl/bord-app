
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

const clearFiles = () => {
    files.value = {};
};

const submitFiles = async () => {
    const formData = new FormData();
    for (const fileObj of Object.values(files.value)) {
        formData.append("files[]", fileObj.file);
    }

    console.log(files, 'formData')
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
        <main class="container mx-auto max-w-screen-lg h-full">
            <article
                aria-label="File Upload Modal"
                class="relative h-full flex flex-col bg-white  rounded-md"
                @drop.prevent="dropHandler"
                @dragover.prevent="dragOverHandler"
                @dragleave="dragLeaveHandler"
                @dragenter="dragEnterHandler"
            >
<!--                shadow-xl-->
                <div
                    id="overlay"
                    :class="{'draggedover': isDraggedOver}"
                    class="w-full h-full absolute top-0 left-0 pointer-events-none z-50 flex flex-col items-center justify-center rounded-md"
                >
                    <i>
                        <svg class="fill-current w-12 h-12 mb-3 text-blue-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M19.479 10.092c-.212-3.951-3.473-7.092-7.479-7.092-4.005 0-7.267 3.141-7.479 7.092-2.57.463-4.521 2.706-4.521 5.408 0 3.037 2.463 5.5 5.5 5.5h13c3.037 0 5.5-2.463 5.5-5.5 0-2.702-1.951-4.945-4.521-5.408zm-7.479-1.092l4 4h-3v4h-2v-4h-3l4-4z" />
                        </svg>
                    </i>
                    <p class="text-lg text-blue-700">Drop files to upload</p>
                </div>

                <section class="h-full overflow-auto p-8 w-full flex flex-col">
                    <header class="border-dashed border-2 border-gray-400 py-12 flex flex-col justify-center items-center">
                        <p class="mb-3 font-semibold text-gray-900 flex flex-wrap justify-center">
                            <span>Drag and drop your</span>&nbsp;<span>files anywhere or</span>
                        </p>
                        <input ref="fileInput" type="file" multiple class="hidden" @change="handleFileInput" />
                        <button
                            class="mt-2 rounded-sm px-3 py-1 bg-gray-200 hover:bg-gray-300 focus:shadow-outline focus:outline-none"
                            @click="triggerFileInput"
                        >
                            Upload a file
                        </button>
                    </header>

                    <h1 class="pt-8 pb-3 font-semibold sm:text-lg text-gray-900">To Upload</h1>

                    <ul id="gallery" class="flex flex-column flex-wrap -m-1">
                        <li v-if="!Object.keys(files).length" id="empty" class="h-full w-full text-center flex flex-col items-center justify-center">
                            <img class="mx-auto w-32" src="https://user-images.githubusercontent.com/507615/54591670-ac0a0180-4a65-11e9-846c-e55ffce0fe7b.png" alt="no data" />
                            <span class="text-small text-gray-500">No files selected</span>
                        </li>
                        <li v-for="(file, url) in files" :key="url" class="block p-1 w-1/2 sm:w-1/3 md:w-1/4 lg:w-1/4 xl:w-1/8 h-36">
                            <article class="group w-full h-full rounded-md focus:outline-none focus:shadow-outline bg-gray-100 cursor-pointer relative shadow-sm">
                                <img v-if="file.isImage" :src="url" :alt="file.name" class="img-preview w-full h-full sticky object-cover rounded-md bg-fixed" />

                                <section class="flex flex-col rounded-md text-xs break-words w-full h-full z-20 absolute top-0 py-2 px-3">
                                    <h1 class="flex-1 group-hover:text-blue-800">{{ file.name }}</h1>
                                    <div class="flex">
                                        <p class="p-1 size text-xs text-gray-700">{{ formatFileSize(file.size) }}</p>
                                        <button
                                            class="delete ml-auto focus:outline-none hover:bg-gray-300 p-1 rounded-md text-gray-800"
                                            @click="removeFile(url)"
                                        >
                                            <svg class="pointer-events-none fill-current w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                <path class="pointer-events-none" d="M3 6l3 18h12l3-18h-18zm19-4v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.316c0 .901.73 2 1.631 2h5.711z" />
                                            </svg>
                                        </button>
                                    </div>
                                </section>
                            </article>
                        </li>
                    </ul>
                </section>
<!--                <footer class="flex justify-end px-8 pb-8 pt-4">-->
<!--                    <button-->
<!--                        class="rounded-sm px-3 py-1 bg-blue-700 hover:bg-blue-500 text-white focus:shadow-outline focus:outline-none"-->
<!--                        @click="submitFiles"-->
<!--                    >-->
<!--                        Upload now-->
<!--                    </button>-->
<!--                    <button-->
<!--                        class="ml-3 rounded-sm px-3 py-1 hover:bg-gray-300 focus:shadow-outline focus:outline-none"-->
<!--                        @click="clearFiles"-->
<!--                    >-->
<!--                        Cancel-->
<!--                    </button>-->
<!--                </footer>-->
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

#overlay.draggedover {
    background-color: rgba(255, 255, 255, 0.7);
}
#overlay.draggedover p,
#overlay.draggedover i {
    opacity: 1;
}

.group:hover .group-hover\:text-blue-800 {
    color: #2b6cb0;
}
</style>
