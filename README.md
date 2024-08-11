# Tennis tournament

<p align="left">
    <img src="https://img.shields.io/badge/Autor-Kenneth_Gonzalez-brightgreen?style=flat&logo=codementor&logoColor=%23959da5" alt="Autor">
    <img src="https://img.shields.io/badge/laravel-v11.20.0-blue?style=flat&logo=laravel" alt="Laravel Version">
</p>

This is a PHP and Laravel challenge to demonstrate my programming skills, based on [this](https://drive.google.com/file/d/1wUdBGiFPKeFgMG-BajIgF5SHFmS6O57A/view?usp=sharing) requirement.

## Tabla de contenidos  
1. [Introduction](#tennis-tournament)
2. [Pre requisites](#pre-requisites)
3. [Local installation](#local-installation)
4. [Console commands](#console-commands)
5. [API Reference](#api-reference)

## Pre requisites

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

## Console commands

- Create a participant allowing you to enter each of its properties: Name, ability, strength, speed and reaction.

  ~~~bash
  php artisan participants:create
  ~~~

- Lists all participants and their properties in table format.

  ~~~bash
  php artisan participants:list
  ~~~

- Insert the chosen number of participants into the database.

  ~~~bash
  php artisan participants:seed
  ~~~

- Eliminate all participants.

  ~~~bash
  php artisan participants:clear
  ~~~

- Start the game, asking for the gender first, calculating and returning the winner.

  ~~~bash
  php artisan game:play
  ~~~

- Returns all participants to the initial state, leaving them ready to run the game again.

  ~~~bash
  php artisan game:reset
  ~~~

- Restart the game and after choosing the genre, play it again, returning the winner.

  ~~~bash
  php artisan game:replay
  ~~~

## API Reference

After instalation open [localhost:8000/api/documentation](http://localhost:8000/api/documentation) or check https://github.com/kendav97/tennis-tournament/blob/main/storage/api-docs/api-docs.json
