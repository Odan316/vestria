<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class Flag
 *
 * Класс флага, на данный момент испольщзуется только как хранилище констант основных флагов
 *
 * @method Flag setName( string $name )
 * @method string getName()
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
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "name" => $this->name,
        ];
    }

}