<?php

/**
 * Class Army
 *
 * Класс армии
 */
class Army extends JSONModel
{

    /** @var int */
    protected $id;

    /** @var Game */
    protected $game;

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
}