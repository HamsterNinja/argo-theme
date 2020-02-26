<template>
    <div class="registration-form">
        <form action="" @submit.prevent="submitLoginForm">
            <div class="registration-form-item">
                <label for="">
                    <p>E-Mail или номер телефона</p>
                    <input v-model="username" type="text" name="email" />
                </label>
                <label for="">
                    <p>Пароль</p>
                    <input v-model="password" type="password" name="password"/>
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