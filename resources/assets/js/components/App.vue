<template>
    <div class="row">
        <div class="col-sm-12">
            <img class="weather-icon" :src="icon" v-if="icon">
        </div>
        <div v-if="http.location">Loading the location...</div>
        <pre v-if="weather">{{weather}}</pre>
    </div>
</template>

<script>
    import IconService from '../services/IconService';
    import WeatherService from '../services/WeatherService';

    export default {
        iconService: null,
        weatherService: null,
        name: 'app',
        data() {
            return {
                icon: null,
                http: {
                    location: false
                },
                weather: null,
                interval: null
            }
        },
        created() {
            let position = localStorage.getItem('position');
            this.iconService = new IconService;
            this.weatherService = new WeatherService(position);

            /**
             * Only init weather service if we don't have the user's location
             */
            if(position != null && position !== '{}') {
                position = JSON.parse(position);
                this.refreshWeather();
            } else {
                this.http.location = true;

                this.weatherService.init().then(res => {
                    this.http.location = false;
                    this.refreshWeather();
                });
            }

            // Update current weather every 5 minutes
            this.interval = setInterval(() => {
                this.refreshWeather();
            }, 1000 * 60 * 5);
        },
        methods: {
            refreshWeather() {
                this.weatherService.weather().then(data => {
                    this.weather = data;
                    this.updateIcon();
                }).catch(res => {
                    console.log(res);
                });
            },
            updateIcon() {
                if(!this.weather) {
                    return false;
                }

                let today = this.weather.daily.data[0];

                this.icon = this.iconService.icon(
                    today.precipitation.probability,
                    today.time,
                    today.precipitation.type,
                    today.sun.set,
                    today.sun.rise,
                    today.cloud_cover
                );
            }
        },
        beforeDestroy() {
            clearInterval(this.interval);
        }
    }
</script>