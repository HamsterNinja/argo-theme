<template>
    <div class="modal-callback">
        <div class="modal-callback-container">
            <div class="modal-callback__title">Обратная связь</div>
            <form @submit.prevent="submitCallbackForm">
            
                <div class="form-group-float-placeholder"> 
                    <input class="modal-login__input form-control" v-model="name" type="text" id="callback_name" placeholder="Имя" required />
                    <label class="form-control-placeholder" for="callback_name">Имя</label>
                </div>

                <div class="form-group-float-placeholder"> 
                    <input class="modal-login__input form-control" v-model="phone" type="text" id="callback_phone" placeholder="Номер" required />
                    <label class="form-control-placeholder" for="callback_phone">Номер</label>
                </div>

      
                <button class="button">Отправить</button>
            </form>
          
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            site_url: SITEDATA.url,
            template_url: SITEDATA.themepath,
            name: '',
            phone: '',
            security: SITEDATA.security,
            errors: ''
        };
    },
    methods: {
        async submitCallbackForm() {
            let formCallback = new FormData(); 
            formCallback.append("action", "ajaxlogin");
            formCallback.append("name", this.name);
            formCallback.append("phone", this.phone);
            formCallback.append("security", this.security);
            let fetchData = {
                method: "POST",
                body: formCallback
            };
            let response = await fetch(SITEDATA.ajax_url, fetchData);
            let jsonResponse = await response.json();
            if (jsonResponse.loggedin == false) {
                this.errors = jsonResponse.message;
            }
            else{
                // document.location.reload();
            }
        }
    }
};
</script>