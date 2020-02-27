export const modal = {
    methods: {
        showModal: (modalName) => {
            const currentModal = document.querySelector(`.${modalName}`);
            const overlay = document.querySelector('.overlay');
            if (currentModal) {
                currentModal.classList.add('modal--show');
                overlay.classList.add('overlay--show');
            }
        },
        closeModal: () => {
            const overlay = document.querySelector('.overlay');
            const modals = document.querySelectorAll('.modal-window');
            modals.forEach(modal => {
                modal.classList.remove('modal--show');
                overlay.classList.remove('overlay--show');
            });
        },
    }
}