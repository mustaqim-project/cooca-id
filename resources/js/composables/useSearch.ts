<script setup lang="ts">
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

interface UseSearchOptions {
  initialQuery?: string;
  debounceMs?: number;
  minLength?: number;
}

export function useSearch({ initialQuery = '', debounceMs = 300, minLength = 2 }: UseSearchOptions = {}) {
  const query = ref(initialQuery);
  const isSearching = ref(false);
  const hasSearched = ref(false);

  const emitSearch = defineEmits<{
    search: [query: string];
  }>();

  const performSearch = (searchQuery: string) => {
    if (searchQuery.length < minLength) {
      return;
    }
    
    isSearching.value = true;
    hasSearched.value = true;
    emitSearch('search', searchQuery);
    
    // Reset searching state after a delay (should be handled by actual API call)
    setTimeout(() => {
      isSearching.value = false;
    }, 500);
  };

  const debouncedSearch = debounce(performSearch, debounceMs);

  watch(query, (newValue) => {
    if (newValue.length >= minLength || newValue === '') {
      debouncedSearch(newValue);
    } else {
      isSearching.value = false;
    }
  });

  const clearSearch = () => {
    query.value = '';
    isSearching.value = false;
    hasSearched.value = false;
    emitSearch('search', '');
  };

  const isValid = computed(() => query.value.length >= minLength);

  return {
    query,
    isSearching,
    hasSearched,
    isValid,
    clearSearch,
    performSearch,
  };
}
</script>
