# PHP Challenge

<p align="left">
    <img src="https://img.shields.io/badge/Autor-Kenneth_Gonzalez-brightgreen?style=flat&logo=codementor&logoColor=%23959da5" alt="Autor">
    <img src="https://img.shields.io/badge/laravel-v11.20.0-blue?style=flat&logo=laravel" alt="Laravel Version">
</p>

Este es un desafío de PHP y laravel para demostrar mis habilidades como programador, basandome en [ésta](https://drive.google.com/file/d/1wUdBGiFPKeFgMG-BajIgF5SHFmS6O57A/view?usp=sharing) consigna.

## Tabla de contenidos  
1. [Introducción](#php-challenge)
2. [Pre requisitos](#pre-requisitos)
3. [Ejecutar en entorno local](#ejecutar-en-entorno-local)
4. [API Reference](#api-reference)

## Pre requisitos

- Tener docker instalado

## Ejecutar en entorno local

- Clonar el proyecto  

  ~~~bash  
  git clone https://github.com/kendav97/tennis-tournament.git
  ~~~

- Ingresar al directorio del proyecto y configurar

  ~~~bash  
  cd tennis-tournament
  cp .env.example .env
  ~~~

- Editar `.env` de la siguiente manera:

  - Escribir una constraseña cualquiera para `DB_PASSWORD`

- Iniciar docker

  ~~~bash
  docker compose build
  docker compose up -d
  ~~~

- Instalar dependencias y generar claves

  ~~~bash
  docker compose exec app bash
  composer install
  php artisan key:generate
  php artisan key:generate --env=testing
  php artisan migrate
  ~~~

- Y por último, correr los tests
  ~~~bash
  php artisan test
  ~~~

## API Reference

<script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@latest/swagger-ui.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@latest/swagger-ui.css" />

<div id="swagger-ui"></div>

<script>
    window.onload = function() {
        const ui = SwaggerUI({
            url: "https://raw.githubusercontent.com/kendav97/tennis-tournament/main/storage/api-docs/api-docs.json",
            dom_id: '#swagger-ui',
        });
    }
</script>