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
    public function __constructor( $game, $data = [ ] )
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
     * @return Character|null
     */
    public function getLeader()
    {
        if (empty( $this->leader )) {
            $this->leader = $this->game->getCharacter( $this->leaderId );
        }

        return $this->leader;
    }
}