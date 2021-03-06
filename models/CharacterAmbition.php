<?php
namespace diplomacy\modules\vestria\models;

/**
 * Class CharacterAmbition
 *
 * Класс Амбиции персонажа (конфиг)
 *
 * @method CharacterAmbition setId( int $id )
 * @method int getId()
 * @method CharacterAmbition setName( string $name )
 * @method string getName()
 * @method CharacterAmbition setMakeLeader( bool $bool )
 * @method string getMakeLeader()
 *
 * @method Condition[] getTakeConditions()
 * @method Condition[] getMeetConditions()
 *
 */
class CharacterAmbition extends \JSONModel
{

    /** @var  int */
    protected $id;
    /** @var  string */
    protected $name;
    /** @var bool */
    protected $makeLeader = false;
    /** @var Condition[] */
    protected $takeConditions = [ ];
    /** @var Condition[] */
    protected $meetConditions = [ ];

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
        if (isset( $data['meetConditions'] )) {
            foreach ($data['meetConditions'] as $conditionData) {
                $this->meetConditions[] = new Condition( $conditionData );
            }
            unset( $data['meetConditions'] );
        }
        parent::setAttributes( $data );
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