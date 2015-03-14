<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class Army
 *
 * Класс армии
 */
class Army extends \JSONModel
{

    /** @var int */
    protected $id;
    /** @var string */
    protected $name;
    /** @var int */
    protected $officerId;
    /** @var int */
    protected $strength;
    /** @var int */
    protected $morale = 1;

    /** @var Game */
    protected $game;
    /** @var Character */
    protected $officer;

    /**
     * @param Game $game
     * @param [] $data
     */
    public function __construct( $game, $data = [ ] )
    {
        $this->game = $game;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getOfficerId()
    {
        return $this->officerId;
    }

    /**
     * @return Character|null
     */
    public function getOfficer()
    {
        if (empty( $this->officer )) {
            $this->officer = $this->game->getCharacter( $this->officerId );
        }

        return $this->officer;
    }

    /**
     * @return int
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @return int
     */
    public function getMorale()
    {
        return $this->morale;
    }

    /**
     * Задает дефолтные параметры для персонажа
     *
     * @return Army
     */
    public function setupAsNew( )
    {
        $this->morale = 50;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "officerId" => $this->officerId,
            "strength" => $this->strength,
            "morale" => $this->morale
        ];
    }

    /**
     * @inheritdoc
     */
    protected function onAttributeChange($attributeName, $oldValue, $newValue)
    {
        switch($attributeName) {
            case 'officerId':
                $newLeader = $this->game->getCharacter( $newValue );
                $newLeader->setArmyId( $this->id );
                $oldLeader = $this->game->getCharacter( $oldValue );
                if(is_object($oldLeader))
                    $oldLeader->setArmyId( 0 );
                break;
            default:
                break;
        }
    }

}