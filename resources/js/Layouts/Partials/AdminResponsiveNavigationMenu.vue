<script setup>

import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

defineProps({
    showingNavigationDropdown: {
        type: Boolean,
    },

});


</script>

<template>
    <!-- Responsive Navigation Menu -->
    <div
        :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
        class="sm:hidden"
    >
        <div class="space-y-1 pb-3 pt-2">
            <ResponsiveNavLink
                :href="route('dashboard')"
                :active="route().current('dashboard')"
            >
                Dashboard
            </ResponsiveNavLink>
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-gray-200 pb-1 pt-4">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800">
                    {{ $page.props.auth.user.name }}
                </div>
                <div class="text-sm font-medium text-gray-500">
                    {{ $page.props.auth.user.email }}
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <ResponsiveNavLink :href="route('account.profile.settings')">
                    Profile
                </ResponsiveNavLink>
                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                    Log Out
                </ResponsiveNavLink>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
