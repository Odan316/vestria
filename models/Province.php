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
}