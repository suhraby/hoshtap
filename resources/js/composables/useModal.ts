import { ref } from 'vue';

export function useModal(initialState: boolean = false) {
    const isOpen = ref(initialState);

    function openModal(): void {
        isOpen.value = true;
    }

    function closeModal(): void {
        isOpen.value = false;
    }

    function toggleModal(): void {
        isOpen.value = !isOpen.value;
    }

    return { isOpen, openModal, closeModal, toggleModal };
}
