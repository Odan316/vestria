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
    private $class;
    private $ambition;
    private $trait;
    private $popularity;
    private $cash;
    private $faction;

    /** @var int */
    private $player_id;

    /** @var Users */
    private $player;

    public function getName()
    {
        return $this->name;
    }

    public function getPlayer()
    {
        if(empty($this->player))
            $this->player = Users::model()->findByPk($this->player_id);

        return $this->player;
    }
}