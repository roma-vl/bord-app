<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

const form = useForm({
    phone: user.phone,
});

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Phone Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's phone information.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('account.profile.phone.update'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="phone" value="Phone" />
                    <TextInput
                        id="phone"
                        type="tel"
                        class="mt-1 block w-full"
                        v-model="form.phone"
                        autocomplete="phone"
                    />
                    <div v-if="Number(user.phone_verified) === 0" class="text-gray-400 flex justify-end"> Not confirmed</div>

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
