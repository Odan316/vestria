<?php

class VestriaModule extends CWebModule
{
	public function init()
	{
        Yii::app()->name = 'Дипломатия: Вестрия';
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
            $this->_assetsBase = Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('vestria.assets'),
                false,
                -1,
                YII_DEBUG
            );
        }
        return $this->_assetsBase;
    }
}
