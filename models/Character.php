<?php
/**
 * Created by PhpStorm.
 * User: onag
 * Date: 27.12.14
 * Time: 13:24
 */

class Character extends JSONModel {

    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var int */
    private $classId;
    /** @var  int */
    private $ambitionId;
    /** @var  int */
    private $traitId;
    /** @var  int */
    private $popularity;
    /** @var  int */
    private $cash;
    /** @var  int */
    private $factionId;

    /** @var int */
    private $playerId;

    /** @var Users */
    private $player;
    /** @var  CharacterClass */
    private $class;

    public function getName()
    {
        return $this->name;
    }

    public function getPlayer()
    {
        if(empty($this->player))
            $this->player = Users::model()->findByPk($this->playerId);

        return $this->player;
    }

    public function getClass()
    {
        if(empty($this->player))
            $this->player = Users::model()->findByPk($this->playerId);

        return $this->player;
    }
}