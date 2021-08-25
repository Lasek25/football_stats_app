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
1. You need to install PHP and create an empty database
2. Add 2 stored procedures to your database:
    * `avgGoals` without any parameters
    ```
    CREATE PROCEDURE `avgGoals`()
    SELECT teams_in_competition_id, goals, team_rank, date FROM (SELECT tim.*, m.date, @team_rank := IF(@current_team = tim.teams_in_competition_id, @team_rank + 1, 1) AS team_rank, @current_team := tim.teams_in_competition_id FROM teams_in_matches tim JOIN matches m ON tim.match_id = m.id WHERE tim.updated_at <> '' ORDER BY tim.teams_in_competition_id ASC, `m`.`date` DESC) ranked WHERE team_rank <= 5;
    ```
    * `insert_update_matches` with a few parameters (but firstly change delimiter from `;` to `$$` and finally after adding procedure back to `;`)
    ```
    CREATE PROCEDURE `insert_update_matches`(IN `f_id` VARCHAR(255), IN `new_round` TINYINT, IN `new_date` DATETIME, IN `new_team_home` VARCHAR(255), IN `new_team_away` VARCHAR(255))
    BEGIN
    IF ((SELECT date FROM matches WHERE BINARY flashscore_id = f_id) AND ((SELECT date FROM matches WHERE BINARY flashscore_id = f_id) <> new_date))
    THEN
    UPDATE matches SET date=new_date, updated_at=now() WHERE BINARY flashscore_id = f_id;
    ELSEIF (NOT EXISTS (SELECT id FROM matches WHERE BINARY flashscore_id = f_id))
    THEN
    INSERT INTO matches (flashscore_id, round, date, created_at, updated_at) VALUES (f_id,new_round,new_date,now(),now());

    INSERT INTO teams_in_matches (match_id, teams_in_competition_id, created_at) VALUES

    ((SELECT id FROM matches WHERE BINARY flashscore_id = f_id), 
    (SELECT max(id) FROM teams_in_competitions WHERE team_id IN (SELECT id FROM teams WHERE name = new_team_home)), now()),

    ((SELECT id FROM matches WHERE BINARY flashscore_id = f_id), 
    (SELECT max(id) FROM teams_in_competitions WHERE team_id IN (SELECT id FROM teams WHERE name = new_team_away)), now());
    END IF;
    END
    ```
3. Download or clone this repo with `git clone https://github.com/Lasek25/football_stats_app.git` command
4. Go to the folder application using `cd ./football_stats_app` command on your terminal and then run `composer install` command
5. Create `.env` file, copy contents of `.env.example` file, set database data (including `DB_DATABASE`) and generate `APP_KEY` with command `php artisan key:generate`
6. Run migration `php artisan migrate`
7. Finally you can run API with `php artisan serve` command and test it

## Usage
You can test GraphQL API here: [https://apifootballstatsapp.herokuapp.com/graphql-playground](https://apifootballstatsapp.herokuapp.com/graphql-playground)

