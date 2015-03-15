<?php
namespace diplomacy\modules\vestria\models;

/**
 * Class CharacterAction
 *
 * Класс действия игрока (конфиг)
 *
 * @method CharacterAction setId( int $id )
 * @method int getId()
 * @method CharacterAction setName( string $name )
 * @method string getName()
 * @method CharacterAction setType( int $type )
 * @method string getType()
 * @method CharacterAction setPhase( int $phase )
 * @method string getPhase()
 *
 * @method Condition[] getConditions()
 * @method Parameter[] getParameters()
 * @method Effect[] getEffects()
 *
 */
class CharacterAction extends \JSONModel
{
    const PHASE_COMMON = 1;
    const PHASE_SPENDING = 2;
    const PHASE_MANEUVRES = 3;
    const PHASE_AFTER_MANEUVRES = 3;

    const TYPE_COMMON = 1;
    const TYPE_FACTION = 2;
    const TYPE_CHARACTER = 3;
    const TYPE_ARMY = 4;

    /** @var int */
    protected $id;
    /** @var string */
    protected $name;
    /** @var int */
    protected $type;
    /** @var int */
    protected $phase;
    /** @var Condition[] */
    protected $conditions = [ ];
    /** @var Parameter [] */
    protected $parameters = [ ];
    /** @var Effect[] */
    protected $effects = [ ];

    /**
     * @inheritdoc
     */
    public function setAttributes( $data )
    {
        if (isset( $data['conditions'] )) {
            foreach ($data['conditions'] as $conditionData) {
                $this->conditions[] = new Condition( $conditionData );
            }
            unset( $data['conditions'] );
        }
        if (isset( $data['parameters'] )) {
            foreach ($data['parameters'] as $parameterData) {
                $this->parameters[] = new Parameter( $parameterData );
            }
            unset( $data['parameters'] );
        }
        if (isset( $data['effects'] )) {
            foreach ($data['effects'] as $effectData) {
                $this->effects[] = new Effect( $effectData );
            }
            unset( $data['effects'] );
        }
        parent::setAttributes( $data );
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return [
            self::TYPE_COMMON    => "Общие",
            self::TYPE_CHARACTER => "Персонаж",
            self::TYPE_FACTION   => "Фракция",
            self::TYPE_ARMY      => "Армия"
        ];
    }

    /**
     * @param Character $character
     *
     * @return bool
     */
    public function canUse( $character )
    {
        $test = true;
        foreach ($this->conditions as $condition) {
            if ( ! $condition->test( $character )) {
                $test = false;
                break;
            }
        }

        return $test;
    }

    /**
     * @param Game $game
     * @param [] $parameters
     *
     * @return void
     */
    public function applyEffects( $game, $parameters )
    {
        foreach ($this->effects as $effect) {
            $effect->apply( $game, $parameters );
        }
    }

    /**
     * @param int $characterId
     * @param [] $parameters
     *
     * @return bool
     */
    public function checkFactionRequestAccept( $characterId, $parameters )
    {
        foreach ($this->effects as $effect) {
            if ($effect->getType() == "factionRequestAccept"
                && isset( $parameters["characterId"] )
                && $parameters["characterId"] == $characterId
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'id'   => $this->id,
            'name' => $this->name
        ];
    }
}