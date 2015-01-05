<?php

/**
 * Class Character
 *
 * Класс "Персонаж"
 */
class Character extends JSONModel
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $name;
    /** @var int */
    protected $playerId;
    /** @var int */
    protected $factionId;
    /** @var int */
    protected $classId;
    /** @var int */
    protected $traitId;
    /** @var int */
    protected $ambitionId;
    /** @var int */
    protected $popularity;
    /** @var int */
    protected $cash;

    /** @var Game */
    private $game;
    /** @var Users */
    private $player;
    /** @var Faction */
    private $faction;
    /** @var CharacterClass */
    private $class;
    /** @var CharacterTrait */
    private $trait;
    /** @var CharacterAmbition */
    private $ambition;

    /**
     * Конструктор
     *
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
     * @param $name
     *
     * @return Game
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
     * @param int $playerId
     *
     * @return Character
     */
    public function setPlayerId( $playerId )
    {
        $this->playerId = $playerId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @param int $factionId
     *
     * @return Character
     */
    public function setFactionId( $factionId )
    {
        $this->factionId = $factionId;
        return $this;
    }

    /**
     * @return int
     */
    public function getFactionId()
    {
        return $this->factionId;
    }

    /**
     * @param int $classId
     *
     * @return Character
     */
    public function setClassId( $classId )
    {
        $this->classId = $classId;
        return $this;
    }

    /**
     * @return int
     */
    public function getClassId()
    {
        return $this->classId;
    }

    /**
     * @param int $traitId
     *
     * @return Character
     */
    public function setTraitId( $traitId )
    {
        $this->traitId = $traitId;
        return $this;
    }

    /**
     * @return int
     */
    public function getTraitId()
    {
        return $this->traitId;
    }

    /**
     * @param int $ambitionId
     *
     * @return Character
     */
    public function setAmbitionId( $ambitionId )
    {
        $this->ambitionId = $ambitionId;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmbitionId()
    {
        return $this->ambitionId;
    }

    /**
     * @param int $popularity
     *
     * @return Character
     */
    public function setPopularity( $popularity )
    {
        $this->popularity = $popularity;
        return $this;
    }

    /**
     * @return int
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * @param int $cash
     *
     * @return Character
     */
    public function setCash( $cash )
    {
        $this->cash = $cash;
        return $this;
    }

    /**
     * @return int
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * @return Users|static
     */
    public function getPlayer()
    {
        if (empty( $this->player )) {
            $this->player = Users::model()->findByPk( $this->playerId );
        }

        return $this->player;
    }

    /**
     * @return CharacterClass
     */
    public function getClass()
    {
        if (empty( $this->class )) {
            $data        = $this->game->getConfig()->getConfigElementById( "character_classes", $this->classId );
            $this->class = new CharacterClass( $data );
        }

        return $this->class;
    }

    /**
     * @return CharacterTrait
     */
    public function getTrait()
    {
        if (empty( $this->trait )) {
            $data        = $this->game->getConfig()->getConfigElementById( "character_traits", $this->traitId );
            $this->trait = new CharacterTrait( $data );
        }

        return $this->trait;
    }

    /**
     * @return CharacterAmbition
     */
    public function getAmbition()
    {
        if (empty( $this->ambition )) {
            $data           = $this->game->getConfig()->getConfigElementById( "character_ambitions",
                $this->ambitionId );
            $this->ambition = new CharacterAmbition( $data );
        }

        return $this->ambition;
    }

    /**
     * @return Faction
     */
    public function getFaction()
    {
        if (empty( $this->faction )) {
            $this->faction = $this->game->getFaction( $this->factionId );
        }

        return $this->faction;
    }

    /**
     * Задает дефолтные параметры для персонажа
     *
     * @param int $id
     *
     * @return $this
     */
    public function setupAsNew( $id )
    {
        $this->id         = $id;
        $this->cash       = 50;
        $this->popularity = 20;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id"         => $this->id,
            "name"       => $this->name,
            "playerId"   => $this->playerId,
            "factionId"  => $this->factionId,
            "classId"    => $this->classId,
            "ambitionId" => $this->ambitionId,
            "traitId"    => $this->traitId,
            "popularity" => $this->popularity,
            "cash"       => $this->cash
        ];
    }
}