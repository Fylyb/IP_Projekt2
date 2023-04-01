<?php
session_start();
require_once __DIR__ . "/db_conn.php";
global $conn;

if(isset($_POST['currentPassword']) && isset($_POST['newPassword'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $currentPassword = validate($_POST['currentPassword']);
    $newPassword = validate($_POST['newPassword']);

    if(empty($currentPassword)){
        header("Location: index.php?error=Current password is required");
        exit();
    }else if(empty($newPassword)) {
        header("Location: index.php?error=New password is required");
        exit();
    }else{
        //$sql = "SELECT * FROM employee WHERE login='$currentPassword' AND password='$newPassword'";
        if($_SESSION['password'] === $currentPassword && $_SESSION['admin'] == 1){
            $hashed_pass = password_hash($newPassword, PASSWORD_BCRYPT);
            $sql = mysqli_query($conn, "UPDATE employee SET password='$hashed_pass' WHERE employee_id='{$_SESSION['employee_id']}'");
            if($sql){
                header("Location: home.php");
                exit();
        }
        }else{
            echo "blbe current heslo";
        }
    }
}else{
    header("Location: index.php?error");
    exit();
}
