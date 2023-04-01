<html>
<head>
    <title>PHP login system</title>
</head>
<body>
    <form action="login.php" method="post">
        <h2>LOGIN</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
         <?php } ?>
        <label>User Name</label>
        <input type="text" name="uname" placeholder="User Name">

        <label>Password</label>
        <input type="password" name="password" placeholder="Password">

        <button type="submit">Login</button>
    </form>
</body>
</html>
