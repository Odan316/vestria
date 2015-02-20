<?php
namespace diplomacy\modules\vestria\models;

use diplomacy\modules\vestria\components\WithFlags;
use diplomacy\modules\vestria\components\WithModifiers;
/**
 * Class Character
 *
 * Класс "Персонаж"
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
    protected $popularity;
    /** @var int */
    protected $cash;
    /** @var int */
    protected $provinceId;
    /** @var int */
    protected $estatesCount;
    /** @var int */
    protected $factoriesCount;
    /** @var int */
    protected $armyId;
    /** @var [] */
    protected $flags = [];
    /** @var Modifier[] */
    protected $modifiers = [];

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
     * @param null|int $factionId
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
     * @param int $provinceId
     *
     * @return Character
     */
    public function setProvinceId( $provinceId )
    {
        $this->provinceId = $provinceId;
        return $this;
    }

    /**
     * @return int
     */
    public function getProvinceId()
    {
        return $this->provinceId;
    }

    /**
     * @param int $count
     *
     * @return Character
     */
    public function setEstatesCount( $count )
    {
        $this->estatesCount = $count;
        return $this;
    }

    /**
     * @return int
     */
    public function getEstatesCount()
    {
        return $this->estatesCount;
    }

    /**
     * @param int $count
     *
     * @return Character
     */
    public function setFactoriesCount( $count )
    {
        $this->factoriesCount = $count;
        return $this;
    }

    /**
     * @return int
     */
    public function getFactoriesCount()
    {
        return $this->factoriesCount;
    }

    /**
     * @param int $armyId
     *
     * @return Character
     */
    public function setArmyId( $armyId )
    {
        $this->armyId = $armyId;
        return $this;
    }

    /**
     * @return int
     */
    public function getArmyId()
    {
        return $this->armyId;
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
        if (empty( $this->player )) {
            $this->player = \Users::model()->findByPk( $this->playerId );
        }

        return $this->player;
    }

    /**
     * @return CharacterClass
     */
    public function getClass()
    {
        if (empty( $this->class )) {
            $this->class        = $this->game->getConfig()->getConfigElementById( "character_classes", $this->classId );
        }

        return $this->class;
    }

    /**
     * @return CharacterTrait
     */
    public function getTrait()
    {
        if (empty( $this->trait )) {
            $this->trait        = $this->game->getConfig()->getConfigElementById( "character_traits", $this->traitId );
        }

        return $this->trait;
    }

    /**
     * @return CharacterAmbition
     */
    public function getAmbition()
    {
        if (empty( $this->ambition )) {
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
        if (empty( $this->faction )) {
            $this->faction = $this->game->getFaction( $this->factionId );
        }

        return $this->faction;
    }

    /**
     * @return Province
     */
    public function getProvince()
    {
        if (empty( $this->province )) {
            $this->province = $this->game->getProvince( $this->provinceId );
        }

        return $this->province;
    }

    /**
     * @return Army
     */
    public function getArmy()
    {
        if (empty( $this->army )) {
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
        $this->id         = $id;
        $this->cash       = 50;
        $this->popularity = 20;
        $this->getClass()->applySetupEffects($this);
        $this->getTrait()->applySetupEffects($this);
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
            "provinceId"     => $this->provinceId,
            "estatesCount"   => $this->estatesCount,
            "factoriesCount" => $this->factoriesCount,
            "armyId"         => $this->armyId,
            "flags"          => $this->flags,
            "modifiers"      => $this->modifiers
        ];
    }
}