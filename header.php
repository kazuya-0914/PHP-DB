<?php

function header_php() {

echo <<< EOM
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHPとデータベースで商品管理アプリを作ろう</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container m-auto">
    <header class="p-4">
      <a href="./">
        <h4>PHPとデータベースで商品管理アプリを作ろう</h4>
      </a>
    </header>
EOM;

}
