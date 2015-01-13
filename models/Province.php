<?php

/**
 * Class Province
 *
 * Класс провинции
 */
class Province extends JSONModel
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $name;
    /** @var int */
    protected $ownerId;

    /** @var Game */
    private $game;
    /** @var Faction */
    private $owner;

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
     * @return Province
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
     * @param int $factionId
     *
     * @return Province
     */
    public function setOwnerId($factionId)
    {
        $this->ownerId = $factionId;
        return $this;
    }
    /**
     * @return int
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @return Faction
     */
    public function getOwner()
    {
        if (empty( $this->owner )) {
            $this->owner = $this->game->getFaction( $this->ownerId );
        }

        return $this->owner;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id"      => $this->id,
            "name"    => $this->name,
            "ownerId" => $this->ownerId
        ];
    }
}