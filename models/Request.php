<?php
namespace diplomacy\modules\vestria\models;
/**
 * Class Request
 *
 * Класс заявки игрока
 */
class Request extends \JSONModel
{
    /** @var int */
    protected $characterId;

    /** @var RequestPosition[] */
    protected $positions;

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
        if(isset($data['positions'])){
            foreach($data['positions'] as $positionData){
                if(isset($positionData['id'])){
                    $this->updatePosition($positionData);
                } else {
                    $this->createPosition($positionData);
                }
            }
            unset($data['positions']);
        }
        parent::__construct( $data );
    }

    /**
     * @param int $characterId
     *
     * @return Request
     */
    public function setCharacterId($characterId)
    {
        $this->characterId = $characterId;
        return $this;
    }
    /**
     * @return int
     */
    public function getCharacterId()
    {
        return $this->characterId;
    }

    /**
     * @return Character|null
     */
    public function getCharacter()
    {
        if (empty( $this->character )) {
            $this->character = $this->game->getCharacter( $this->characterId );
        }
        return $this->character;
    }

    /**
     * @return RequestPosition[]
     */
    public function getPositions()
    {
        return $this->positions;
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