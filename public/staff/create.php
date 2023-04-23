<?php
require_once __DIR__ . "/../../bootstrap/bootstrap.php";

session_start();
if ($_SESSION['name'] === null){
    header("Location: ../index.php");
}
class EmployeeCreatePage extends CRUDPage
{

    private ?Staff $employee;
    private ?array $errors = [];
    private int $state;

    protected function prepare(): void
    {

        parent::prepare();
        $this->findState();
        $this->title = "Založit novou osobu";

        //když chce formulář
        if ($this->state === self::STATE_FORM_REQUESTED)
        {
            //jdi dál
            $this->employee = new Staff();
            $stmt=PDOProvider::get()->query("SELECT `name` roomName, room_id roomId, `no` FROM room JOIN `key` WHERE `key`.key_id = room_id");
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
                //ulož je
                $success = $this->employee->insert();
                foreach($this->keys as $key) {
                    $stmt = PDOProvider::get()->prepare("INSERT INTO `key` (employee, room) VALUES (:employeeId, :roomId)");
                    $success = $stmt->execute(['employeeId' => $this->employee->employee_id, 'roomId' => $key]);
                }

                //přesměruj
                $this->redirectStaff(self::ACTION_INSERT, $success);
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
$page = new EmployeeCreatePage();
$page->render();

?>
