import Bluebird from 'bluebird';
import Vue from 'vue';

export default class WeatherService {
    constructor(position = null) {
        this.position = position || null;

        if (!navigator.geolocation) {
            //TODO: handle this better
            alert('Geolocation is currently not supported! Please enable');
        }
    }

    /**
     * Fetch the user's location
     */
    init() {
        return new Bluebird((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(position => {
                this.position = {
                    lat: position.coords.latitude,
                    lon: position.coords.longitude
                };
                console.log(this.position);
                console.log(JSON.stringify(this.position));

                localStorage.setItem('position', JSON.stringify(this.position));

                resolve();
            });
        });
    }

    /**
     * Fetch the current weather conditions
     */
    current() {
        if (!this.position)
            throw new Error("Position has not been initialized");

        return new Bluebird((resolve, reject) => {
            Vue.prototype.$http.get('/api/weather/current', {
                params: {
                    lat: this.position.lat,
                    lon: this.position.lon
                }
            }).then(res => {
                resolve(res);
            }).catch(res => {
                reject(null);
            });
        });
    }

    /**
     * Fetch the weather for today
     */
    today() {
        if (!this.position)
            throw new Error("Position has not been initialized");

        return new Bluebird((resolve, reject) => {
            Vue.prototype.$http.get('/api/weather/today', {
                params: {
                    lat: this.position.lat,
                    lon: this.position.lon
                }
            }).then(res => {
                resolve(res);
            }).catch(res => {
                reject(null);
            });
        });
    }
}