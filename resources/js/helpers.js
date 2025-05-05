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

export const getFullPathForImage = (path) =>
    import.meta.env.VITE_APP_STORAGE_URL + "/" + path;
