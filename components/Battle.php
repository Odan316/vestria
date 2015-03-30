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
use diplomacy\modules\vestria\models\Army;


class Battle {

    /** @var Game */
    private $game;
    /** @var Province */
    private $province;
    /** @var Character */
    private $attacker;
    /** @var string */
    private $winner;

    /** @var Character[] */
    private $attackers = [];
    /** @var Character[] */
    private $defenders = [];
    /** @var Character[] */
    private $retreated = [];

    /** @var [] */
    private $minArmiesMorale = [];

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
                $this->attackers[$commander->getId()] = $commander;
            else
                $this->defenders[$commander->getId()] = $commander;

            $this->minArmiesMorale[$commander->getId()] = floor($commander->getArmy()->getMorale()/3);
        }
    }

    /**
     * Обсчет битвы по раундам
     */
    private function calculateBattle()
    {
        while(count($this->attackers) && count($this->defenders)){
            $this->calculateRound();
        }
        if(count($this->attackers))
            $this->winner = "attakers";
        else
            $this->winner = "defenders";

    }

    public function calculateRound()
    {
        foreach($this->attackers as $attacker){
            $attackerArmy = $attacker->getArmy();
            $attackDamage = $attackerArmy->getStrength() * ($attackerArmy->getMorale()/100) * $attacker->getModifier("attackDamageModifier");
            $hitDamage = floor($attackDamage/count($this->defenders));
            foreach($this->defenders as $defender) {
                $defenderArmy = $defender->getArmy();
                $defenderDamage = $defenderArmy->getStrength() * ($defenderArmy->getMorale()/100) * $defender->getModifier("defenceDamageModifier");
                $this->inflictDamage($defenderArmy, $hitDamage);
                $this->inflictDamage($attackerArmy, $defenderDamage);
            }
        }

        foreach($this->attackers as $attacker) {
            $this->checkArmyDefeat( $attacker->getArmy() );
        }
        foreach($this->defenders as $defender) {
            $this->checkArmyDefeat( $defender->getArmy() );
        }
    }

    /**
     * Нанесение повреждений по численности и морали
     *
     * @param Army $army
     * @param int $damage
     */
    public function inflictDamage($army, $damage)
    {
        $strengthBefore = $army->getStrength();
        $moraleBefore = $army->getMorale();
        $moraleDamage = floor($damage/$strengthBefore);
        $strengthAfter = $strengthBefore - $damage < 0 ? 0 : $strengthBefore - $damage;
        $moraleAfter = $moraleBefore - $moraleDamage < 0 ? 0 : $moraleBefore - $moraleDamage;
        $army->setStrength($strengthAfter);
        $army->setMorale($moraleAfter);
    }

    /**
     * Проверка на то, не разгромлена ли армия в раунде и ее отступление/уничтожение
     *
     * @param Army $army
     */
    public function checkArmyDefeat($army)
    {
        if($army->getStrength() == 0 || $army->getMorale() == 0) {
            // Армия уничтожена
            unset($this->attackers[$army->getOfficerId()]);
            unset($this->defenders[$army->getOfficerId()]);
            $this->game->destroyArmy($army->getId());
        } elseif ($army->getMorale() < $this->minArmiesMorale[$army->getOfficerId()]){
            // Армия сбегает
            unset($this->attackers[$army->getOfficerId()]);
            unset($this->defenders[$army->getOfficerId()]);
            $this->retreated[$army->getOfficerId()] = $army;
        }
    }

    /**
     * Потери после битвы
     */
    private function calculateLosses()
    {
        // Пока что ничего
    }

    /**
     * Расчет куда должны отступать армии, если омгут отступить, или уничтожение не отступивших армий
     */
    private function calculateRetreat()
    {
        foreach($this->retreated as $retreated) {

        }
    }
}