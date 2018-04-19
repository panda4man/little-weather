import Bluebird from 'bluebird';
import Vue from 'vue';

export default class WeatherService {
    constructor() {
        this.position = null;

        if(! navigator.geolocation) {
            alert('Geolocation is currently not supported! Please enable');
        }
    }

    init() {
        return new Bluebird((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(position => {
                this.position = position;

                resolve();
            });
        });
    }

    current() {
        if(!this.position)
            throw new Error("Position has not been initialized");

        return new Bluebird((resolve, reject) => {
            Vue.$http.get('/api/weather/current', {
                params: {
                    lat: this.position.coords.latitude,
                    lon: this.position.coords.longitude
                }
            }).then(res => {
                resolve(res);
            }).catch(res => {
                reject(null);
            });
        });
    }

    today() {
        if(!this.position)
            throw new Error("Position has not been initialized");

        return new Bluebird((resolve, reject) => {
            Vue.$http.get('/api/weather/today', {
                params: {
                    lat: this.position.coords.latitude,
                    lon: this.position.coords.longitude
                }
            }).then(res => {
                resolve(res);
            }).catch(res => {
                reject(null);
            });
        });
    }
}