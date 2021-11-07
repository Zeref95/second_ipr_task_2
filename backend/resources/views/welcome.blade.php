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
        .code {
            font-size: .875em;
            color: #d63384;
            word-wrap: break-word;
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
        <b>Answer: </b>
            <pre class="code">
        [{
                "id": 7,
                "title": "Молоко",
                "description": "Жителей города Кировска давно не удивить...",
                "poster": "storage/posters/7.jpg",
        }]
        </pre>

    </p>
</div>

<div class="block">
    <h2>Get movie session</h2>

    <p>
        <code>
            {{config('app.url')}}/api/v1/movie-session?movie_id=&{move_id}&date=${date}
        </code>
    </p>
    <p><b>Method:</b> GET</p>
    <p><b>Where:</b></p>
    <ul>
        <li><code>${movie_id}</code> - Move ID, like <code>5</code></li>
        <li><code>${date}</code> - Date, like <code>2021-11-05</code></li>
    </ul>

    <p>
        <b>Example: </b> <code>{{config('app.url')}}/api/v1/movie-session?movie_id=7&date=2021-11-05</code>
        <br>
        <b>Answer: </b>
    <pre class="code">
        [{
            "id": 8,
            "movie_id": 7,
            "city_id": 1,
            "date": "2021-11-06",
            "time": "12:20:00",
            "places": [
                {
                    "row": 1,
                    "place": 1,
                    "status": "free"
                },
                {
                    "row": 1,
                    "place": 2,
                    "status": "taken"
                },
            ]
        }]
        </pre>

    </p>
</div>

<div class="block">
    <h2>Make order</h2>

    <p>
        <code>
            {{config('app.url')}}/api/v1/order
        </code>
    </p>
    <p><b>Method:</b> POST</p>
    <p><b>Where:</b></p>
    <ul>
        <li><code>session_id</code> - Session ID, like <code>5</code></li>
        <li><code>places</code> - Chosen places, like <code>[1,2,3,4,5]</code></li>
        <li><code>is_test</code> - Not required, boolean, like <code>1</code></li>
    </ul>

    <p><b>Answer: </b> <code>{"status":"OK","message":"Order successfully created"}</code></p>
</div>
</body>
</html>
