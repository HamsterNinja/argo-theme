<template>
    <form @submit.prevent="submitChangeForm">
        <label for="old_password" :class="{ 'input--error': submitted && $v.old_password.$error }">
            <p>Текущий пароль *</p>
            <input v-model="old_password" aria-label="Текущий пароль" type="password" id="old_password" required>
            <div class="error" v-if="submitted && !$v.old_password.required">Текущий пароль обязателен</div>
        </label>
        <label for="new_password" :class="{ 'input--error': submitted && $v.new_password.$error }">
            <p>Новый пароль *</p>
            <input v-model="new_password" aria-label="Новый пароль" type="password" id="new_password" required>
            <div class="error" v-if="submitted && !$v.new_password.required">Новый пароль обязателен</div>
        </label>
        <button class="acoount-submit">ПОДТВЕРДИТЬ</button>
    </form>
</template>

<script>
import { required, email, minLength } from "vuelidate/lib/validators";
export default {
    props: {
        user_id: {
            type: String,
        },
    },
    data() {
        return {
            submitted: false,
            submitStatus: '',
            template_url: SITEDATA.themepath,
            old_password: '',
            new_password: '',
            errors: ''
        };
    },
    validations: {
        old_password: {
            required
        },
        new_password: {
            required
        },
    },
    methods: {
        async submitChangeForm(){
            this.submitted = true;
            this.$v.$touch();
 
            let formReg = new FormData(); 
            formReg.append("user_id", this.user_id);
            formReg.append("old_password", this.old_password);
            formReg.append("new_password", this.new_password);

            let fetchData = {
                method: "POST",
                body: formReg
            };
            
            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR';
                setTimeout(() => {this.submitStatus = ''}, 1000);
            } else {
                this.submitStatus = 'PENDING'
                const sendURL = `${SITEDATA.url}/wp-json/amadreh/v1/change-password`;
                let response = await fetch(sendURL, fetchData);
                let responseData = await response.json();
                if(responseData.data.status == 'success'){
                    this.submitStatus = 'SUCCESS';
                    this.clearForm();
                    setTimeout(() => {this.submitStatus = ''}, 1000);
                    setTimeout(() => {this.showModal('modal-window--changed')}, 1500);
                }
                else{
                    this.submitStatus = 'ERROR';
                    setTimeout(() => {this.submitStatus = ''}, 1000);
                }
            }
        },
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
        clearForm(){
            this.submitted =  false;
            this.old_password =  "";
            this.new_password =  "";
        },
    }
};
</script>