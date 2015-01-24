<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class PlayerAction
 *
 * Класс действия игрока
 */
class PlayerAction extends \JSONModel
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $name;
    /** @var [] */
    protected $conditions;
    /** @var [] */
    protected $parameters;
    /** @var [] */
    protected $effects;

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
     * @return string PlayerAction
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
     * @return []
     */
    public function getConditions()
    {
        return $this->conditions;
    }
    /**
     * @return []
     */
    public function getParameters()
    {
        return $this->parameters;
    }
    /**
     * @return []
     */
    public function getEffects()
    {
        return $this->effects;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'conditions' => $this->conditions,
            'parameters' => $this->parameters,
            'effects' => $this->effects
        ];
    }
}