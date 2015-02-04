<?php
namespace diplomacy\modules\vestria\models;

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
    /** @var CharacterAction */
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
     * @return CharacterAction|null
     */
    public function getAction()
    {
        if (empty( $this->action )) {
            $this->action = $this->request->getGame()->getConfig()->getConfigElementById('character_actions', $this->actionId );
        }
        return $this->action;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return []
     */
    public function getParameters()
    {
        return $this->parameters;
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