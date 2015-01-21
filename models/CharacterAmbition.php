<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class CharacterAmbition
 *
 * Класс Амбиции персонажа (конфиг)
 */
class CharacterAmbition extends \JSONModel
{

    /** @var  int */
    protected $id;
    /** @var  string */
    protected $name;
    /** @var [] */
    protected $classes = [ ];

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
     * @return CharacterAmbition
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