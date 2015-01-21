<?php
namespace diplomacy\modules\vestria;

class VestriaModule extends \CWebModule
{
    /**
     * Пространство имён для контроллеров
     * @var string
     */
    public $controllerNamespace = '\diplomacy\modules\vestria\controllers';
    /** @var string */
    protected $title;

	public function init()
	{
        \Yii::app()->name = 'Дипломатия: Вестрия';
        $this->title = "Вестрия: Время Перемен";
		// import the module-level models and components
		$this->setImport(array(
			'vestria.models.*',
			'vestria.components.*',
		));
	}
    /**
     * метод для работы с ассетами, взят здесь : http://habrahabr.ru/post/139166/
     */
    private $_assetsBase;
    public function getAssetsBase()
    {
        if ($this->_assetsBase === null) {
            $this->_assetsBase = \Yii::app()->assetManager->publish(
                \Yii::getPathOfAlias('vestria.assets'),
                false,
                -1,
                YII_DEBUG
            );
        }
        return $this->_assetsBase;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
