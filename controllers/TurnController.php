<?php
namespace diplomacy\modules\vestria\controllers;

use diplomacy\modules\vestria\components\ModelsFinder;
use diplomacy\modules\vestria\components\VesController;
use diplomacy\modules\vestria\models\CharacterAction;
use diplomacy\modules\vestria\models\RequestPosition;

/**
 * Class TurnController
 *
 * Контроллер для управления обсчетом хода
 */
class TurnController extends VesController
{

    const ESTATES_BASE_INCOME = 50;
    const FACTORIES_BASE_INCOME = 25;
    const ARMY_BASE_UPKEEP = 1;

    /**
     * Запуск обсчета хода
     */
    public function actionGo()
    {
        $this->makeTurn();

        $this->redirect($this->createUrl( 'game/' ));
    }

    /**
     * Управление обсчетом хода
     */
    private function makeTurn()
    {
        // предобработка хода
        $this->preprocessTurn();
        // общие заявки
        $this->applicateRequests([CharacterAction::PHASE_COMMON]);
        // переводы и "быстрые доходы"
        $this->applicateRequests([CharacterAction::PHASE_IMMEDIATE_INCOME]);
        // покупки и набор войск
        $this->applicateRequests([CharacterAction::PHASE_SPENDING]);
        // приход в бюджет
        $this->budgetSpending();
        // движения армий и сражения
        $this->manoeuvresAndBattles();
        // проверка принадлежности провинций
        //$this->provinceCheck();
        // движения армий и сражения
        $this->applicateRequests([CharacterAction::PHASE_AFTER_MANOEUVRES]);
        // приход в бюджет
        $this->budgetIncome();
        // приход в бюджет и набор рекрутов от действий
        $this->applicateRequests([CharacterAction::PHASE_INCOME]);
        // проверка победных условий
        //$this->checkAmbitions();
        // постобработка хода
        $this->postprocessTurn();
    }

    /**
     * Предобработка хода:
     * - переключение номера хода
     * - рандомизирование списка персонажей для рандомизирования порядка хода
     */
    private function preprocessTurn()
    {
        $this->game->setTurn($this->game->getTurn() + 1);
        $this->gameModel->last_turn += 1;
        $this->game->randomizeCharactersOrder();
    }

    /**
     * Постобработка хода:
     * - очистка списка заявок
     * - сохранение нового файла хода
     * - сохранение модели игры в платформе
     */
    private function postprocessTurn()
    {
        $this->game->clearRequests();
        $this->game->save();
        $this->gameModel->save();
    }


    /**
     * Применение позиций заявок заданных типов
     * @param int[] $phases
     */
    private function applicateRequests($phases = [])
    {
       // \CVarDumper::dump(json_encode($this->game), 3, 1);
        foreach($this->game->getCharacters() as $character) {
            $positions = $this->getPositionsByPhases($character, $phases);

            /** @var RequestPosition $position */
            foreach($positions as $position){
                $position->apply();
            }

        }
        //\CVarDumper::dump(json_encode($this->game), 3, 1);
        //die();
    }

    private function getPositionsByPhases($character, $phases)
    {
        $request = $this->game->getRequestByCharacterId($character->getId());
        $positions = [];
        if(is_object($request))
            $positions = $request->getPositions(['action.phase' => $phases]);

        return $positions;
    }


    /**
     * Расчет рутинных расходов бюджета
     * - за армию эквивалентно численности
     */
    private function budgetSpending()
    {
        foreach($this->game->getCharacters() as $character) {
            $armyStrength = $character->getArmy()->getStrength();
            $armyUpkeepModifier = $character->getModifier("armyUpkeep");
            $armyUpkeep = $armyStrength * self::ARMY_BASE_UPKEEP * (1+$armyUpkeepModifier);

            $character->setCash($character->getCash() - $armyUpkeep);
        }
    }

    /**
     * Расчет передвижений и битв
     * - двигаются армии по одной
     * - если в провинции назначения есть армия чужой фракции - происходит бой
     */
    public function manoeuvresAndBattles()
    {
        foreach($this->game->getCharacters() as $character) {
            $positions = $this->getPositionsByPhases($character, [CharacterAction::PHASE_MANOEUVRES]);
            foreach($positions as $position){
                $position->apply();
                // battle check!!
            }

        }
    }

    /**
     * Расчет рутинных доходов бюджета
     * - за каждое владение
     * - за каждое предприятие
     */
    private function budgetIncome()
    {
        foreach($this->game->getCharacters() as $character) {
            $estatesModifier = $character->getModifier("estatesIncome");
            $estatesIncome = $character->getEstatesCount() * self::ESTATES_BASE_INCOME * (1+$estatesModifier);

            $factoriesModifier = $character->getModifier("factoriesIncome");
            $factoriesIncome = $character->getFactoriesCount() * self::FACTORIES_BASE_INCOME * (1+$factoriesModifier);

            $character->setCash($character->getCash() + $estatesIncome + $factoriesIncome);
        }
    }

    private function provinceCheck()
    {

    }

    private function checkAmbitions()
    {

    }
}