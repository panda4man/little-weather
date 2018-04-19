import moment from 'moment';

export default class IconService {
    icon(probability, unix, precip_type, sunset, sunrise, cloudy) {
        this.now = moment(unix).local();
        this.probability = probability;
        this.type = precip_type; //rain, snow, sleet
        this.sunset = moment(sunset).local();
        this.sunrise = moment(sunrise).local();
        this.cloudy = cloudy;

        let light = 0;

        if(now.isBefore(this.sunset) && now.isAfter(this.sunrise)) {
            light = 1;
        } else if (now.isBefore(this.sunrise) || now.isAfter(this.sunrise)) {
            light = -1;
        }

        // No precipitation at all
        if(!this.type) {
            if(this.cloudy > 0.3) { // Show clouds
                if(light > -1) { // sun out
                    return '/img/weather/Cloud-Sun.svg';
                } else { // moon out
                    return '/img/weather/Cloud-Moon.svg';
                }
            } else { // No clouds
                if(light > -1) {
                    return '/img/weather/Sun.svg';
                } else {
                    return '/img/weather/Moon.svg';
                }
            }
        } else { // Rain, snow, sleet
            if(light > -1) {
                if (this.type === 'snow') {
                    return '/img/weather/Cloud-Snow-Sun.svg';
                } else {
                    return '/img/weather/Cloud-Rain-Sun.svg';
                }
            } else {
                if (this.type === 'snow') {
                    return '/img/weather/Cloud-Snow-Moon.svg';
                } else {
                    return '/img/weather/Cloud-Rain-Moon.svg';
                }
            }
        }
    }
}