<template>
<div class="registration-form" @submit.prevent="submitRegistrationForm('')">
    <form action="">
        <div class="registration-form-item">
            <div class="registration-form-name">Основные данные</div>
            <label for="" class="form-group">
                <p>Имя</p>
                <input type="text" v-model="registration.first_name">
            </label>
            <label for="" class="form-group" :class="{ 'input--error': $v.registration.phone.$error }">
                <p>Телефон *</p>
                <masked-input type="phone" v-model.trim="$v.registration.phone.$model" mask="\+\7 (111) 111-11-11"/>
                <div class="errors-form">
                    <div class="error" v-if="!$v.registration.phone.required">Телефон обязателен</div>
                    <div class="error" v-if="!$v.registration.phone.correctPhone">Должен быть действительный телефон</div>
                </div>
            </label>
            <label for="" class="form-group" :class="{ 'input--error': $v.registration.email.$error }">
                <p>E-mail *</p>
                <input type="text" v-model="$v.registration.email.$model">
                <div class="errors-form">
                    <div class="error" v-if="!$v.registration.email.required">Почта обязательна</div>
                    <div class="error" v-if="!$v.registration.email.email">Должен быть действительный адрес электронной почты</div>
                    <div class="error" v-for="error in errors">
                        <span v-if="error == 'Email уже используется'">Email уже используется</span>
                    </div>
                </div>
            </label>
        </div>
        <div class="registration-form-item">
            <div class="registration-form-name">Ваш пароль</div>
            <label for="" class="form-group" :class="{ 'input--error': $v.registration.password.$error }">
                <p>Пароль *</p>
                <input type="text" v-model="$v.registration.password.$model">
                <div class="errors-form">
                    <div class="error" v-if="!$v.registration.password.required">Пароль обязателен</div>
                    <div class="error" v-if="!$v.registration.password.minLength">Должно быть не менее 8 символов</div>
                </div>
            </label>
            <label for="" class="form-group" :class="{ 'input--error': $v.registration.confirm_password.$error }">
                <p>Подтвердите пароль *</p>
                <input type="text" v-model="$v.registration.confirm_password.$model">
                <div class="errors-form">
                    <div class="error" v-if="!$v.registration.confirm_password.required">Потверждение обязательно</div>
                    <div class="error" v-if="!$v.registration.confirm_password.sameAs">Должен совпадать с паролем</div>
                </div>
            </label>
        </div>
        <div class="account-right-form notice">
            <label class="form-group control control-checkbox">
                SMS уведомление
                <input id="notice" type="checkbox" value="sms" v-model="registration.notice">
                <div class="control_indicator"></div>
            </label>
            <label class="form-group control control-checkbox">
                E-mail уведомление
                <input id="notice" type="checkbox" value="email" v-model="registration.notice">
                <div class="control_indicator"></div>
            </label>
            <button class="acoount-submit">ЗАРЕГИСТРИРОВАТЬСЯ</button>
        </div>
    </form>
</div>
</template>

<script>
import { required,  email, helpers, sameAs, minLength } from 'vuelidate/lib/validators';
import MaskedInput from 'vue-masked-input';
const alpha = helpers.regex('alpha', /[\u0000-~Ѐ-Ӿ]/);

export default {
    components: {
        'masked-input': MaskedInput,
    },
    data: () => ({
        template_url: SITEDATA.themepath,
        errors: [],
        registration: {
            first_name: '',
            phone: '',
            email: '',
            password: '',
            confirm_password: '',
            notice: '',
        }
    }),
    validations: {
        registration: {
            phone: {
                required,
                correctPhone: (phone) => { 
                    return phone.replace(/[^\d\.]/g, '').length == 11
                }
            },
            password: {
                required,
                minLength: minLength(8)
            },
            confirm_password: {
                required,
                sameAs: sameAs('password')
            },
            email: {
                required,
                email
            },
        }
    },
    methods: {
        async submitRegistrationForm(button){
            this.errors = [];
            this.registration.submitted = true;
            this.$v.registration.$touch()
 
            let formReg = new FormData(); 
            formReg.append("first_name", this.registration.first_name);
            formReg.append("phone", this.registration.phone);
            formReg.append("email", this.registration.email);
            formReg.append("password", this.registration.password);
            formReg.append("confirm_password", this.registration.confirm_password);
            formReg.append("notice", this.registration.notice);

            let fetchData = {
                method: "POST",
                body: formReg,
            };
            
            if (this.$v.registration.$invalid) {
                this.registration[button] = 'ERROR';
                setTimeout(() => {this.registration[button] = ''}, 1000);
            } else {
                this.registration[button] = 'PENDING'
                const sendURL = `${SITEDATA.url}/wp-json/amadreh/v1/add-user`;
                let response = await fetch(sendURL, fetchData);
                let responseData = await response.json();
                if(responseData.success == true){
                    this.registration[button] = 'SUCCESS';
                    setTimeout(() => {this.registration[button] = ''}, 1000);
                    // TODO: переход на главную
                    // setTimeout(() => {this.redirectPayment(methodPayment)}, 2000);
                }
                else{
                    this.registration[button] = 'ERROR';
                    this.errors.push(responseData.data);
                    setTimeout(() => {this.registration[button] = ''}, 1000);
                }
            }
        },
    }
}
</script>

