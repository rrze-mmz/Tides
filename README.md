## About Tides
[![Laravel](https://github.com/stefanosgeo/tides/actions/workflows/build.yml/badge.svg?branch=develop)](https://github.com/stefanosgeo/tides/actions/workflows/build.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Tides is an open source video platform based on <a  href="https://laravel.com" target="_blank"> laravel framework </a>. 

## Following Components is used

- <a href="https://tailwindcss.com/" target="_blank"> Tailwindcss </a> 
- <a href="https://plyr.io/" target="_blank"> Plyr Player </a>
- <a href="https://github.com/protonemedia/laravel-ffmpeg" target="_blank"> Laravel FFmpeg </a>


# Quick start

## Set up development environment

For local development is recommended to use 
<a href="https://laravel.com/docs/8.x/valet/" target="_blank">Valet</a>

* <a href="https://laravel.com/docs/8.x/valet">For MacOS</a>
* <a href="https://cpriego.github.io/valet-linux/">Valet fork for Linux</a>
* <a href="https://github.com/cretueusebiu/valet-windows">Valet fork for Windows</a>

## Tides install

* First clone repo
* ``` cd /repo/path ```
* ``` composer install ```
* ``` cp .env.example .env ```
* ``` artisan key:generate ```
* ``` touch /tmp/tides.sqlite ```
* ``` artisan migrate ```
## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
