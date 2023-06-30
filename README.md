# Tides - Open Source Video Platform

[![Laravel](https://github.com/stefanosgeo/tides/actions/workflows/build.yml/badge.svg?branch=develop)](https://github.com/stefanosgeo/tides/actions/workflows/build.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Tides is an open source video platform based on the Laravel framework. It provides a flexible and customizable
solution for managing and streaming videos. This README provides instructions on setting up the development environment
and installing Tides on your local machine.

## Features and Components

Tides utilizes the following components:

- **Tailwind CSS**: A utility-first CSS framework for building responsive and modern user interfaces.
- **Plyr Player**: A lightweight and customizable HTML5 video player with a sleek design and robust features.
- **Laravel FFmpeg**: A PHP package for working with FFmpeg, allowing for video manipulation and processing.

## Quick Start

To get started with Tides, follow these steps to set up your development environment:

### Setting up Development Environment

For local development, we recommend using Valet. Choose the appropriate Valet fork for your operating system:

- [Valet for macOS](https://github.com/laravel/valet)
- [Valet for Linux](https://github.com/cpriego/valet-linux)
- [Valet for Windows](https://github.com/cretueusebiu/valet-windows)

### Tides Installation

1. Clone the Tides repository to your desired location:

   ```
   git clone https://github.com/your-username/tides.git
      ```
2. Install the required dependencies using Composer:
   ```
   composer install
      ```

3. Create a copy of the .env.example file and name it .env:
   ```
   cp .env.example .env
      ```
4. Generate a new application key:
   ```
   php artisan key:generate
      ```

5. Create an SQLite database file for Tides:
   ```
   touch /tmp/tides.sqlite
      ```
6. Run the database migrations:
   ```
   php artisan migrate
      ```

## License

Tides is open-source software licensed under the MIT license. See the LICENSE file for more details.

Thank you for your interest in Tides! If you encounter any issues or have any questions, please don't hesitate to reach
out or create a GitHub issue. We appreciate your support and contributions in making Tides a powerful video platform.

