<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\WithFlags;
use diplomacy\modules\vestria\components\WithModifiers;

/**
 * Class Character
 *
 * Класс Персонажа
 *
 * @method Character setId( int $id )
 * @method int getId()
 * @method Character setName( string $name )
 * @method string getName()
 * @method Character setPlayerId( int $playerId )
 * @method int getPlayerId()
 * @method Character setFactionId( int $factionId )
 * @method int getFactionId()
 * @method Character setClassId( int $classId )
 * @method int getClassId()
 * @method Character setTraitId( int $traitId )
 * @method int getTraitId()
 * @method Character setAmbitionId( int $ambitionId )
 * @method int getAmbitionId()
 * @method Character setPopularity( int $popularity )
 * @method int getPopularity()
 * @method Character setCash( int $cash )
 * @method int getCash()
 * @method Character setRecruits( int $recruits )
 * @method int getRecruits()
 * @method Character setProvinceId( int $provinceId )
 * @method int getProvinceId()
 * @method Character setEstatesCount( int $count )
 * @method int getEstatesCount()
 * @method Character setFactoriesCount( int $count )
 * @method int getFactoriesCount()
 * @method Character setArmyId( int $armyId )
 * @method int getArmyId()
 *
 */
class Character extends \JSONModel implements WithFlags, WithModifiers
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
    protected $popularity = 0;
    /** @var int */
    protected $cash = 0;
    /** @var int */
    protected $recruits = 0;
    /** @var int */
    protected $provinceId;
    /** @var int */
    protected $estatesCount;
    /** @var int */
    protected $factoriesCount;
    /** @var int */
    protected $armyId;
    /** @var [] */
    protected $flags = [ ];
    /** @var Modifier[] */
    protected $modifiers = [ ];

    /** @var Game */
    private $game;
    /** @var \Users */
    private $player;
    /** @var Faction */
    private $faction;
    /** @var CharacterClass */
    private $class;
    /** @var CharacterTrait */
    private $trait;
    /** @var CharacterAmbition */
    private $ambition;
    /** @var Province */
    private $province;
    /** @var Army */
    private $army;
    /** @var Request */
    private $request;

    const DEFAULT_POPULARITY = 20;
    const DEFAULT_CASH = 50;
    const DEFAULT_RECRUITS = 0;

    /**
     * Конструктор
     *
     * @param Game $game
     * @param [] $data
     */
    public function __construct( $game, $data = [ ] )
    {
        $this->game = $game;
        if (isset( $data['modifiers'] )) {
            foreach ($data['modifiers'] as $modifierData) {
                $this->modifiers[] = new Modifier( $modifierData );
            }
            unset( $data['modifiers'] );
        }
        parent::__construct( $data );
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function hasFlag( $name )
    {
        return in_array( $name, $this->flags );
    }

    /**
     * @inheritdoc
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
        // get characters modifiers
        foreach ($this->modifiers as $modifier) {
            if ($modifier->getName() == $modifierName) {
                $modValue += $modifier->getValue();
            }
        }
        $faction = $this->getFaction();
        if ( ! empty( $faction )) {
            $modValue += $this->getFaction()->getModifier( $modifierName );
        }

        return $modValue;
    }


    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @return \Users|static
     */
    public function getPlayer()
    {
        if (empty( $this->player ) || $this->player->id != $this->playerId) {
            $this->player = \Users::model()->findByPk( $this->playerId );
        }

        return $this->player;
    }

    /**
     * @return CharacterClass
     */
    public function getClass()
    {
        if (empty( $this->class ) || $this->class->getId() != $this->classId) {
            $this->class = $this->game->getConfig()->getConfigElementById( "character_classes", $this->classId );
        }

        return $this->class;
    }

    /**
     * @return CharacterTrait
     */
    public function getTrait()
    {
        if (empty( $this->trait ) || $this->trait->getId() != $this->traitId) {
            $this->trait = $this->game->getConfig()->getConfigElementById( "character_traits", $this->traitId );
        }

        return $this->trait;
    }

    /**
     * @return CharacterAmbition
     */
    public function getAmbition()
    {
        if (empty( $this->ambition ) || $this->ambition->getId() != $this->ambitionId) {
            $this->ambition = $this->game->getConfig()->getConfigElementById( "character_ambitions",
                $this->ambitionId );
        }

        return $this->ambition;
    }

    /**
     * @return Faction
     */
    public function getFaction()
    {
        if (empty( $this->faction ) || $this->faction->getId() != $this->factionId) {
            $this->faction = $this->game->getFaction( $this->factionId );
        }

        return $this->faction;
    }

    /**
     * @return Province
     */
    public function getProvince()
    {
        if (empty( $this->province ) || $this->province->getId() != $this->provinceId) {
            $this->province = $this->game->getProvince( $this->provinceId );
        }

        return $this->province;
    }

    /**
     * @return Army
     */
    public function getArmy()
    {
        if (empty( $this->army ) || $this->army->getId() != $this->armyId) {
            $this->army = $this->game->getArmy( $this->armyId );
        }

        return $this->army;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (empty( $this->request )) {
            $this->request = $this->game->getRequestByCharacterId( $this->id );
        }

        return $this->request;
    }

    /**
     * Задает дефолтные параметры для персонажа
     *
     * @param int $id
     *
     * @return Character
     */
    public function setupAsNew( $id )
    {
        $this->setId( $id );
        $this->setCash( self::DEFAULT_CASH );
        $this->setPopularity( self::DEFAULT_POPULARITY );
        $this->setRecruits( self::DEFAULT_RECRUITS );
        $this->getClass()->applySetupEffects( $this );
        $this->getTrait()->applySetupEffects( $this );

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id"             => $this->id,
            "name"           => $this->name,
            "playerId"       => $this->playerId,
            "factionId"      => $this->factionId,
            "classId"        => $this->classId,
            "ambitionId"     => $this->ambitionId,
            "traitId"        => $this->traitId,
            "popularity"     => $this->popularity,
            "cash"           => $this->cash,
            "recruits"       => $this->recruits,
            "provinceId"     => $this->provinceId,
            "estatesCount"   => $this->estatesCount,
            "factoriesCount" => $this->factoriesCount,
            "armyId"         => $this->armyId,
            "flags"          => $this->flags,
            "modifiers"      => $this->modifiers
        ];
    }
}