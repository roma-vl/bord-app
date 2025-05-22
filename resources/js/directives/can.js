import { usePage } from '@inertiajs/vue3';

export default {
  mounted(el, binding) {
    const permissions = usePage().props.auth?.permissions || [];
    const value = binding.value;

    const isAllowed = Array.isArray(value)
      ? value.some((permission) => permissions.includes(permission))
      : permissions.includes(value);

    if (!isAllowed) {
      el.remove();
    }
  },
};
