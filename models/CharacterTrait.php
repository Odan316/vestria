<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class CharacterTrait
 *
 * Класс Черты персонажа (конфиг)
 */

class CharacterTrait extends \JSONModel {

    /** @var  int */
    protected $id;
    /** @var  string */
    protected $name;
    /** @var Condition[] */
    protected $takeConditions = [ ];

    /**
     * @inheritdoc
     */
    public function setAttributes( $data )
    {
        if (isset( $data['takeConditions'] )) {
            foreach ($data['takeConditions'] as $conditionData) {
                $this->takeConditions[] = new Condition( $conditionData );
            }
            unset( $data['takeConditions'] );
        }
        parent::setAttributes( $data );
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Character $character
     *
     * @return bool
     */
    public function canTake( $character )
    {
        $test = true;
        foreach ($this->takeConditions as $condition) {
            if ( ! $condition->test( $character )) {
                $test = false;
                break;
            }
        }
        return $test;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id"   => $this->id,
            "name" => $this->name
        ];
    }
}