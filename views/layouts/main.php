<?php
use vestria\controllers\GameController;
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
    <link href='http://fonts.googleapis.com/css?family=Marck+Script&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href="<?= Yii::app()->request->baseUrl; ?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <script type="text/javascript">
        <?php if(strpos($_SERVER['HTTP_HOST'], 'local') !== false){ ?>
        window.url_root = "/vestria/";
        <?php } else { ?>
        window.url_root = "/diplomacy/vestria/";
        <?php } ?>
    </script>
</head>

<body>
<div id="page">
    <div id="inner_content">
        <?= $content; ?>
    </div>

    <div class="clearfix"></div>

    <div id="footer">
        "<?=Yii::app()->name ?>" Copyright by Onad &copy; <?= date('Y'); ?>. No Rights Reserved.
        <a id="cabinet_link" href="/cabinet">Назад в кабинет</a>
        <br/>
		<?= Yii::powered(); ?>
	</div>

</div>
</body>
</html>
