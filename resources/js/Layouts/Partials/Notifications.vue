<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import NotificationIcon from '@/Components/Icon/NotificationIcon.vue';
</script>

<template>
  <Dropdown align="right" width="72">
    <template #trigger>
      <div class="flex flex-col items-center">
        <div class="relative flex justify-center">
          <div>
            <button
              class="inline-grid place-items-center align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[32px] min-h-[32px] rounded-md hover:shadow-sm text-stone-800 hover:bg-stone-200 px-4 py-3 dark:bg-gray-700 dark:text-white"
            >
              <NotificationIcon />
            </button>
          </div>
          <span
            class="absolute -top-2 -right-2 px-1.5 py-0.5 text-xs border leading-none grid place-items-center rounded-full min-w-[18px] min-h-[18px] bg-red-500 text-stone-50 border-white"
          >
            5
          </span>
        </div>
      </div>
    </template>

    <template #content>
      <ul
        class="dark:bg-gray-700 dark:text-white flex-col gap-2 overflow-auto bg-white p-3 font-sans text-sm font-normal text-blue-gray-500 shadow-blue-gray-500/10 focus:outline-none"
      >
        <div
          role="menuitem"
          class="flex w-full cursor-pointer select-none items-center gap-4 rounded-md px-3 py-2 pr-8 pl-2 text-start leading-tight outline-none transition-all hover:bg-gray-500/10 hover:border-gray-300 active:bg-gray-300 active:border-gray-800 dark:text-white"
        >
          <img
            alt="tania andrew"
            src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1480&amp;q=80"
            class="relative inline-block h-12 w-12 !rounded-full object-cover object-center"
          />
          <div class="flex flex-col gap-1">
            <p
              class="block font-sans text-sm font-normal leading-normal text-gray-700 antialiased dark:text-white"
            >
              <span class="font-medium text-blue-gray-900">Tania</span> send you a message
            </p>
            <p
              class="flex items-center gap-1 font-sans text-xs font-light text-gray-600 antialiased dark:text-gray-300"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
                class="h-3 w-3"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
              </svg>
              13 minutes ago
            </p>
          </div>
        </div>
        <div
          class="flex w-full cursor-pointer select-none items-center gap-4 rounded-md px-3 py-2 pr-8 pl-2 text-start leading-tight outline-none transition-all hover:bg-gray-500/10 hover:border-gray-300 active:bg-gray-300 active:border-gray-800"
        >
          <img
            alt="natali craig"
            src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1061&amp;q=80"
            class="relative inline-block h-12 w-12 !rounded-full object-cover object-center"
          />
          <div class="flex flex-col gap-1">
            <p
              class="block font-sans text-sm font-normal leading-normal text-gray-700 antialiased dark:text-white"
            >
              <span class="font-medium text-blue-gray-900">Natali</span> reply to your email
            </p>
            <p
              class="flex items-center gap-1 font-sans text-xs font-light text-gray-600 antialiased dark:text-gray-300"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
                class="h-3 w-3"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
              </svg>
              a hour ago
            </p>
          </div>
        </div>
      </ul>
    </template>
  </Dropdown>
</template>

<style scoped></style>
