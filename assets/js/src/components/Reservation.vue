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
                                <select name="">
                                    <option value="1">1</option>
                                </select>
                            </div>
                        </div>
                        <div class="reservation-form-column">
                            <div class="reservation-form-column-name">Стол</div>
                            <div class="reservation-form-column-value">
                                <select name="">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                </select>
                            </div>
                        </div>
                        <div class="reservation-form-column">
                            <div class="reservation-form-column-name">Количество гостей</div>
                            <quantity-button count='1'/>
                        </div>
                    </div>
                    <div class="reservation-form-row">
                        <div class="reservation-form-column-name">Дата и время визита</div>
                        <div class="reservation-form-calendar">
                            <input type="date" name="calendar">
                        </div>
                        <div class="reservation-form-time">
                        
                            <label class="control control-radio">
                                18:00
                                <input type="radio" name="radio">
                                <div class="control_indicator"></div>
                            </label>
                        
                        </div>
                    </div>
                    <div class="reservation-form-row">
                        <div class="reservation-form-column-name">Ваши данные</div>
                        <div class="reservation-form-client-info">
                            <input type="text" name="name" placeholder="Имя">
                            <input type="phone" name="phone" placeholder="Телефон">
                            <textarea name="description" placeholder="Пожелания (не обязательно)"></textarea>
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
export default {
    components: {},
    data: () => ({
        template_url: SITEDATA.themepath,
        halls: '1',
    }),
}
</script>