# Symfony5 movie collection
This is an simple applicaiotn using symfony veriosn 5. This application inclues two part web and api, both parts has crud operations using web interface and api call. Tools and technology used Symfony5 (V5), MariaDB 10, Bootstrap 4, Postman

# Installation
Clone git repo

```
git clone https://github.com/anwarhossainam2020/symfony5-movie-collection.git
```

Install dependencies:
```
composer install
```

Create database:
```
php bin/console doctrine:database:create
```

Run database migration
```
php bin/console doctrine:migrations:migrate
```

Run Server / project

```
symfony server:start -d --no-tls --allow-http
```

# Run Web / review

```
http://127.0.0.1:8000/
```
This is url will show Movie collection list, Create New Movie, Edit Movie, Delete movie link.

# Run API

Get Movie list API endpoint. Method ```GET```
```
http://127.0.0.1:8000/api/movie
```
Create New Movie. 
Method ```POST```
Params
- title
```
http://127.0.0.1:8000/api/movie
```
Edit & update existing movie. 
Method ```PATCH|PUT```
Params
- id
- title
- count [optional]
```
http://127.0.0.1:8000/api/movie
```
Delete Movie
Method ```DELETE```
Params
- id
```
http://127.0.0.1:8000/api/movie
```
Increase movie view count. Movie ```{id}``` must be replace with database ID
```
http://127.0.0.1:8000/api/movie/{id}/count/increase
```
Decrease movie view count. Movie ```{id}``` must be replace with database ID
```
http://127.0.0.1:8000/api/movie/{id}/count/decrease
```



