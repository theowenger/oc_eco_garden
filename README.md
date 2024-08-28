# ECO_GARDEN_API

## Description

this api is used to return gardening information and weather conditions based on your location

## Installation

    - docker compose up --build
    - copy/past .env to .env.local
    -change DATABASE_URL (optionnal)

## Launch project

    -docker exec -it eco_garden_php bash
    -once in the container:
    -composer install
    -go to localhost:{PHP_PORT}/doc

## Connect to WeatherApi.com

    - go to https://www.weatherapi.com/
    - create account
    - copy/past API key in .env.local