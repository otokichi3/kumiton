<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>くみとん</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="apple-touch-icon" href="shuttle.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/main.css') ?>">
	<script src="<?= base_url('fontawesome-free-5.8.2-web/js/all.min.js') ?>"></script>
	
</head>

<body>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <a href="<?= base_url('main') ?>" class="navbar-brand">くみとん</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navmenu1" aria-controls="navmenu1" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navmenu1">
            <div class="navbar-nav">
                <a class="nav-item nav-link" href="<?= base_url('main/manage_member') ?>">メンバー管理</a>
                <a class="nav-item nav-link" href="<?= base_url('main') ?>">メンバー選択</a>
                <a class="nav-item nav-link" href="<?= base_url('main/show_match') ?>">試合</a>
                <a class="nav-item nav-link" href="#">履歴</a>
            </div>
        </div>
    </nav>
