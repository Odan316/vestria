<?php
/**
 * Class CharacterTrait
 *
 * Класс Черты персонажа (конфиг)
 */

class CharacterTrait extends JSONModel {

    /** @var  int */
    protected $id;
    /** @var  string */
    protected $name;
    /** @var [] */
    protected $classes = [];

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
     * @return CharacterTrait
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