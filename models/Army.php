<?php
namespace diplomacy\modules\vestria\models;

/**
 * Class Army
 *
 * Класс армии
 *
 * @method Army setId( int $id )
 * @method int getId()
 * @method Army setName( string $name )
 * @method string getName()
 * @method Army setOfficerId( int $officerId )
 * @method int getOfficerId()
 * @method Army setStrength( int $strength )
 * @method int getStrength()
 * @method Army setMorale( int $morale )
 * @method int getMorale()
 *
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

    const DEFAULT_MORALE = 50;

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
     * @return Character|null
     */
    public function getOfficer()
    {
        if (empty( $this->officer ) || $this->officer->getId() != $this->officerId) {
            $this->officer = $this->game->getCharacter( $this->officerId );
        }

        return $this->officer;
    }

    /**
     * Задает дефолтные параметры для персонажа
     *
     * @return Army
     */
    public function setupAsNew()
    {
        $this->setMorale(self::DEFAULT_MORALE);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id"        => $this->id,
            "name"      => $this->name,
            "officerId" => $this->officerId,
            "strength"  => $this->strength,
            "morale"    => $this->morale
        ];
    }

    /**
     * @inheritdoc
     */
    protected function onAttributeChange( $attributeName, $oldValue, $newValue )
    {
        switch ($attributeName) {
            case 'officerId':
                $newLeader = $this->game->getCharacter( $newValue );
                $newLeader->setArmyId( $this->id );
                $oldLeader = $this->game->getCharacter( $oldValue );
                if (is_object( $oldLeader )) {
                    $oldLeader->setArmyId( 0 );
                }
                break;
            default:
                break;
        }
    }

}