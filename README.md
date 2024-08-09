# Tennis tournament

<p align="left">
    <img src="https://img.shields.io/badge/Autor-Kenneth_Gonzalez-brightgreen?style=flat&logo=codementor&logoColor=%23959da5" alt="Autor">
    <img src="https://img.shields.io/badge/laravel-v11.20.0-blue?style=flat&logo=laravel" alt="Laravel Version">
</p>

This is a PHP and Laravel challenge to demonstrate my programming skills, based on [this](https://drive.google.com/file/d/1wUdBGiFPKeFgMG-BajIgF5SHFmS6O57A/view?usp=sharing) requirement.

## Tabla de contenidos  
1. [Introduction](#php-challenge)
2. [Pre requisites](#pre-requisitos)
3. [Local installation](#ejecutar-en-entorno-local)
4. [API Reference](#api-reference)

## Pre requisitos

- Have docker installed

## Local installation

- Clone the project  

  ~~~bash  
  git clone https://github.com/kendav97/tennis-tournament.git
  ~~~

- Enter the project directory and configure

  ~~~bash  
  cd tennis-tournament
  cp .env.example .env
  ~~~

- Edit `.env` as follows:

  - Escribir una constraseña cualquiera para `DB_PASSWORD`

- Start docker

  ~~~bash
  docker compose build
  docker compose up -d
  ~~~

- Install dependencies and generate keys

  ~~~bash
  docker compose exec app bash
  composer install
  php artisan key:generate
  php artisan key:generate --env=testing
  php artisan migrate
  ~~~

- And finally, run the tests
  ~~~bash
  php artisan test
  ~~~

## API Reference

After instalation open [localhost:8000/api/documentation](http://localhost:8000/api/documentation) or check https://github.com/kendav97/tennis-tournament/blob/main/storage/api-docs/api-docs.json