<?php
namespace diplomacy\modules\vestria\components;

use diplomacy\modules\vestria\models\PlayerAction;
use diplomacy\modules\vestria\models\RequestPosition;

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