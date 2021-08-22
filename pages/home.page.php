<header id="single-header">
	<div class="row">
		<div class="col-md-12 text-center">
			<h1>
				<?= $locales->SITE_TITLE; ?>
				<br>
				<small><?= sprintf($locales->SITE_CLAIM, $config->infos->city); ?></small>
			</h1>
			<br>
			</div>
	</div>
</header>

<div class="flex-container row area">
	<div class="flex-item-homepage big-data">
		<a href="pokemon">
			<img src="core/img/pokeball.png" alt="Visit the <?= $config->infos->site_name; ?> Pokedex" width=50 class="big-icon">
			<p><big><strong class="total-pkm-js">0</strong> Pokémon</big><br>
			<?= sprintf($locales->WIDGET_POKEMON_SUB, $config->infos->city); ?></p>
		</a>
	</div>
	<div class="flex-item-homepage big-data"> <!-- GYMS -->
		<a href="gym">
			<img src="core/img/rocket.png" alt="Discover the <?= $config->infos->site_name; ?> Gyms" width=50 class="big-icon">
			<p><big><strong class="total-gym-js">0</strong> <?= $locales->GYMS; ?></big><br>
			<?= $locales->WIDGET_GYM_SUB; ?></p>
		</a>
	</div>

<!--
	<?php
    if (true === !$config->system->no_lures) {
        ?>
		<div class="flex-item-homepage big-data">
			<a href="pokestops">
				<img src="core/img/lure-module.png" alt="Discover the <?= $config->infos->site_name; ?> Pokéstops" width=50 class="big-icon">
				<p><big><strong class="total-lure-js">0</strong> <?= $locales->LURES; ?></big><br>
                    <?= sprintf($locales->WIDGET_LURES_SUB, $config->infos->city); ?></p>
			</a>
		</div>
		<?php
    }?>
-->
	<?php
    if (true === $config->system->homepage_raids) {
        ?>
		<div class="flex-item-homepage flex-item-homepage-homepage big-data"> <!-- RAIDS -->
			<a href="raids">
				<img src="core/img/raid.png" alt="Discover the <?= $config->infos->site_name; ?> Raids" width=50
                     class="big-icon">
				<p><big><strong class="total-raids-js">0</strong> <?= $locales->RAIDS; ?></big><br>
                    <?= sprintf($locales->WIDGET_LURES_SUB, $config->infos->city); ?></p>
			</a>
		</div>
		<?php
    }
    ?>

<?php if (isset($config->homewidget->locale)) {
        $locale = $config->homewidget->locale;
        $homewidget_text = $locales->$locale;
    } elseif (isset($config->homewidget->text)) {
        $homewidget_text = $config->homewidget->text;
    }?>
	<div class="flex-item-homepage big-data">
		<a href="<?= $config->homewidget->url; ?>" target="_blank">
			<img src="<?= $config->homewidget->image; ?>" alt="<?= $config->homewidget->image_alt; ?>" width=50 class="big-icon">
			<p><?= $homewidget_text; ?></p>
		</a>
	</div>

</div>


<div class="row big padding">
	<h2 class="text-center sub-title"><?= $locales->FIGHT_TITLE; ?></h2>

		<?php
            foreach ($home->teams as $team => $total) {
                if ($home->teams->rocket) {
                    ?>

					<div class="col-md-3 col-sm-6 col-sm-12 team">
						<div class="row">
							<div class="col-xs-12 col-sm-12">
								<p style="margin-top:0.5em;text-align:center;"><img src="core/img/<?= $team; ?>.png" alt="Team <?= $team; ?>" class="img-responsive" style="display:inline-block" width=80> <strong class="total-<?= $team; ?>-js">0</strong> <?= $locales->GYMS; ?></p>

				<?php
                } else {
                    ?>

					<div class="col-md-4 col-sm-6 col-sm-12 team">
						<div class="row">
							<div class="col-xs-12 col-sm-12">
								<?php
                                    if ('rocket' != $team) {
                                        ?>
										<p style="margin-top:0.5em;text-align:center;"><img src="core/img/<?= $team; ?>.png" alt="Team <?= $team; ?>" class="img-responsive" style="display:inline-block" width=80> <strong class="total-<?= $team; ?>-js">0</strong> <?= $locales->GYMS; ?></p>
										<?php
                                    }
                } ?>
							</div>
						</div>
					</div>
				<?php
            } ?>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		<?php
        foreach (array_reverse($timers, true) as $id => $countdown) {
            ?>
			startTimer(<?= $countdown; ?>,"<?= $id; ?>");
		<?php
        } ?>
	}, false);
</script>
