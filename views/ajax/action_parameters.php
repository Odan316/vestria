<?php
use diplomacy\modules\vestria\controllers\AjaxController;
use diplomacy\modules\vestria\models\Character;
use diplomacy\modules\vestria\models\CharacterAction;
/**
 * @var $this AjaxController
 * @var $character Character
 * @var $action CharacterAction
 */
?>

<?= $this->widget( "diplomacy\\modules\\vestria\\widgets\\PositionParametersWidget",
    ["action" => $action, "positionId" => 0, "character" => $character], true ) ?>