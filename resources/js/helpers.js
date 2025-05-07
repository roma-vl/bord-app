import {ref, watch} from "vue";

export const useLocalStorageFn = (key, defaultValue) => {
    let stored = localStorage.getItem(key);
    let parsed;
    try {
        parsed = stored ? JSON.parse(stored) : defaultValue;
    } catch (error) {
        parsed = defaultValue;
    }
    if (Array.isArray(defaultValue) && !Array.isArray(parsed)) {
        parsed = defaultValue;
    }
    const value = ref(parsed);
    watch(value, (newVal) => {
        localStorage.setItem(key, JSON.stringify(newVal));
    }, { deep: true });
    return value;
};

export const getFullPathForImage = (path) => {
    if (path === '' || path === undefined) {
        return import.meta.env.VITE_APP_STORAGE_URL + "images/adverts/info/empty.jpg"
    }
    return import.meta.env.VITE_APP_STORAGE_URL + "/" + path;
}


export function getDateFormatFromLocale(date) {
    const parsedDate = new Date(date);
    if (isNaN(parsedDate)) return "-";

    return parsedDate.toLocaleDateString("uk-UA", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit"
    });
}

