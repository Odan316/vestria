<?php

/**
 * Class Faction
 *
 * Класс фракции
 */
class Faction extends JSONModel
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $name;
    /** @var int */
    protected $leaderId;

    /** @var Game */
    protected $game;
    /** @var Character */
    protected $leader;

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
     * @param int $id
     *
     * @return Faction
     */
    public function setupAsNew( $id )
    {
        $this->id = $id;
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
            "leaderId"   => $this->leaderId
        ];
    }
}