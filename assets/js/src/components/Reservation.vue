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
                            <input type="date" name="calendar" v-model="date">
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
                            <input v-model="name" type="text" name="name" placeholder="Имя">
                            <input v-model="phone" type="phone" name="phone" placeholder="Телефон">
                            <textarea v-model="comment" name="description" placeholder="Пожелания (не обязательно)"></textarea>
                        </div>
                    </div>
                    <button class="reservation-submit">ЗАБРОНИРОВАТЬ</button>
                </form>
            </div>
            <div class="reservation-right">
                <tabs>
                    <tab name="1 зал" :selected="true">
                        <object type="image/svg+xml" :data="template_url + `/assets/images/scheme-1.svg`">Ваш браузер не поддерживает SVG</object>
                    </tab>
                    <tab name="2 зал">
                        <object type="image/svg+xml" :data="template_url + `/assets/images/scheme-1.svg`">Ваш браузер не поддерживает SVG</object>
                    </tab>
                    <tab name="3 зал">
                        <object type="image/svg+xml" :data="template_url + `/assets/images/scheme-1.svg`">Ваш браузер не поддерживает SVG</object>
                    </tab>
                </tabs>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
import Moment from 'moment';
import { extendMoment } from 'moment-range';

const moment = extendMoment(Moment);

const range = (start, end) => Array.from({length: (end - start)}, (v, k) => k + start);

import {mapState, mapGetters} from 'vuex';
export default {
    components: {},
    data: () => ({
        template_url: SITEDATA.themepath,
        time: '',
        date: '',
        halls: 1,
        table: 1,
        guests: 1,
        hallsRange: range(1, 4),
        tablesRange: range(1, 10),
        name: '',
        phone: '',
        comment: '',
    }),
    computed: {
        ...mapState(['currentUser']),
        timeRange(){
            let times = [];
            let hours = range(9, 21)
            let minutes = ["00", "30"]
            
            hours.forEach(hour => {
                minutes.forEach(minute => {
                    times.push(hour + ":" + minute)
                })
            })

            return times;
        }
    } 
}
</script>