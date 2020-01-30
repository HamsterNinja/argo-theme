const contactsMap = {
    init: () => {
        const contactMapsElement = document.querySelector('#map');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.intersectionRatio > 0) {
                    if (contactMapsElement) {
                        ymaps.ready(function () {
                            var myMap = new ymaps.Map('map', {
                                    center: [55.732829, 38.951328],
                                    zoom: 16.8  
                                }, {
                                    searchControlProvider: 'yandex#search'
                                }),

                                myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
                                    hintContent: 'Хинкальная',
                                    balloonContent: 'Ликино-Дулево, ул. Калинина, 5'
                                }, {
                                    preset: 'islands#blueFoodIcon',
                                    iconColor: '#ed4543'
                                });

                            myMap.geoObjects
                                .add(myPlacemark);
                        });
                    }
                    observer.unobserve(entry.target);
                }
            });
        });
        if (contactMapsElement) {
            observer.observe(contactMapsElement);
        }
    }
}

module.exports = contactsMap;