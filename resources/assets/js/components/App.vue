<template>
    <div>
        <img :src="icon" v-if="icon">
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
                interval: null
            }
        },
        created() {
            this.iconService = new IconService;
            this.weatherService = new WeatherService;

            this.weatherService.init().then(res => {
                console.log('here');
                this.updateCurrentWeather();
            });

            //this.icon = this.iconService.icon();

            this.interval = setInterval(this.updateCurrentWeather, 1000 * 60 * 5);
        },
        methods: {
            updateCurrentWeather() {
                this.weatherService.current().then(res => {
                    console.log(res);
                }).catch(res => {
                    console.log(res);
                });
            }
        }
    }
</script>