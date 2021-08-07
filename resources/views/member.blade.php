<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vue, Laravel, Bootstrap</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>

<b-container fluid="fluid" id="app">
    <h1>【Member一覧・編集】<div class="header_link"><a href="/">トップへ</a></div></h1>

    <router-view/>

</b-container>

<script src="{{ mix('js/app.js') }}"></script>
<style>
    .header_link {
        float: right;
        font-size: 14px;
        margin: 20px 10px 0 0;
    }
</style>
</body>
</html>
