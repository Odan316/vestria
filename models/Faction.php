<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\WithFlags;
use diplomacy\modules\vestria\components\WithModifiers;

/**
 * Class Faction
 *
 * Класс фракции
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
    private $flags = [];
    /** @var Modifier[] */
    private $modifiers = [];

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
     * @param string $name
     *
     * @return string Faction
     */
    public function setName( $name )
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $characterId
     *
     * @return Faction
     */
    public function setLeaderId($characterId)
    {
        $this->leaderId = $characterId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLeaderId()
    {
        return $this->leaderId;
    }

    /**
     * @param string $color
     *
     * @return Faction
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @return Character|null
     */
    public function getLeader()
    {
        if (empty( $this->leader )) {
            $this->leader = $this->game->getCharacter( $this->leaderId );
        }

        return $this->leader;
    }

    /**
     * Задает дефолтные параметры для персонажа
     *
     * @return Faction
     */
    public function setupAsNew( )
    {
        return $this;
    }
    /**
     * @param string $name
     * @param bool $value
     */
    public function setFlag($name, $value)
    {
        $this->flags[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasFlag($name)
    {
        return (isset($this->flags[$name]) && $this->flags[$name] == true);
    }

    /**
     * @inheritdoc
     */
    public function setModifier($modifier)
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
        foreach($this->modifiers as $modifier){
            if($modifier->getName() == $modifierName)
                $modValue += $modifier->getValue();
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

    protected function onAttributeChange($attributeName, $oldValue, $newValue)
    {
        switch($attributeName) {
            case 'leaderId':
                $newLeader = $this->game->getCharacter( $newValue );
                $newLeader->setFactionId( $this->id );
                break;
            default:
                break;
        }
    }
}