<template>
  <form action>
    <label for>
      <p>Имя</p>
      <input type="text" placeholder="Иван" />
    </label>
    <label for>
      <p>Фамилия</p>
      <input type="text" placeholder="Иванов" />
    </label>
    <label for>
      <p>E-Mail</p>
      <input type="email" placeholder="ivanov@mail.ru" />
    </label>
    <label for>
      <p>Телефон *</p>
      <input type="phone" placeholder="+7 (000) 000-00-00" />
    </label>
    <button class="acoount-submit">ПОДТВЕРДИТЬ</button>
  </form>
</template>

<script>
export default {
  data() {
    return {
       account:{
            submitted: false,
            submitStatus: '',
            first_name: SITEDATA.user_first_name,
            last_name: SITEDATA.user_last_name,
            country: SITEDATA.user_country,
            city: SITEDATA.user_city,
            address: SITEDATA.user_address,
            phone: SITEDATA.user_phone,
            email: SITEDATA.user_email,
        },
    };
  },

  
};
</script>