<script>
export default {
    name: "Breadcrumb",
    props: {
        breadcrumbs: {
            type: Array,
            required: true,
            default: () => [],
        },
    },
    methods: {
        navigateTo(url) {
            if (url) {
                window.location.href = url;
            }
        },
    },
};
</script>
<template>
    <div class="flex items-center pb-2 pt-0">
        <nav class="text-sm sm:text-base bg-white p-2 rounded-md shadow">
            <ol class="list-none p-0 inline-flex space-x-2">
                <li v-for="(breadcrumb, index) in breadcrumbs" :key="index" class="flex items-center">
                    <svg
                        v-if="breadcrumb.icon"
                        @click="navigateTo(breadcrumb.url)"
                        xmlns="http://www.w3.org/2000/svg"
                        height="1em"
                        viewBox="0 0 576 512"
                        :class="['cursor-pointer', breadcrumb.url ? 'hover:fill-blue-500' : '', 'transition-colors', 'duration-300']"
                        fill="#4b5563"
                    >
                        <path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/>
                    </svg>

                    <template v-if="breadcrumb.url">
                        <a
                            :href="breadcrumb.url"
                            class="text-gray-600 hover:text-blue-500 transition-colors duration-300"
                            :class="breadcrumb.current ? 'font-bold text-gray-800' : ''"
                        >
                            {{ breadcrumb.title }}
                        </a>
                    </template>
                    <template v-else>
                        <span
                            class="text-gray-600"
                            :class="breadcrumb.current ? 'font-bold text-gray-800' : ''"
                        >
                          {{ breadcrumb.title }}
                        </span>
                    </template>

                    <span v-if="index < breadcrumbs.length - 1" class="mx-2">/</span>
                </li>
            </ol>
        </nav>
    </div>
</template>

<style scoped>
/* Додаткові стилі за потреби */
</style>
