<?php p($l->t('Enhanced security has been enabled for your account. Please authenticate using a second factor.')) ?>
<ol class="two-factor-challenge-picker">
	<?php foreach ($_['providers'] as $provider): ?>
	<li class="button">
		<a href="<?php p(\OC::$server->getURLGenerator()->linkToRoute('core.TwoFactorChallenge.showChallenge', ['challengeProviderId' => $provider->getId()])) ?>">
			<?php p($provider->getDisplayName()) ?>
		</a>
	</li>
	<?php endforeach; ?>
</ol>
