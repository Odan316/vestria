<?php
/**
 * Created by PhpStorm.
 * User: onag
 * Date: 17.03.15
 * Time: 15:35
 */

namespace diplomacy\modules\vestria\components;

use diplomacy\modules\vestria\models\Game;
use diplomacy\modules\vestria\models\Province;
use diplomacy\modules\vestria\models\Character;


class Battle {

    /** @var Game */
    private $game;
    /** @var Province */
    private $province;
    /** @var Character */
    private $attacker;

    /** @var Character[] */
    private $attackers = [];
    /** @var Character[] */
    private $defenders = [];

    /**
     * @param Province $province
     */
    public function __construct($province)
    {
        $this->province = $province;
        $this->game = $province->getGame();
    }

    /**
     * @param Character $character
     */
    public function setAttacker($character)
    {
        $this->attacker = $character;
    }

    /**
     * Управление битвой
     */
    public function calculateResult()
    {
        $this->getSides();
        $this->calculateBattle();
        $this->calculateLosses();
        $this->calculateRetreat();
    }

    /**
     * Определение сторон
     */
    private function getSides()
    {
        $commandersInProvince = $this->game->getCharacters(["provinceId" => $this->province->getId(), "armyId" => ["notEmpty"]]);
        foreach($commandersInProvince as $commander) {
            if($commander->getFactionId() == $this->attacker->getFactionId())
                $this->attackers[] = $commander;
            else
                $this->defenders[] = $commander;
        }
    }

    /**
     * Обсчет битвы по раундам
     */
    private function calculateBattle()
    {
        foreach($this->attackers as $attacker){

        }
    }

    /**
     * Потери после битвы
     * - мораль
     */
    private function calculateLosses()
    {

    }

    /**
     * Расчет куда должны отступать армии, если омгут отступить, или уничтожение не отступивших армий
     */
    private function calculateRetreat()
    {

    }
}