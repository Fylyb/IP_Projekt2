<?php
require_once __DIR__ . "/../../bootstrap/bootstrap.php";

session_start();
if ($_SESSION['name'] === null){
    header("Location: ../index.php");
}
class EmployeeUpdatePage extends CRUDPage
{
    private ?Staff $employee;
    private ?array $errors = [];
    private int $state;

    protected function prepare(): void
    {
        parent::prepare();
        $this->findState();
        $this->title = "Upravit zaměstnance";

        //když chce formulář
        if ($this->state === self::STATE_FORM_REQUESTED)
        {
            $employeeId = filter_input(INPUT_GET, 'employeeId', FILTER_VALIDATE_INT);
            if (!$employeeId)
                throw new BadRequestException();

            //jdi dál

            $this->employee = Staff::findByID($employeeId);
            if (!$this->employee)
                throw new NotFoundException();

            $stmt=PDOProvider::get()->query("SELECT `name` roomName, room_id roomId FROM room");
            $this->keys=$stmt->fetchAll();
        }

        //když poslal data
        elseif($this->state === self::STATE_DATA_SENT) {
            //načti je
            $this->employee = Staff::readPost();

            //zkontroluj je, jinak formulář
            $this->errors = [];
            $this->keys = filter_input(INPUT_POST, 'keys', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
            $isOk = $this->employee->validate($this->errors);
            if (!$isOk)
            {
                $this->state = self::STATE_FORM_REQUESTED;
            }
            else
            {
                $success = $this->employee->update();
                if($success){

                $stmt = PDOProvider::get()->prepare("DELETE FROM `key` WHERE `key`.employee = :employeeId");
                $stmt->execute(['employeeId' => $this->employee->employee_id]);
                foreach($this->keys as $key) {
                    $stmt = PDOProvider::get()->prepare("INSERT INTO `key` (employee, room) VALUES (:employeeId, :roomId)");
                    $success = $stmt->execute(['employeeId' => $this->employee->employee_id, 'roomId' => $key]);
                    if(!$success)
                        break;
                }
                }


                //ulož je
                //$success = $this->room->update();


                //přesměruj
                $this->redirectStaff(self::ACTION_UPDATE, $success);
            }
        }
    }

    protected function pageBody()
    {
        return MustacheProvider::get()->render(
            'employeeForm',
            [
                'employee' => $this->employee,
                'errors' => $this->errors,
                'keys' => $this->keys
            ]
        );
    }

    private function findState() : void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            $this->state = self::STATE_DATA_SENT;
        else
            $this->state = self::STATE_FORM_REQUESTED;
    }

}

$page = new EmployeeUpdatePage();
$page->render();

?>