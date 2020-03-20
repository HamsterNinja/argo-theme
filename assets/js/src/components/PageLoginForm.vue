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
                <a :href="site_url + '/my-account/lost-password/'" class="forgot-password">Забыли пароль?</a>
            </div>
            <div class="account-right-form notice">                                               
                <button class="acoount-submit state-button"
                :class="{ 
                'state-button--pending': submitStatus == 'PENDING', 
                'state-button--success': submitStatus == 'SUCCESS',
                'state-button--fail': submitStatus == 'ERROR',
			    }"
                >
                <span class="state-button__text">Войти</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            site_url: SITEDATA.url,
            template_url: SITEDATA.themepath,
            username: '',
            password: '',
            security: SITEDATA.security,
            errors: '',
            submitted: false,
            submitStatus: '', 
        };
    },
    computed: {
        login_failure(){
            return this.errors.includes('Неверное имя пользователя или пароль')
        }
    },
    methods: {
        async submitLoginForm() {
            this.submitStatus = 'PENDING'
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
                this.submitStatus = 'ERROR';
                setTimeout(() => {this.submitStatus = ''}, 1000);
            }
            else{
                console.log(jsonResponse.loggedin);
                console.log(jsonResponse.loggedin === false);
                this.submitStatus = 'SUCCESS';
                setTimeout(() => {this.submitStatus = ''}, 1000);
                setTimeout(() => { document.location.reload() }, 1200);
            }
        }
    }
};
</script>