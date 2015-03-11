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


}