<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class Army
 *
 * Класс армии
 */
class Flag extends \JSONModel
{
    const CAN_LEAD_ARMIES = "canLeadArmies";

    /** @var string */
    protected $name;

    /**
     * @param [] $data
     */
    public function __construct( $data = [ ] )
    {
        parent::__construct( $data );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "officerId" => $this->officerId,
            "strength" => $this->strength,
            "morale" => $this->morale
        ];
    }

}