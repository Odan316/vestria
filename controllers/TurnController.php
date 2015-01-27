<?php
namespace diplomacy\modules\vestria\controllers;

use diplomacy\modules\vestria\components\VesController;
use diplomacy\modules\vestria\components\PlayerActionHandler;
use diplomacy\modules\vestria\models\PlayerAction;

/**
 * Class TurnController
 *
 * Контроллер для управления обсчетом хода
 */
class TurnController extends VesController
{
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
        // общие заявки
        $this->applicateRequests([PlayerAction::TYPE_CHARACTERS]);
        // приход в бюджет
        $this->budgetCalculation();
        // траты и набор войск
        $this->applicateRequests([PlayerAction::TYPE_SPENDING]);
        // движения армий и сражения
        $this->applicateRequests([PlayerAction::TYPE_MANEUVRES]);
    }

    /**
     * Применение позиций заявок заданных типов
     * @param int[] $types
     */
    private function applicateRequests($types = [])
    {

    }

    /**
     * Расчет рутинных доходов и расходов бюджета
     */
    private function budgetCalculation()
    {

    }
}