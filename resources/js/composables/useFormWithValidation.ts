<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface UseFormOptions<T> {
  initialValues: T;
  remember?: boolean;
}

export function useFormWithValidation<T extends Record<string, any>>({
  initialValues,
  remember = false,
}: UseFormOptions<T>) {
  const form = useForm(initialValues);

  const getError = (field: keyof T) => {
    return form.errors[field as string];
  };

  const hasError = (field: keyof T) => {
    return !!form.errors[field as string];
  };

  const isInvalid = (field: keyof T) => {
    return hasError(field) && form.recentlySuccessful === false;
  };

  const resetField = (field: keyof T) => {
    if (form.errors[field as string]) {
      const newErrors = { ...form.errors };
      delete newErrors[field as string];
      form.errors = newErrors;
    }
  };

  const clearErrors = () => {
    form.clearErrors();
  };

  const submit = async (method: 'post' | 'put' | 'patch', url: string, options?: any) => {
    return new Promise((resolve, reject) => {
      form.transform((data) => data).submit(method, url, {
        ...options,
        onSuccess: (page) => {
          resolve(page);
        },
        onError: (errors) => {
          reject(errors);
        },
      });
    });
  };

  return {
    form,
    getError,
    hasError,
    isInvalid,
    resetField,
    clearErrors,
    submit,
  };
}
</script>
