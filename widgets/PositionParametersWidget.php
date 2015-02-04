<?php
namespace diplomacy\modules\vestria\widgets;

use diplomacy\modules\vestria\models\Character;
use diplomacy\modules\vestria\models\CharacterAction;

/**
 * Class PositionParametersWidget
 * @package diplomacy\modules\vestria\widgets
 *
 * @method \diplomacy\modules\vestria\components\VesController getController()
 */
class PositionParametersWidget extends \CWidget
{
    /** @var CharacterAction */
    public $action;
    /** @var int */
    public $positionId;
    /** @var Character */
    public $character;

    public function init()
    {
    }

    public function run()
    {
        $request = $this->character->getRequest();
        if(is_object($request))
            $values = $request->getPosition($this->positionId)->getParameters();
        else
            $values = [];
        $this->render( "position_parameters", ["values" => $values] );
    }
}