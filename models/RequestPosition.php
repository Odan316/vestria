<?php
namespace diplomacy\modules\vestria\models;

/**
 * Class RequestPosition
 *
 * Класс отдельного пункта заявки игрока
 *
 * @method RequestPosition setId( int $id )
 * @method int getId()
 * @method Request setActionId( int $actionId )
 * @method int getActionId()
 *
 * @method [] getParameters()
 */
class RequestPosition extends \JSONModel
{
    /** @var int */
    protected $id;
    /** @var int */
    protected $actionId;
    /** @var [] */
    protected $parameters = [];

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

    /**
     *
     */
    public function apply()
    {
        $this->getAction()->applyEffects($this->getRequest()->getCharacter()->getGame(), $this->parameters);
    }

    /**
     * @param int $characterId
     *
     * @return bool
     */
    public function checkFactionRequestAccept($characterId)
    {
        return $this->getAction()->checkFactionRequestAccept($characterId, $this->parameters);
    }
}