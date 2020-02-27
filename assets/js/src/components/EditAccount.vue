<template>
  <form action>
    <label for>
      <p>Имя</p>
      <input type="text" placeholder="Иван" v-model="currentUser.first_name">
    </label>
    <label for>
      <p>Фамилия</p>
      <input type="text" placeholder="Иванов" v-model="currentUser.last_name">
    </label>
    <label for>
      <p>E-Mail</p>
      <input type="email" placeholder="ivanov@mail.ru" v-model="currentUser.email">
    </label>
    <label for>
      <p>Телефон *</p>
      <input type="phone" placeholder="+7 (000) 000-00-00" v-model="currentUser.phone">
    </label>
    <button class="acoount-submit">ПОДТВЕРДИТЬ</button>
  </form>
</template>

<script>
import {mapState, mapGetters} from 'vuex';
export default {
  data() {
    return {
       account:{
            submitted: false,
            submitStatus: '',
        },
    };
  },
  computed: {
    ...mapState(['currentUser'])
  } 
};
</script>