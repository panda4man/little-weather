<template>
    <div class="row weather">
        <div class="col-xs-12 col-sm-12">
            <div class="row">
                <div class="col-xs-6">
                    <div class="d-flex jc-center">
                        <img class="weather-icon" :src="icon" v-if="icon">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div>
                        <div class="current-temp" v-if="weather">
                            <div class="temp">{{weather.currently.temperature.toFixed(0)}}&deg;</div>
                        </div>
                        <div class="high-low" v-if="today">
                            <div class="high">{{today.temperature.high.toFixed(0)}}</div>
                            <div class="separator">/</div>
                            <div class="low">{{today.temperature.low.toFixed(0)}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-lg">
                <div class="col-xs-12" v-if="forecast.length">
                    <div class="forecast">
                        <div class="day" v-for="day in forecast">
                            <div class="temps">
                                <div class="day mb-sm">{{dayFromUnix(day.time)}}</div>
                                <div><img class="icon" :src="forecastIcon(day)"></div>
                                <div class="high">{{day.temperature.high.toFixed(0)}}</div>
                                <div class="low">{{day.temperature.low.toFixed(0)}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import IconService from '../services/IconService';
    import WeatherService from '../services/WeatherService';
    import moment from 'moment';

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

            /**
             * Only init weather service if we don't have the user's location
             */
            if(position != null && position !== '{}') {
                position = JSON.parse(position);
                this.weatherService = new WeatherService(position);
                this.refreshWeather();
            } else {
                this.weatherService = new WeatherService(position);
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
                    console.log(this.weather);
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
                let current = this.weather.currently;

                this.icon = this.iconService.icon(
                    today.precipitation.probability,
                    current.time,
                    current.precipitation.type,
                    today.sun.set,
                    today.sun.rise,
                    current.cloud_cover
                );
            },
            dayFromUnix(unix) {
                let m = moment.unix(unix);

                return m.format('ddd');
            },
            forecastIcon(day) {
                let t = moment.unix(day.time).add(8, 'hours').format('X');
                let icon = this.iconService.icon(
                    day.precipitation.probability,
                    t,
                    day.precipitation.type,
                    day.sun.set,
                    day.sun.rise,
                    day.cloud_cover
                );

                return icon;
            }
        },
        beforeDestroy() {
            clearInterval(this.interval);
        },
        computed: {
            today() {
                let t = null;

                if(this.weather && this.weather.daily) {
                    t = this.weather.daily.data[0];
                }

                return t;
            },
            forecast() {
                if(this.weather && this.weather.daily) {
                    return this.weather.daily.data.slice(0, 7);
                }

                return [];
            }
        }
    }
</script>