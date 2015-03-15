<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class Province
 *
 * Класс провинции
 *
 * @method Province setId( int $id )
 * @method int getId()
 * @method Province setName( string $name )
 * @method string getName()
 * @method Province setOwnerId( int $ownerId )
 * @method int getOwnerId()
 * @method Province setNameX( int $nameX )
 * @method int getNameX()
 * @method Province setNameY( int $nameY )
 * @method int getNameY()
 * @method Province setNameSize( int $nameSize )
 * @method int getNameSize()
 *
 */
class Province extends \JSONModel
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $name;
    /** @var int */
    protected $ownerId;
    /** @var int */
    protected $nameX;
    /** @var int */
    protected $nameY;
    /** @var string */
    protected $nameSize;

    /** @var Game */
    private $game;
    /** @var Faction */
    private $owner;

    /**
     * @param Game $game
     * @param [] $data
     */
    public function __construct( $game, $data = [ ] )
    {
        $this->game = $game;
        parent::__construct( $data );
    }


    /**
     * @return Faction
     */
    public function getOwner()
    {
        if (empty( $this->owner ) || $this->owner->getId() != $this->ownerId) {
            $this->owner = $this->game->getFaction( $this->ownerId );
        }

        return $this->owner;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "id"      => $this->id,
            "name"    => $this->name,
            "ownerId" => $this->ownerId,
            "nameX" => $this->nameX,
            "nameY" => $this->nameY,
            "nameSize" => $this->nameSize
        ];
    }
}