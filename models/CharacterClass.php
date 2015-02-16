<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class CharacterClass
 *
 * Класс Класса персонажа (конфиг)
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
    protected $setupEffects = [];

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
     * @param $character
     *
     * @return void
     */
    public function applySetupEffects($character)
    {
        foreach($this->setupEffects as $effect) {
            $effect->apply($game, []);
        }
    }
}