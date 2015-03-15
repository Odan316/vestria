<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\WithFlags;
use diplomacy\modules\vestria\components\WithModifiers;

/**
 * Class Faction
 *
 * Класс фракции
 *
 * @method Faction setId( int $id )
 * @method int getId()
 * @method Faction setName( string $name )
 * @method string getName()
 * @method Faction setLeaderId( int $leaderId )
 * @method int getLeaderId()
 * @method Faction setColor( string $colorHexCode )
 * @method string getColor()
 */
class Faction extends \JSONModel implements WithFlags, WithModifiers
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $name;
    /** @var int */
    protected $leaderId;
    /** @var string */
    protected $color;

    /** @var Game */
    private $game;
    /** @var Character */
    private $leader;

    /** @var [] */
    private $flags = [ ];
    /** @var Modifier[] */
    private $modifiers = [ ];

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
    public function getLeader()
    {
        if (empty( $this->leader ) || $this->leader->getId() != $this->leaderId) {
            $this->leader = $this->game->getCharacter( $this->leaderId );
        }

        return $this->leader;
    }

    /**
     * Задает дефолтные параметры для персонажа
     *
     * @return Faction
     */
    public function setupAsNew()
    {
        return $this;
    }

    /**
     * @param string $name
     */
    public function setFlag( $name )
    {
        foreach ($this->flags as $key => $flagName) {
            if ($flagName == $name) {
                return;
            }
        }
        $this->flags[] = $name;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasFlag( $name )
    {
        return in_array( $name, $this->flags );
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function removeFlag( $name )
    {
        foreach ($this->flags as $key => $flagName) {
            if ($flagName == $name) {
                unset( $this->flags[$key] );
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function setModifier( $modifier )
    {
        $this->modifiers[] = $modifier;
    }

    /**
     * @inheritdoc
     */
    public function getModifier( $modifierName )
    {
        $modValue = 0;
        // get faction modifiers
        foreach ($this->modifiers as $modifier) {
            if ($modifier->getName() == $modifierName) {
                $modValue += $modifier->getValue();
            }
        }

        return $modValue;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id"       => $this->id,
            "name"     => $this->name,
            "leaderId" => $this->leaderId,
            "color"    => $this->color
        ];
    }

    /**
     * @inheritdoc
     */
    protected function onAttributeChange( $attributeName, $oldValue, $newValue )
    {
        switch ($attributeName) {
            case 'leaderId':
                $newLeader = $this->game->getCharacter( $newValue );
                $newLeader->setFactionId( $this->id );
                break;
            default:
                break;
        }
    }
}