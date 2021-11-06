<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documentation</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <style>
        h1 {
            text-align: center;
        }
        h2 {
            color: #336dc3;
        }
        .block {
            background-color: #ececec;
            margin-top: 50px;
            padding-left: 15px;
        }
    </style>
</head>
<body>
<h1>API Documentation</h1>

<div class="block">
    <h2>Get cities list</h2>

    <p>
        <code>
            {{config('app.url')}}/api/v1/cities
        </code>
    </p>
    <p><b>Method:</b> GET</p>

    <p>
        <b>Example: </b> <code>{{config('app.url')}}/api/v1/cities</code>
        <br>
        <b>Answer: </b> <code>[{"id":1,"name":"Taganrog"},{"id":2,"name":"Rostov"}]</code>
    </p>
</div>

<div class="block">
    <h2>Get movies list</h2>

    <p>
        <code>
            {{config('app.url')}}/api/v1/movies?city_id=${city_id}
        </code>
    </p>
    <p>
        <code>
            {{config('app.url')}}/api/v1/movies?city_id=${city_id}&date=${date}
        </code>
    </p>
    <p><b>Method:</b> GET</p>
    <p><b>Where:</b></p>
    <ul>
        <li><code>${city_id}</code> - Id of city, like <code>1</code></li>
        <li><code>${date}</code> - Date, like <code>2021-11-05</code></li>
    </ul>

    <p>
        <b>Example: </b> <code>{{config('app.url')}}/api/v1/movies?city_id=1&date=2021-11-05</code>
        <br>
        <b>Answer: </b> <code>Список фильмов</code>
    </p>
</div>

<div class="block">
    <h2>Get movie info</h2>

    <p>
        <code>
            {{config('app.url')}}/api/v1/movie-info/${movie_id}/${date}
        </code>
    </p>
    <p><b>Method:</b> GET</p>
    <p><b>Where:</b></p>
    <ul>
        <li><code>${movie_id}</code> - Move ID, like <code>5</code></li>
        <li><code>${date}</code> - Date, like <code>2021-11-05</code></li>
    </ul>

    <p><b>Answer: </b> <code>{Возможные сеансы и места}</code></p>
</div>

<div class="block">
    <h2>Make order</h2>

    <p>
        <code>
            {{config('app.url')}}/api/v1/order
        </code>
    </p>
    <p><b>Method:</b> POST</p>
    <p><b>Body:</b></p>
    <ul>
        <li><code>session_id</code> - Session ID, like <code>5</code></li>
        <li><code>places</code> - Chosen places, like <code>[1,2,3,4,5]</code></li>
    </ul>

    <p><b>Answer: </b> <code>{успешно или ошибка}</code></p>
</div>
</body>
</html>
