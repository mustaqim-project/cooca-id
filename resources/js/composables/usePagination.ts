<script setup lang="ts">
import { router } from '@inertiajs/vue3';

interface UsePaginationProps {
  initialPage?: number;
  initialPerPage?: number;
}

export interface PaginationLinks {
  first: string | null;
  last: string | null;
  prev: string | null;
  next: string | null;
}

export interface PaginatedData<T> {
  data: T[];
  current_page: number;
  from: number;
  last_page: number;
  per_page: number;
  to: number;
  total: number;
  links: PaginationLinks;
}

export function usePagination<T = any>({ initialPage = 1, initialPerPage = 15 }: UsePaginationProps = {}) {
  const currentPage = ref(initialPage);
  const perPage = ref(initialPerPage);
  const lastPage = ref(1);
  const total = ref(0);
  const data = ref<T[]>([]);

  const setPage = (page: number) => {
    if (page < 1 || page > lastPage.value) return;
    currentPage.value = page;
  };

  const setPerPage = (size: number) => {
    perPage.value = size;
    currentPage.value = 1;
  };

  const updateFromResponse = (response: PaginatedData<T>) => {
    data.value = response.data;
    currentPage.value = response.current_page;
    lastPage.value = response.last_page;
    perPage.value = response.per_page;
    total.value = response.total;
  };

  const goToPage = (page: number, url?: string) => {
    if (url) {
      router.get(url, {}, { preserveState: true, preserveScroll: true });
    } else {
      setPage(page);
    }
  };

  const nextPage = (url?: string) => {
    if (currentPage.value < lastPage.value) {
      goToPage(currentPage.value + 1, url);
    }
  };

  const prevPage = (url?: string) => {
    if (currentPage.value > 1) {
      goToPage(currentPage.value - 1, url);
    }
  };

  const hasPrev = computed(() => currentPage.value > 1);
  const hasNext = computed(() => currentPage.value < lastPage.value);

  const pages = computed(() => {
    const range = 2;
    const start = Math.max(1, currentPage.value - range);
    const end = Math.min(lastPage.value, currentPage.value + range);
    const result: (number | string)[] = [];

    for (let i = start; i <= end; i++) {
      result.push(i);
    }

    if (start > 1) {
      result.unshift('...');
      if (start > 2) {
        result.unshift(1);
      }
    }

    if (end < lastPage.value) {
      result.push('...');
      if (end < lastPage.value - 1) {
        result.push(lastPage.value);
      }
    }

    return result;
  });

  return {
    currentPage,
    perPage,
    lastPage,
    total,
    data,
    setPage,
    setPerPage,
    updateFromResponse,
    goToPage,
    nextPage,
    prevPage,
    hasPrev,
    hasNext,
    pages,
  };
}
</script>
