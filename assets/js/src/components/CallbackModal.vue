<template>
    <div class="modal-callback">
        <div class="modal-callback-container">
            <div class="modal-callback__title">Обратная связь</div>
            <form @submit.prevent="submitCallbackForm">
            
                <div class="form-group-float-placeholder form-group" :class="{ 'input--error': $v.name.$error }"> 
                    <input class="modal-login__input form-control" id="callback_name" v-model.trim="$v.name.$model" type="text" name="name" placeholder="Имя">
                    <div class="errors-form">
                        <div class="error" v-if="!$v.name.required">Имя обязательно</div>
                    </div>
                    <label class="form-control-placeholder" for="callback_name">Имя</label>
                </div>

                <div class="form-group-float-placeholder form-group" :class="{ 'input--error': focusPhone && $v.phone.$error }"> 
                    <masked-input id="callback_phone" class="modal-login__input form-control" type="phone" name="phone" v-model.trim="$v.phone.$model" placeholder="Номер" mask="\+\7 (111) 111-11-11" @focus.native="focusPhone = true"></masked-input>
                    <div class="errors-form">
                        <div class="error" v-if="focusPhone && !$v.phone.required">Телефон обязателен</div>
                        <div class="error" v-if="focusPhone && !$v.phone.correctPhone">Должен быть действительный телефон</div>
                    </div>
                    <label class="form-control-placeholder" for="callback_phone">Номер</label>
                </div>

      
                <button class="button state-button"
                :class="{ 
                    'state-button--pending': submitStatus == 'PENDING', 
                    'state-button--success': submitStatus == 'SUCCESS',
                    'state-button--fail': submitStatus == 'ERROR',
				}"
                ><span class="state-button__text">Отправить</span></button>
            </form>
        </div>
    </div>
</template>

<script>
import { modal } from "./mixins/modal";
import { required,  email, helpers, sameAs, minLength } from 'vuelidate/lib/validators';
import MaskedInput from 'vue-masked-input';
export default {
    mixins: [modal],
    components: {
        'masked-input': MaskedInput,
    },
    data() {
        return {
            site_url: SITEDATA.url,
            template_url: SITEDATA.themepath,
            name: '',
            phone: '',
            security: SITEDATA.security,
            errors: '',
            submitStatus: '',
            focusPhone: false,
            submitted: false
        };
    },
    validations: {
        phone: {
            required,
            correctPhone: (phone) => { 
                return phone.replace(/[^\d\.]/g, '').length == 11
            }
        },  
        name: {
            required,
        },      
    },
    methods: {
        async submitCallbackForm() {
            this.submitted = true;
            this.focusPhone = true;
            this.$v.$touch()
            let formCallback = new FormData(); 
            formCallback.append("name", this.name);
            formCallback.append("phone", this.phone);
            formCallback.append("security", this.security);
            let fetchData = {
                method: "POST",
                body: formCallback
            };

            if (this.$v.$invalid) {
                this.submitStatus = 'ERROR';
                setTimeout(() => {this.submitStatus = ''}, 1000);
            } else {
                this.submitStatus = 'PENDING'
                const sendURL = `${SITEDATA.themepath}/email-send.php`;
                let response = await fetch(sendURL, fetchData);
                let responseData = await response.json();
                if(responseData.status == 'success'){
                    this.submitStatus = 'SUCCESS';
                    this.clearForm();
                    setTimeout(() => {this.submitStatus = ''}, 1000);
                    setTimeout(() => {this.closeModal()}, 1300);
                    setTimeout(() => {this.showModal("modal-window--thank")}, 1500);
                }
                else{
                    this.submitStatus = 'ERROR';
                    setTimeout(() => {this.submitStatus = ''}, 1000);
                }
            }
        },
        clearForm(){
            this.name = "";
            this.phone = "";
        }
    }
};
</script>