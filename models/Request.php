<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class Request
 *
 * Класс заявки игрока
 *
 * @method Request setCharacterId( int $characterId )
 * @method int getCharacterId()
 * @method Request setLastPositionId( int $id )
 * @method int getLastPositionId()
 */
class Request extends \JSONModel
{
    /** @var int */
    protected $characterId;

    /** @var RequestPosition[] */
    protected $positions = [];

    /** @var int */
    private $lastPositionId = 0;

    /** @var Game */
    protected $game;
    /** @var Character */
    protected $character;

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
     * @inheritdoc
     */
    public function setAttributes($data)
    {
        if(isset($data['positions'])){
            foreach($data['positions'] as $positionData){
                if(isset($positionData['id']) && $this->getPosition($positionData['id'])){
                    $this->updatePosition($positionData);
                } else {
                    $this->createPosition($positionData);
                }
            }
            unset($data['positions']);
        }
        parent::setAttributes($data);
    }

    /**
     * @return Character|null
     */
    public function getCharacter()
    {
        if (empty( $this->character ) || $this->character->getId() != $this->characterId) {
            $this->character = $this->game->getCharacter( $this->characterId );
        }
        return $this->character;
    }

    /**
     * @param [] $criteria
     * @param bool $asArray
     *
     * @return RequestPosition[]
     */
    public function getPositions($criteria = [], $asArray = false)
    {
        return $this->getModelsList($this->positions, $criteria, $asArray);
    }

    /**
     * @param int $id
     *
     * @return RequestPosition|null
     */
    public function getPosition($id)
    {
        foreach($this->positions as $position) {
            if($position->getId() == $id)
                return $position;
        }
        return null;
    }

    public function createPosition($data)
    {
        $this->lastPositionId ++;
        $data['id'] = $this->lastPositionId;
        $model = new RequestPosition($this, $data);
        $this->positions[] = $model;
    }

    /**
     * @param [] $data
     *
     * @return void
     */
    public function updatePosition($data)
    {
        $position = $this->getPosition($data['id']);
        if($position){
            $position->setAttributes($data);
        }
    }

    /**
     * @param int $id
     */
    public function deletePosition($id)
    {
        foreach($this->positions as $key => $position){
            if($position->getId() == $id){
                unset($this->positions[$key]);
                $this->game->save();
                break;
            }
        }
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            "characterId" => $this->characterId,
            "positions"   => $this->positions
        ];
    }
}