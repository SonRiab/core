<?php
/** @var $l OC_L10N */
/** @var $_ array */
/* @var $error boolean */
$error = $_['error'];
/* @var $provider OCP\Authentication\TwoFactorAuth\IProvider */
$provider = $_['provider'];
/* @var $template string */
$template = $_['template'];
?>

<h1><?php p($provider->getDisplayName()); ?></h1>
<?php if ($error): ?>
<span class="warning"><?php p($l->t('An error occured while verifying the token')); ?></span>
<?php endif; ?>
<?php print_unescaped($template); ?>
