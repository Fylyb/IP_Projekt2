<?php
session_start();
if ($_SESSION['name'] === null){
header("Location: ../index.php");
}
?>
<html>
<head>
    <title>PHP login system</title>
</head>
<body>
<form action="changePassBack.php" method="post">
    <h2>Change password</h2>
    <?php if (isset($_GET['error'])) { ?>
        <p class="error"><?php echo $_GET['error']; ?></p>
    <?php } ?>
    <label>Current password</label>
    <input type="password" name="currentPassword" placeholder="Current password">

    <label>New password</label>
    <input type="password" name="newPassword" placeholder="New password">

    <button type="submit">Change password</button>
</form>
</body>
</html>
