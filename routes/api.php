<?php

Route::get('/weather/current', 'Api\WeatherController@getCurrent');
Route::get('/weather/today', 'Api\WeatherController@getToday');