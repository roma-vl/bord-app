<template>
    <div v-if="showBanner" class="fixed bottom-0 left-0 w-full bg-gray-600 z-50 p-5">
        <div class="md:flex items-center -mx-3">
            <div class="md:flex-1 px-3 mb-5 md:mb-0">
                <p class="text-center md:text-left text-white text-xs leading-tight md:pr-12">
                    Ми використовуємо cookie для кращого досвіду. Прийняти всі або налаштувати вручну.
                </p>
            </div>
            <div class="px-3 text-center">
                <button
                    class="py-2 px-6 bg-gray-800 hover:bg-gray-900 text-white rounded font-bold text-sm shadow-xl mr-3"
                    @click="openModal"
                >
                    Налаштування
                </button>
                <button
                    class="py-2 px-6 bg-green-500 hover:bg-green-600 text-white rounded font-bold text-sm shadow-xl"
                    @click="acceptAll"
                >
                    Прийняти
                </button>
            </div>
        </div>

        <!-- Cookie Settings Modal -->
        <dialog ref="modalRef" class="w-11/12 md:w-1/2 rounded-md bg-white shadow-lg p-0">
            <form @submit.prevent="submitSettings" class="flex flex-col w-full">
                <div class="flex justify-between items-center px-5 py-3 border-b">
                    <h2 class="text-lg font-bold">Налаштування cookie</h2>
                    <button @click="closeModal" class="text-gray-500 hover:text-black text-2xl">×</button>
                </div>

                <div class="divide-y px-5 py-3">
                    <label class="flex items-center py-3 cursor-not-allowed opacity-50">
                        <input type="checkbox" v-model="settings.necessary" class="sr-only" disabled />
                        <div
                            class="w-10 h-6 rounded-full relative transition duration-300"
                            :class="{
                                'bg-blue-500': settings.necessary,
                                'bg-gray-300': !settings.necessary
                            }">
                            <div
                                class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300"
                                :class="{ 'translate-x-4': settings.necessary }"></div>
                        </div>
                        <span class="ml-3">Обов’язкові cookie (завжди увімкнені)</span>
                    </label>

                    <label class="flex items-center py-3 cursor-pointer">
                        <input type="checkbox" v-model="settings.preferences" class="sr-only"/>
                        <div class="w-10 h-6  rounded-full relative transition duration-300"
                            :class="{ 'bg-blue-500': settings.preferences,
                                      'bg-gray-300': !settings.preferences  }">
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300"
                                :class="{ 'translate-x-4': settings.preferences }"></div>
                        </div>
                        <span class="ml-3">Налаштування користувача</span>
                    </label>
                    <label class="flex items-center py-3 cursor-pointer">
                        <input type="checkbox" v-model="settings.analytics" class="sr-only"/>
                        <div class="w-10 h-6  rounded-full relative transition duration-300"
                             :class="{ 'bg-blue-500': settings.analytics,
                                      'bg-gray-300': !settings.analytics  }">
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300"
                                 :class="{ 'translate-x-4': settings.analytics }"></div>
                        </div>
                        <span class="ml-3">Аналітика</span>
                    </label>
                    <label class="flex items-center py-3 cursor-pointer">
                        <input type="checkbox" v-model="settings.marketing" class="sr-only"/>
                        <div class="w-10 h-6  rounded-full relative transition duration-300"
                             :class="{ 'bg-blue-500': settings.marketing,
                                      'bg-gray-300': !settings.marketing  }">
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300"
                                 :class="{ 'translate-x-4': settings.marketing }"></div>
                        </div>
                        <span class="ml-3">Маркетинг</span>
                    </label>
                </div>


                <div class="flex justify-end px-5 py-3 border-t">
                    <button type="submit"
                        class="py-2 px-6 bg-gray-800 hover:bg-gray-900 text-white rounded font-bold text-sm shadow">
                        Зберегти
                    </button>
                </div>
            </form>
        </dialog>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const showBanner = ref(false)
const modalRef = ref(null)

const settings = ref({
    necessary: true, // завжди true
    preferences: false,
    analytics: false,
    marketing: false,
})

onMounted(() => {
    const accepted = localStorage.getItem('cookiesAccepted')
    if (!accepted) {
        showBanner.value = true
    }
    const stored = localStorage.getItem('cookieSettings')
    if (stored) {
        settings.value = { ...settings.value, ...JSON.parse(stored) }
    }
})

function openModal() {
    modalRef.value?.showModal()
}

function closeModal() {
    modalRef.value?.close()
}

function acceptAll() {
    settings.value.preferences = true
    settings.value.analytics = true
    settings.value.marketing = true
    submitSettings()
}

async function submitSettings() {
    localStorage.setItem('cookiesAccepted', 'true')
    localStorage.setItem('cookieSettings', JSON.stringify(settings.value))

    // 👇 Відправка POST-запиту на бекенд (заміни URL)
    try {
        await fetch('/api/cookie-preferences', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(settings.value),
        })
    } catch (error) {
        console.error('Помилка надсилання cookie налаштувань:', error)
    }

    closeModal()
    showBanner.value = false
}
</script>

<style scoped>
dialog::backdrop {
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(3px);
}
</style>
