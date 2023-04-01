<?php
require_once __DIR__ . "/../bootstrap/bootstrap.php";

session_start();

if(isset($_SESSION['admin']) && isset($_SESSION['login'])) {

class IndexPage extends BasePage
{
    public function __construct()
    {
        $this->title = "Prohlížeč databáze firmy";
    }

    protected function pageBody()
    {
    }

}
?>
<html xmlns="">
<head>
    <title>Home</title>
</head>
<body>
    User: <?php echo $_SESSION['name'];?></br>
    Admin: <?php echo $_SESSION['admin'];?>
</body>
</html>
<?php
$page = new IndexPage();
$page->render();
}else{
    header("Location: index.php");
    exit();
}
?>
