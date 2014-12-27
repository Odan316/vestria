<?php

class m141226_085623_VES_init extends CDbMigration
{
	public function safeUp()
	{
		$this->insert('modules', [
			'title' => 'Вестрия: Время перемен',
			'tag' => 'VES',
			'author_id' => 1,
			'system_name' => 'vestria',
			'active' => 'yes'
		]);
	}

	public function safeDown()
	{
		$this->delete('modules', ['tag' => 'VES']);
	}
}