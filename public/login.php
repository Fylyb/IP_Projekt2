<?php
session_start();
require_once __DIR__ . "/db_conn.php";
global $conn;

if(isset($_POST['uname']) && isset($_POST['password'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);

    $stmtPass = "SELECT password FROM employee WHERE login='$uname'";
    $result = mysqli_query($conn, $stmtPass);
    if(mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $passVerify = password_verify($pass, $row['password']);
        if($passVerify){
            $sql = "SELECT * FROM employee WHERE login='$uname' AND password='$row[password]'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['login'] = $row['login'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['surname'] = $row['surname'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['admin'] = $row['admin'];
                $_SESSION['employee_id'] = $row['employee_id'];
                header("Location: home.php");
                exit();
            }else{
                header("Location: index.php?error=Incorrect User name or password");
                exit();
            }
        }else{
            header("Location: index.php?error=Incorrect User name or password");
            exit();
        }
    } else {
        header("Location: index.php?error=Incorrect User name or password");
        exit();
    }

}else{
    header("Location: index.php?error");
    exit();
}
