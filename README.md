# :soccer: Football Stats App - backend

## General Informations
This is a main repo of Football Stats App - web application which allows users to view the scores, the football schedule, as well as statistics of football teams and matches. It was made for the needs of an engineering thesis. Project consists of the three parts: backend ([GraphQL](https://graphql.org/) API) in this repo, [frontend](https://github.com/Lasek25/football_stats_app_front) and [scrapers](https://github.com/Lasek25/football_stats_app_scrapers) which provide the football stats data. Application allows users to create an account, but it is not obligatory.  
Feel free to test Football Stats App here (sometimes first loading may take a few seconds): [https://footballstatsapplication.herokuapp.com](https://footballstatsapplication.herokuapp.com)

## Database Schema
![Database](https://user-images.githubusercontent.com/56116548/129792696-24be1421-6b9d-456c-b305-fe52f4cf572f.png)

## Tech Stack
* [PHP](https://www.php.net/) (version: 7.3)
* [Laravel](https://laravel.com/docs/8.x) (version: 8.0)
* [Lighthouse](https://lighthouse-php.com/) - GraphQL framework for Laravel (version: 4.18)
* [Lighthouse GraphQL Passport Auth](https://github.com/joselfonseca/lighthouse-graphql-passport-auth) - GraphQL mutations for authentication with Laravel Passport
* MySQL
* [Laravel GraphQL Playground](https://github.com/mll-lab/laravel-graphql-playground) (version: 2.4)

## Features
1. Main features:
    * get matches schedule and results
    * get informations about selected league - standings, results, fixtures, statistics of the teams (goals, corners, fouls, offsides, etc.)
    * get informations about the best teams in selected stats category (goals, corners, fouls, offsides, etc.)
    * sort and filter the above-mentioned data
2. Authorization:
    * create an account
    * sign in/out
    * change username or password
3. Others:
    * change theme of the application (dark or light mode)
    * enjoy fully responsive app on all of your devices (computers, smartphones and tablets)

## Instalation
1. You need to install PHP and create database
2. 

## Usage
You can test GraphQL API here: [https://apifootballstatsapp.herokuapp.com/graphql-playground](https://apifootballstatsapp.herokuapp.com/graphql-playground)
download application to your smartphone add to home screen (only for android users)

