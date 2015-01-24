<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\PlayerActionHandler;
/**
 * Class RequestPosition
 *
 * Класс отдельного пункта заявки игрока
 */
class RequestPosition extends \JSONModel
{
    /** @var int */
    protected $id;
    /** @var int */
    protected $actionId;

    /** @var [] */
    protected $parameters;

    /** @var Request */
    protected $request;
    /** @var PlayerAction */
    protected $action;

    /**
     * @param Request $request
     * @param [] $data
     */
    public function __construct( $request, $data = [ ] )
    {
        $this->request = $request;
        parent::__construct( $data );
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getActionId()
    {
        return $this->actionId;
    }
    /**
     * @param int $actionId
     *
     * @return RequestPosition
     */
    public function setActionId($actionId)
    {
        $this->actionId = $actionId;
        return $this;
    }

    /**
     * @return PlayerAction|null
     */
    public function getAction()
    {
        if (empty( $this->action )) {
            $this->action = $this->request->getGame()->getConfig()->getConfigElementById('player_actions', $this->actionId );
        }
        return $this->action;
    }

    /**
     * @return []
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getParametersCode()
    {
        return (new PlayerActionHandler($this->request->getGame(), $this->request->getCharacter()))->getParametersCode($this->getAction(), $this->getParameters());
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "actionId" => $this->actionId,
            "parameters"   => $this->parameters
        ];
    }
}