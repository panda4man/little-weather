<template>
    <div class="row">
        <div class="col-sm-12">
            <img class="weather-icon" :src="icon" v-if="icon">
        </div>
        <div v-if="http.location">Loading the location...</div>
        <pre v-if="currentWeather">{{currentWeather}}</pre>
        <pre v-if="todayWeather">{{todayWeather}}</pre>
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
                currentWeather: null,
                todayWeather: null,
                interval: null
            }
        },
        created() {
            let position = localStorage.getItem('position');
            this.iconService = new IconService;
            this.weatherService = new WeatherService(position);

            if(position != null && position !== '{}') {
                position = JSON.parse(position);
                this.updateCurrentWeather();
                this.updateTodayWeather();
            } else {
                this.http.location = true;

                this.weatherService.init().then(res => {
                    this.http.location = false;
                    this.updateCurrentWeather();
                    this.updateTodayWeather();
                });
            }

            // Update current weather every 5 minutes
            this.interval = setInterval(() => {
                this.updateCurrentWeather();
                this.updateTodayWeather();
            }, 1000 * 60 * 5);
        },
        methods: {
            updateCurrentWeather() {
                this.weatherService.current().then(res => {
                    this.currentWeather = res.data.data;
                }).catch(res => {
                    console.log(res);
                });
            },
            updateTodayWeather() {
                this.weatherService.today().then(res => {
                    this.todayWeather = res.data.data;

                    this.updateIcon();
                }).catch(res => {
                    console.log(res);
                });
            },
            updateIcon() {
                if(!this.todayWeather) {
                    return false;
                }

                this.icon = this.iconService.icon(
                    this.todayWeather.precipitation.probability,
                    this.todayWeather.time,
                    this.todayWeather.precipitation.type,
                    this.todayWeather.sun.set,
                    this.todayWeather.sun.rise,
                    this.todayWeather.cloud_cover
                );
            }
        },
        beforeDestroy() {
            clearInterval(this.interval);
        }
    }
</script>