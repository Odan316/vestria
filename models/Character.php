<?php

/**
 * Created by PhpStorm.
 * User: onag
 * Date: 27.12.14
 * Time: 13:24
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
    protected $ambitionId;
    /** @var int */
    protected $traitId;
    /** @var int */
    protected $popularity;
    /** @var int */
    protected $cash;


    /** @var Users */
    private $player;
    /** @var CharacterClass */
    private $class;

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
     * @return Users|static
     */
    public function getClass()
    {
        if (empty( $this->player )) {
            $this->player = Users::model()->findByPk( $this->playerId );
        }

        return $this->player;
    }
}