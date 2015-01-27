<?php
namespace diplomacy\modules\vestria\widgets;

use diplomacy\modules\vestria\models\PlayerAction;
use diplomacy\modules\vestria\models\RequestPosition;

/**
 * Class RequestPositionWidget
 * @package diplomacy\modules\vestria\widgets
 *
 * @method \diplomacy\modules\vestria\components\VesController getController()
 */
class RequestPositionWidget extends \CWidget
{
    /** @var PlayerAction[] */
    public $actions = [];
    /** @var RequestPosition */
    public $position = null;
    /** @var int */
    public $i = 0;

    public function init()
    {
    }

    public function run()
    {
        $this->render( "request_position" );
    }


}