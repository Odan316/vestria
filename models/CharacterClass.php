<?php
namespace diplomacy\modules\vestria\models;

/**
 * Class CharacterClass
 *
 * Класс Класса персонажа (конфиг)
 *
 * @method CharacterClass setId( int $id )
 * @method int getId()
 * @method CharacterClass setName( string $name )
 * @method string getName()
 *
 * @method Effect[] getSetupEffects()
 *
 */
class CharacterClass extends \JSONModel
{
    const CLASS_ARISTOCRAT = 1;
    const CLASS_OFFICER = 2;
    const CLASS_CAPITALIST = 3;
    const CLASS_MOB_LEADER = 4;
    const CLASS_PRIEST = 5;

    /** @var  int */
    protected $id;
    /** @var  string */
    protected $name;
    /** @var Effect[] */
    protected $setupEffects = [ ];

    /**
     * @inheritdoc
     */
    public function setAttributes( $data )
    {
        if (isset( $data['setupEffects'] )) {
            foreach ($data['setupEffects'] as $effectData) {
                $this->setupEffects[] = new Effect( $effectData );
            }
            unset( $data['setupEffects'] );
        }
        parent::setAttributes( $data );
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
}