<?php
require_once __DIR__ . "/../../bootstrap/bootstrap.php";

session_start();
if ($_SESSION['name'] === null){
    header("Location: ../index.php");
}
class EmployeeDeletePage extends CRUDPage
{

    protected function prepare(): void
    {
        parent::prepare();

        $employeeId = filter_input(INPUT_POST, 'employeeId', FILTER_VALIDATE_INT);
        if (!$employeeId)
            throw new BadRequestException();

        $stmt = PDOProvider::get()->prepare("DELETE FROM `key` WHERE `key`.employee = :employeeId");
        $stmt->execute(['employeeId' => $employeeId]);
        //když poslal data
        $success = Staff::deleteByID($employeeId);

        //přesměruj
        $this->redirectStaff(self::ACTION_DELETE, $success);
    }

    protected function pageBody()
    {
        return "";
    }

}

$page = new EmployeeDeletePage();
$page->render();

?>