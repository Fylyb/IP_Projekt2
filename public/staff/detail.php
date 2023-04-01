<?php
require_once __DIR__ . "/../../bootstrap/bootstrap.php";

session_start();
if ($_SESSION['name'] === null){
    header("Location: ../index.php");
}
class EmployeeDetailPage extends BasePage
{
    private $employee;
    private $rooms;

    protected function prepare(): void
    {
        parent::prepare();
        //získat data z GET
        $employeeId = filter_input(INPUT_GET, 'employeeId', FILTER_VALIDATE_INT);
        if (!$employeeId)
            throw new BadRequestException();

        //najít osobu v databázi
        $this->employee = Staff::findByID($employeeId);
        if (!$this->employee)
            throw new NotFoundException();


        $stmt = PDOProvider::get()->prepare("SELECT r.room_id, r.no, r.name AS roomName FROM room AS r JOIN employee AS e ON r.room_id = e.room WHERE e.employee_id= :employeeId ORDER BY e.name, e.surname");
        $stmt->execute(['employeeId' => $employeeId]);
        $this->rooms = $stmt->fetchAll();

        $stmtKey = PDOProvider::get()->prepare("SELECT *, room.name AS roomName FROM room JOIN `key` ON room.room_id=`key`.room WHERE `key`.employee=:employeeId");
        $stmtKey->execute(['employeeId' => $employeeId]);
        $this->keys = $stmtKey->fetchAll();

        $this->title = "Detail osoby {$this->employee->surname}";

    }

    protected function pageBody()
    {
        //prezentovat data
        return MustacheProvider::get()->render(
            'employeeDetail',
            ['employee' => $this->employee, 'rooms' => $this->rooms, 'keys' => $this->keys]
        );
    }

}

$page = new EmployeeDetailPage();
$page->render();

?>