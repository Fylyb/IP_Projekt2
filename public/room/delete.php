<?php
require_once __DIR__ . "/../../bootstrap/bootstrap.php";
session_start();
if ($_SESSION['name'] === null){
    header("Location: ../index.php");
}
class RoomDeletePage extends CRUDPage
{

    protected function prepare(): void
    {
        parent::prepare();

        $roomId = filter_input(INPUT_POST, 'roomId', FILTER_VALIDATE_INT);
        if (!$roomId)
            throw new BadRequestException();


        $stmt = PDOProvider::get()->prepare("DELETE FROM `key` WHERE `key`.room = :roomId");
        $stmt->execute(['roomId' => $roomId]);
        //když poslal data
        $success = Room::deleteByID($roomId);

        //přesměruj
        $this->redirect(self::ACTION_DELETE, $success);
    }

    protected function pageBody()
    {
        return "";
    }

}

$page = new RoomDeletePage();
$page->render();

?>