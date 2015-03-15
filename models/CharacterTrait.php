<?php
namespace diplomacy\modules\vestria\models;

/**
 * Class CharacterTrait
 *
 * Класс Черты персонажа (конфиг)
 *
 * @method CharacterTrait setId( int $id )
 * @method int getId()
 * @method CharacterTrait setName( string $name )
 * @method string getName()
 *
 * @method Condition[] getTakeConditions()
 * @method Effect[] getSetupEffects()
 * @method Effect[] getOnTurnEffects()
 *
 */

class CharacterTrait extends \JSONModel
{

    /** @var  int */
    protected $id;
    /** @var  string */
    protected $name;
    /** @var Condition[] */
    protected $takeConditions = [ ];
    /** @var Effect[] */
    protected $setupEffects = [ ];
    /** @var Effect[] */
    protected $onTurnEffects = [ ];

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
        if (isset( $data['setupEffects'] )) {
            foreach ($data['setupEffects'] as $effectData) {
                $this->setupEffects[] = new Effect( $effectData );
            }
            unset( $data['setupEffects'] );
        }
        if (isset( $data['onTurnEffects'] )) {
            foreach ($data['onTurnEffects'] as $effectData) {
                $this->onTurnEffects[] = new Effect( $effectData );
            }
            unset( $data['onTurnEffects'] );
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
     * @param Character $character
     *
     * @return void
     */
    public function applySetupEffects( $character )
    {
        foreach ($this->setupEffects as $effect) {
            $effect->apply( $character->getGame(), [ "characterId" => $character->getId() ] );
        }
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