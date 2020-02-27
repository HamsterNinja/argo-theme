<template>
    <div class="registration-form">
        <form action="" @submit.prevent="submitLoginForm">
            <div class="registration-form-item">
                <label for="email" class="form-group" :class="{ 'input--error': login_failure }">
                    <p>E-Mail или номер телефона</p>
                    <input id="email" v-model="username" type="text" name="email" />
                    <div class="errors-form">
                        <div class="error" v-if="login_failure">Неверное имя пользователя или пароль</div>
                    </div>
                </label>
                <label for="password" class="form-group">
                    <p>Пароль</p>
                    <input id="password" v-model="password" type="password" name="password"/>
                </label>
                <a :href="template_url + '/my-account/lost-password/'" class="forgot-password">Забыли пароль?</a>
            </div>
            <div class="account-right-form notice">                                               
                <button class="acoount-submit">Войти</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            template_url: SITEDATA.themepath,
            username: '',
            password: '',
            security: SITEDATA.security,
            errors: ''
        };
    },
    computed: {
        login_failure(){
            return this.errors.includes('Неверное имя пользователя или пароль')
        }
    },
    methods: {
        async submitLoginForm() {
            let formLogin = new FormData(); 
            formLogin.append("action", "ajaxlogin");
            formLogin.append("username", this.username);
            formLogin.append("password", this.password);
            formLogin.append("security", this.security);
            let fetchData = {
                method: "POST",
                body: formLogin
            };

            let response = await fetch(SITEDATA.ajax_url, fetchData);
            let jsonResponse = await response.json();
            if (jsonResponse.loggedin == false) {
                this.errors = jsonResponse.message;
            }
            else{
                document.location.reload();
            }
        }
    }
};
</script>