# a3f-test

Для сборки проекта в системе должны быть установлены: Make, docker и docker compose CLI plugin.
Если используете традиционный docker-compose, замените все вхождения docker compose на docker-compose
в корне проекта в Makefile.

1. git clone

2. make init - в корне проекта. Соберется контейнер с приложением.

3. HOST=https://site.for.test make run - посчитает количество тегов для указанного сайта.

## Примеры:
![pwd.hermna.team](https://github.com/hermansochi/a3f-test/raw/master/assets/pwd_herman_team.png)

![habr.com](https://github.com/hermansochi/a3f-test/raw/master/assets/habr_com.png)

![kuban.rbc.ru](https://github.com/hermansochi/a3f-test/raw/master/assets/kuban_rbc.ru.png)