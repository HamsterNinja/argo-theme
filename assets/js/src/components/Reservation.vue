<template>
    <div class="reservation-content">
        <div class="new-block-fone-white pad-50">
        <div class="container">
            <div class="reservation-left">
                <div class="reservation-left-title">Забронировать стол</div>
                <form action="">
                    <div class="reservation-form-row">
                        <div class="reservation-form-column">
                            <div class="reservation-form-column-name">Зал</div>
                            <div class="reservation-form-column-value">
                                <select v-model="halls">
                                    <option v-for="(value, index) of hallsRange" :key="`halls-${index}`" :value="value">{{value}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="reservation-form-column">
                            <div class="reservation-form-column-name">Стол</div>
                            <div class="reservation-form-column-value">
                                <select v-model="table">
                                    <option v-for="(value, index) of tablesRange" :key="`table-${index}`" :value="value">{{value}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="reservation-form-column">
                            <div class="reservation-form-column-name">Количество гостей</div>
                            <quantity-button v-model="guests"/>
                        </div>
                    </div>
                    <div class="reservation-form-row">
                        <div class="reservation-form-column-name">Дата и время визита</div>
                        <div class="reservation-form-calendar">
                            <input type="date" name="calendar" v-model="date" :min="currentDate">
                        </div>
                        <div class="reservation-form-time">
                            <label class="control control-radio" v-for="(value, index) of timeRange" :key="`time-${index}`">
                                {{ value }}
                                <input type="radio" :value="value" name="radio" v-model="time">
                                <div class="control_indicator"></div>
                            </label>
                        </div>
                    </div>
                    <div class="reservation-form-row">
                        <div class="reservation-form-column-name">Ваши данные</div>
                        <div class="reservation-form-client-info">
                            <div class="form-group" :class="{ 'input--error': $v.name.$error }">
                                <input v-model.trim="$v.name.$model" type="text" name="name" placeholder="Имя">
                                <div class="errors-form">
                                    <div class="error" v-if="!$v.name.required">Имя обязательно</div>
                                </div>
                            </div> 
                            <div class="form-group" :class="{ 'input--error': focusPhone && $v.phone.$error }">
                                <masked-input type="phone" name="phone" v-model.trim="$v.phone.$model" placeholder="Телефон" mask="\+\7 (111) 111-11-11" @focus.native="focusPhone = true"></masked-input>
                                <div class="errors-form">
                                    <div class="error" v-if="focusPhone && !$v.phone.required">Телефон обязателен</div>
                                    <div class="error" v-if="focusPhone && !$v.phone.correctPhone">Должен быть действительный телефон</div>
                                </div>
                            </div>
                            <textarea v-model="comment" name="description" placeholder="Пожелания (не обязательно)"></textarea>
                        </div>
                    </div>
                    <button class="reservation-submit" @click.prevent="addReservation">ЗАБРОНИРОВАТЬ</button>
                </form>
            </div>
            <div class="reservation-right">
                <tabs @set_tab="setHall($event)">
                    <tab name="1 зал" :selected="true">
                        <map-hall @set_table="setTable($event)" uid="_1" :tables="orderedTables"></map-hall>    
                    </tab>
                    <!-- TODO: поменять картинку зала -->
                    <tab name="2 зал">
                        <map-hall-2 @set_table="setTable($event)" uid="_2" :tables="orderedTables"></map-hall-2>
                    </tab>
                    <!-- TODO: временно отключить веранду -->
                    <!-- <tab name="3 зал">
                        <map-hall @set_table="setTable($event)" uid="_3" :tables="orderedTables"></map-hall>
                    </tab> -->
                </tabs>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
import Moment from 'moment';
import { extendMoment } from 'moment-range';
import { required,  email, helpers, sameAs, minLength } from 'vuelidate/lib/validators';
import MaskedInput from 'vue-masked-input';
const alpha = helpers.regex('alpha', /[\u0000-~Ѐ-Ӿ]/);

const moment = extendMoment(Moment);

const range = (start, end) => Array.from({length: (end - start)}, (v, k) => k + start);

import {mapState, mapGetters} from 'vuex';
import { modal } from "./mixins/modal";
import { mapValues, groupBy } from 'lodash';

const nest = function (seq, keys) {
    if (!keys.length) {return seq}
    let first = keys[0]
    let rest = keys.slice(1)
    return mapValues(groupBy(seq, first), value => nest(value, rest));
};

export default {
    mixins: [modal],
    components: {
        'masked-input': MaskedInput,
    },
    validations: {
        name: {
            required,
        },
        phone: {
            required,
            correctPhone: (phone) => { 
                return phone.replace(/[^\d\.]/g, '').length == 11
            }
        },        
    },
    data: () => ({
        template_url: SITEDATA.themepath,
        time: '9:00',
        currentTime: moment().format('HH:mm'),
        date: moment().format('YYYY-MM-DD'),
        currentDate: moment().format('YYYY-MM-DD'),
        halls: 1,
        table: 1,
        guests: 1,
        hallsRange: range(1, 3),
        tablesRange: range(1, 10),
        name: '',
        phone: '',
        focusPhone: false,
        comment: '',
    }),
    computed: {
        ...mapState(['currentUser']),
        orders(){
            return this.$store.getters["orders"]
        },
        timeRange(){
            let times = [];
            let hours = range(9, 21)
            let minutes = ["00", "30"]
            
            hours.forEach(hour => {
                minutes.forEach(minute => {
                    // TODO: скрывать забронированное время для столиков
                    // if(this.orderedTime.includes(hour + ":" + minute)){
                        times.push(hour + ":" + minute)
                    // }
                })
            })

            return times;
        },
        ordersByDateTime(){
            let result = [];
            try {
                let orderGroupByDateTime = nest(this.orders, ['date', 'time'])
                let dateKey = moment(this.date).format('DD/MM/YYYY')
                let timeKey = moment(this.time, "HH:mm").format('HH:mm:ss')
                result = orderGroupByDateTime[dateKey][timeKey]
            } catch (e) {
                console.log(e);
            }
            return result
        },
        orderedTime(){
            let result = [];
            try {
                let orderGroupByDate = nest(this.orders, ['date', 'halls', 'table'])
                let dateKey = moment(this.date).format('DD/MM/YYYY')
                result = orderGroupByDate[dateKey][this.halls][this.table].map(item => moment(item['time'], "HH:mm").format('H:mm'))
                return result
            }
            catch (e) {
                console.log(e);
            }
            return result
        },
        orderedTables(){
            let result = [];
            try {
                let tablesByHalls = nest(this.orders, ['date', 'time', 'halls'])
                let dateKey = moment(this.date).format('DD/MM/YYYY')
                let timeKey = moment(this.time, "HH:mm").format('HH:mm:ss')
                result = tablesByHalls[dateKey][timeKey][this.halls]
            }
            catch (e) {
                console.log(e);
            }
            // TODO: сгрупировать по столам
            return result
        }
    },
    async mounted() {
        if (this.$store.getters['orders'].length === 0) {
            await this.$store.dispatch('fetchOrders')
        }
    },
    methods: {
        async addReservation() {
            let formLogin = new FormData(); 
            formLogin.append("name", this.name);
            formLogin.append("phone", this.phone);
            formLogin.append("comment", this.comment);
            formLogin.append("date", this.date);
            formLogin.append("time", this.time);
            formLogin.append("guests", this.guests);
            formLogin.append("halls", this.halls);
            formLogin.append("table", this.table);

            const sendURL = `${SITEDATA.url}/wp-json/amadreh/v1/add-reservation/`;
            
            let fetchData = {
                method: "POST",
                body: formLogin
            };

            let response = await fetch(sendURL, fetchData);
            let data = await response.json();
            if (data.success) {
                this.showModal("modal-window--thank");
            }
        },

        setHall(tab){
            this.halls = parseInt(tab)
        },
        
        setTable(number){
            this.table = number
        }
    }
}
</script>