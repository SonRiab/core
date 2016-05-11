<?php
/** @var $l OC_L10N */
/** @var $_ array */
/* @var $provider OCP\Authentication\TwoFactorAuth\IProvider */
$provider = $_['provider'];
/* @var $template string */
$template = $_['template'];
?>

<h1><?php p($provider->getDisplayName()); ?></h1>
<?php print_unescaped($template); ?>
