<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class CharacterAction
 *
 * Класс действия игрока
 */
class CharacterAction extends \JSONModel
{
    const TYPE_CHARACTERS = 1;
    const TYPE_SPENDING = 2;
    const TYPE_MANEUVRES = 3;

    /** @var int */
    protected $id;
    /** @var string */
    protected $name;
    /** @var int */
    protected $type;
    /** @var Condition[] */
    protected $conditions = [];
    /** @var Parameter [] */
    protected $parameters = [];
    /** @var Effect[] */
    protected $effects = [];

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
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Parameter[]
     */
    public function getParameters(){
        return $this->parameters;
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
    public function applyEffects($game, $parameters)
    {
        foreach($this->effects as $effect) {
            $effect->apply($game, $parameters);
        }
    }

    /**
     * @param int $characterId
     * @param [] $parameters
     *
     * @return bool
     */
    public function checkFactionRequestAccept($characterId, $parameters)
    {
        foreach($this->effects as $effect){
            if($effect->getType() == "factionRequestAccept"
               && isset($parameters["characterId"])
               && $parameters["characterId"] == $characterId
            )
            {
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
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}