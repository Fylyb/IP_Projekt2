<?php
session_start();
require_once __DIR__ . "/db_conn.php";
global $conn;

if(isset($_POST['currentPassword']) && isset($_POST['newPassword']) && isset($_POST['confirmNewPassword'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $currentPassword = validate($_POST['currentPassword']);
    $newPassword = validate($_POST['newPassword']);
    $confirmNewPassword= validate($_POST['confirmNewPassword']);

    if(empty($currentPassword)){
        header("Location: home.php?error=Current password is required");
        exit();
    }else if(empty($newPassword)) {
        header("Location: home.php?error=New password is required");
        exit();
    }else if(empty($confirmNewPassword)) {
        header("Location: home.php?error=Confirm new password is required");
        exit();
    }
    else{
        if ($newPassword != $confirmNewPassword){
            header("Location: changePass.php?error=new password and new password confirmation are not the same");
            exit();
        }
        $stmtPass = "SELECT password FROM employee WHERE employee_id=" . $_SESSION['employee_id'];
        $result = mysqli_query($conn, $stmtPass);
        if($result){
            $row = mysqli_fetch_assoc($result);
            $pass = password_verify($currentPassword, $row['password']);
            if($pass){
                $hashed_pass = password_hash($newPassword, PASSWORD_BCRYPT);
                $sql = "UPDATE employee SET password='$hashed_pass' WHERE employee_id='{$_SESSION['employee_id']}'";
                $result = mysqli_query($conn, $sql);
                if($result){
                    $_SESSION['password'] = $hashed_pass;
                    header("Location: home.php");
                    exit();
                } else {
                    echo mysqli_error($conn);
                }
            }else{
                echo "Wrong current password";
            }
        }else{
            echo mysqli_error($conn);
        }
    }
}else{
    header("Location: index.php?error");
    exit();
}
