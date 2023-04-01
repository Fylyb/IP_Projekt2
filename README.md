# IP_Projekt2
pro funkčnost je třeba doplnit obsah v config/config_local.json a public/db_conn.php

config/config_local.json
```
{
  "db": {
    "user" : "example",
    "password" : "example"
  }
}
```

public/db_conn.php
```
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['name'] === null){
    header("Location: ../index.php");
}
$sname = "example";
$uname = "example";
$password = "example";

$db_name = "example";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if(!$conn) {
    echo "Connection failed!";
}
```

potřebné nainstalování packages přes composer update
