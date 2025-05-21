<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3'
import FlashMessage from "@/Components/FlashMessage.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {computed} from "vue";

const props = defineProps({
    settings: Object,
    group: String
})
console.log(props.settings, 'props.settings')
console.log(props.group, 'props.group')

const form = useForm({ ...props.settings })
const flash = computed(() => usePage().props.flash);
function submit() {
    form.put(route('admin.settings.' + props.group, props.group))
}

</script>

<template>
    <Head title="Roles" />
    <AdminLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
                    <FlashMessage :flash="flash" />
                    <div class=" px-4">
                        <div class="grid grid-cols-2 gap-4 items-start mb-3">
                            <h2 class="text-xl font-bold mb-4">Global Settings</h2>
                        </div>
                    </div>
                    <div>
                        <form @submit.prevent="submit">
                            <div v-if="props.group === 'general' ">
                                <label> site_name </label>
                                <input v-model="form.site_name" placeholder="Role name" required class="border p-2 w-full my-2" />

                                <label> maintenance_mode </label>
                                <input type="checkbox" v-model="form.maintenance_mode" class="p-3 my-2" />
                            </div>
                            <div v-else-if="props.group === 'user' "></div>
                                <label> allow_registration </label>
                                <input type="checkbox" v-model="form.allow_registration" class="p-3 my-2" />

                                <label> require_email_verification </label>
                                <input type="checkbox" v-model="form.require_email_verification" class="p-3 my-2" />
                            <hr>
                            <button type="submit" class="bg-blue-500 px-4 py-2 text-white rounded">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
