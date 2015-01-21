<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class CharacterClass
 *
 * Класс Класса персонажа (конфиг)
 */
class CharacterClass extends \JSONModel
{

    /** @var  int */
    protected $id;
    /** @var  string */
    protected $name;

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
     * @return CharacterClass
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