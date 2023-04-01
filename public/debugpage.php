<?php
require_once __DIR__ . "/../bootstrap/bootstrap.php";

class DebugPage extends BasePage
{
    private $room;

    protected function pageBody()
    {
        $errors = [
            'no' => 'pole je povinné',
            'name' => 'jméno nesmí obsahovat emotikony'
        ];
        //prezentovat data
        return MustacheProvider::get()->render(
            'roomForm',
            [
                'room' => $this->room,
                'errors' => $errors
            ]
        );
    }

}

$page = new DebugPage();
$page->render();

?>
