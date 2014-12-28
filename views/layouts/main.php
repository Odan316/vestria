<?php
/**
 * @var $this GameController
 * @var string $content
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=CHtml::encode($this->pageTitle); ?></title>
    <link href="<?= Yii::app()->request->baseUrl; ?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>

<body>
<div id="page">
    <div id="inner_content">
        <?= $content; ?>
    </div>

    <div class="clear"></div>

    <div id="footer">
        "<?=$this->game_title ?>" Copyright by Onad &copy; <?= date('Y'); ?>. No Rights Reserved.<br/>
		<?= Yii::powered(); ?>
	</div>

</div>
</body>
</html>
