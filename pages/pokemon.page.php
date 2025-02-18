<!-- Header -->
<header id="single-header">

	<!-- Breadcrumb -->
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
				<li><a href="<?= HOST_URL; ?>"><?= $locales->HOME; ?></a></li>
				<li><a href="pokemon"><?= $locales->NAV_POKEDEX; ?></a></li>
				<li class="active"><?= $pokemon->name; ?></li>
			</ol>
		</div>
	</div>
	<!-- /Breadcrumb -->

	<div class="row">

		<div class="col-sm-1 hidden-xs">

				<?php if ($pokemon->id - 1 > 0) {
    ?>

				<p class="nav-links"><a href="pokemon/<?= $pokemon->id - 1; ?>"><i class="fa fa-chevron-left"></i></a></p>

				<?php
}?>

		</div>

		<div class="col-sm-10 text-center">

			<h1>#<?= sprintf('%03d <strong>%s</strong>', $pokemon->id, $pokemon->name); ?></h1>

			<p id="share">
				<a href="https://www.facebook.com/sharer/sharer.php?u=<?= HOST_URL; ?>pokemon/<?= $pokemon->id; ?>" target="_blank" class="btn btn-primary" title="<?php printf($locales->SHARE, 'Facebook'); ?>" ><?php printf($locales->SHARE, ''); ?> <i class="fa fa-facebook" aria-hidden="true"></i></a>

				<a href="https://twitter.com/intent/tweet?source=<?= HOST_URL; ?>pokemon/<?= $pokemon_id; ?>&text=Find%20<?= $pokemon->name; ?>%20in:%20<?= $config->infos->city; ?>%20<?= HOST_URL; ?>pokemon/<?= $pokemon->id; ?>" target="_blank" title="<?php printf($locales->SHARE, 'Twitter'); ?>" class="btn btn-info"><?php printf($locales->SHARE, ''); ?> <i class="fa fa-twitter" aria-hidden="true"></i></a>
			</p>

		</div>


		<div class="col-sm-1 hidden-xs">

			<?php if ($pokemon->id + 1 < $config->system->max_pokemon) {
        ?>

			<p class="nav-links"><a href="pokemon/<?= $pokemon->id + 1; ?>"><i class="fa fa-chevron-right"></i></a></p>

			<?php
    } ?>
		</div>

	</div>
<?php
    $form_array = array('Unset', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'W', 'X', 'Y', 'Z');
    $form_array = array_values($form_array);
    ?>

</header>
<!-- /Header -->


<div class="row">
	<div class="col-md-2 col-xs-4">
		<div id="poke-img" style="padding-top:15px;margin-bottom:1em;">
			<img class="media-object img-responsive" src="<?= $pokemon->img; ?>" alt="<?= $pokemon->name; ?> model" >
		</div>
	</div>

	<div class="col-md-4 col-xs-8" style="margin-bottom:1em;">

		<div class="media">
			<div class="media-body" style="padding-top:25px;">

				<p><?= $pokemon->description; ?></p>

				<p>
				<?php foreach ($pokemon->types as $type) {
        ?>
					<span class="label label-default" style="background-color:<?= $pokemons->typecolors->$type; ?>"><?= $type; ?></span>
				<?php
    }?>
				</p>

			</div>
		</div>

	</div>

	<div class="col-md-6" style="padding-top:10px;">
		<canvas id="spawn_chart" width="100%" height="40"></canvas>
	</div>

</div>



<div class="row">
	<div class="col-md-6" style="padding-top:10px;">
		<div class="table-responsive">
		<table class="table">
            <tr>
                <td class="col-md-8 col-xs-8"><strong><?= $locales->POKEMON_GEN; ?></strong></td>
                <td class="col-md-4 col-xs-4"><?= $pokemon->gen; ?></td>
            </tr>
			<tr>
				<td class="col-md-8 col-xs-8"><strong><?= $locales->POKEMON_SEEN; ?></strong></td>
				<td class="col-md-4 col-xs-4">

				<?php
                if (isset($pokemon->last_seen) || isset($pokemon->last_seen_day)) {

                    if (isset($pokemon->last_seen)) {
                        $time_ago = time_ago($pokemon->last_seen, $locales);
                    } else {
                        $time_ago = time_ago_day($pokemon->last_seen_day, $locales);
                    }

                    if (isset($pokemon->last_position)) {
                        ?>
                        <a href="https://maps.google.com/?q=<?= $pokemon->last_position->latitude; ?>,<?= $pokemon->last_position->longitude; ?>&ll=<?= $pokemon->last_position->latitude; ?>,<?= $pokemon->last_position->longitude; ?>&z=16" target="_blank"><?= $time_ago ?></a>
                        <?php
                    } else {
                        echo $time_ago;
                    }
                    ?>

				    <?php
                } else {
                    echo $locales->NEVER;
                }
                ?>

				</td>
			</tr>
			<tr>
				<td class="col-md-8 col-xs-8"><strong><?= $locales->POKEMON_AMOUNT; ?></strong></td>
				<td class="col-md-4 col-xs-4"><?= $pokemon->spawn_count; ?> <?= $locales->SEEN; ?></td>
			</tr>
			<tr>
				<td class="col-md-8 col-xs-8"><strong><?= $locales->POKEMON_RATE; ?></strong></td>
				<td class="col-md-4 col-xs-4"><?= $pokemon->spawns_per_day; ?> / <?= $locales->DAY; ?></td>
			</tr>
			<tr>
				<td class="col-md-8 col-xs-8">
                    <?php
                    if (isset($pokemon->protected_gyms)) {
                        echo '<strong>'.$locales->POKEMON_GYM.$pokemon->name.'</strong>';
                    } ?>
                </td>
				<td class="col-md-4 col-xs-4">
                    <?php
                    if (isset($pokemon->protected_gyms)) {
                        echo $pokemon->protected_gyms;
                    }?>
                </td>
			</tr>
		</table>
		</div>
		</div>

		<div class="col-md-6" style="padding-top:10px;">
		<div class="table-responsive">
		<table class="table">
			<tr>
				<td class="col-md-8 col-xs-8"><strong><?= $locales->POKEMON_RAID_SEEN; ?></strong></td>
				<td class="col-md-4 col-xs-4">

                    <?php
                    if (isset($pokemon->last_raid_position)) {
                        ?>

					    <a href="https://maps.google.com/?q=<?= $pokemon->last_raid_position->latitude; ?>,<?= $pokemon->last_raid_position->longitude; ?>&ll=<?= $pokemon->last_raid_position->latitude; ?>,<?= $pokemon->last_raid_position->longitude; ?>&z=16" target="_blank"><?= time_ago($pokemon->last_raid_seen, $locales); ?></a>

					    <?php
                    } else {
                        echo $locales->NEVER;
                    }
                    ?>

				</td>
			</tr>
			<tr>
				<td class="col-md-8 col-xs-8"><strong><?= $locales->POKEMON_RAID_AMOUNT; ?></strong></td>
				<td class="col-md-4 col-xs-4"><?= $pokemon->raid_count; ?> <?= $locales->SEEN; ?></td>
			</tr>

		</table>
		</div>
	</div>
</div>


<div class="row area text-center subnav">
	<div class="btn-group" role="group">
		<a class="btn btn-default page-scroll" href="pokemon/<?= $pokemon->id; ?>#where"><i class="fa fa-map-marker"></i> <?= $locales->POKEMON_MAP; ?></a>
		<a class="btn btn-default page-scroll" href="pokemon/<?= $pokemon->id; ?>#evolve"><i class="fa fa-flash"></i> <?= $locales->POKEMON_EVOLUTIONS; ?></a>
		<a class="btn btn-default page-scroll" href="pokemon/<?= $pokemon->id; ?>#top50"><i class="fa fa-list"></i> Top10</a>
	</div>
</div>


<div class="row" id="where">
	<div class="col-md-12">
		<h2 class="text-center sub-title"><?= $locales->POKEMON_WHERE; ?> <?= $pokemon->name; ?>?</h2>
	</div>
</div>
<div class="row text-center subnav">
	<div class="btn-group" role="group">
		<a class="btn btn-default active" id="heatmapSelector"><i class="fa fa-thermometer-three-quarters"></i> <?= $locales->POKEMON_HEATMAP_BUTTON; ?></a>
	</div>
</div>
<div class="row" style="margin-bottom:20px">
	<div class="col-md-12" id="timeFilterContainer">
		<div id="timeSelector">
		</div>
	</div>
	<div class="col-md-12 text-center" id="loaderContainer">
		<h3><?= $locales->LOADING; ?></h3>
	</div>
</div>
<div class="row area">
	<div class="col-md-12">
		<div id="map">
		</div>
	</div>
</div>



<div class="row area" id="evolve">

	<h2 class="text-center sub-title"><strong><?= $pokemon->name; ?> </strong><?= $locales->POKEMON_EVOLUTIONS; ?></h2>

	<div class="col-md-12 flex-container-tree results">

		<?php
        $tree = array($pokemon->tree);
        $depth = get_depth($tree);
        $skip = false;
        for ($i = 0; $i < $depth; ++$i) {
            $i_id = intval(($i + 1) / 2);
            $data = get_tree_at_depth($tree, $i_id, $config->system->max_pokemon); ?>

			<?php
            if (!is_null($data) && 0 != sizeof($data) && !$skip) {
                ?>
				<div class="col-md-12 flex-item-tree">
					<?php
                    foreach ($data as $obj) {
                        $obj_id = $obj->id;
                        $tree_pokemon = $pokemons->pokemon->$obj_id;
                        $link = 'pokemon/'.$obj_id;

                        if (0 == $i % 2) {
                            ?>

							<div>
								<a href="<?= $link; ?>"><img src="<?= $tree_pokemon->img; ?>" alt="<?= $tree_pokemon->name; ?>" class="img"></a>
								<p class="pkmn-name"><a href="<?= $link; ?>">#<?= sprintf('%03d<br>%s', $tree_pokemon->id, $tree_pokemon->name); ?></a></p>
							</div>

						<?php
                        } else {
                            ?>

							<div>
								<img src="core/img/arrow<?=$obj->array_sufix; ?>.png" alt="Arrow" class="img">
								<p class="pkmn-name">
									<?php
                                    if (isset($obj->candies)) {
                                        echo $obj->candies.' '.$locales->POKEMON_CANDIES;
                                    } else {
                                        echo '? '.$locales->POKEMON_CANDIES;
                                    }

                            if (isset($obj->item)) {
                                $itemName = 'ITEM_'.$obj->item;
                                echo '<br>+ '.$locales->$itemName;
                            } elseif (isset($obj->info)) {
                                $infoName = 'INFO_'.$obj->info;
                                echo '<br>('.$locales->$infoName.')';
                            } else {
                                echo '<br> </br>';
                            } ?>
								</p>
							</div>
                            <?php
                        }
                    } ?>
                </div>
            <?php
            } elseif ($skip) {
                $skip = false;
            } else {
                $skip = true;
            }
        }
        ?>
	</div>
</div>

<?php if (!empty($top)) {
            ?>
	<div class="row area" id="top50">
		<div class="col-md-12">
			<h2 class="text-center sub-title">Top 50 <strong><?= $pokemon->name; ?></strong></h2>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th><a href="pokemon/<?= $pokemon->id; ?>?order=cp<?php echo 'cp' == $best_order && !isset($_GET['direction']) ? '&direction=desc' : ''; ?>#top50">CP <i class="fa fa-sort" aria-hidden="true"></i></a></th>
							<th><a href="pokemon/<?= $pokemon->id; ?>?order=IV<?php echo 'IV' == $top_order && !isset($_GET['direction']) ? '&direction=desc' : ''; ?>#top50">IV <i class="fa fa-sort" aria-hidden="true"></i></a></th>
							<th><a href="pokemon/<?= $pokemon->id; ?>?order=individual_attack<?php echo 'individual_attack' == $top_order && !isset($_GET['direction']) ? '&direction=desc' : ''; ?>#top50"><?= $locales->POKEMON_TABLE_ATTACK; ?> <i class="fa fa-sort" aria-hidden="true"></i></a></th>
							<th><a href="pokemon/<?= $pokemon->id; ?>?order=individual_defense<?php echo 'individual_defense' == $top_order && !isset($_GET['direction']) ? '&direction=desc' : ''; ?>#top50"><?= $locales->POKEMON_TABLE_DEFENSE; ?> <i class="fa fa-sort" aria-hidden="true"></i></a></th>
							<th><a href="pokemon/<?= $pokemon->id; ?>?order=individual_stamina<?php echo 'individual_stamina' == $top_order && !isset($_GET['direction']) ? '&direction=desc' : ''; ?>#top50"><?= $locales->POKEMON_TABLE_STAMINA; ?> <i class="fa fa-sort" aria-hidden="true"></i></a></th>
							<th><a href="pokemon/<?= $pokemon->id; ?>?order=move_1<?php echo 'move_1' == $top_order && !isset($_GET['direction']) ? '&direction=desc' : ''; ?>#top50">1. <?= $locales->MOVE; ?> <i class="fa fa-sort" aria-hidden="true"></i></a></th>
							<th><a href="pokemon/<?= $pokemon->id; ?>?order=move_2<?php echo 'move_2' == $top_order && !isset($_GET['direction']) ? '&direction=desc' : ''; ?>#top50">2. <?= $locales->MOVE; ?> <i class="fa fa-sort" aria-hidden="true"></i></a></th>
							<th><a href="pokemon/<?= $pokemon->id; ?>?order=disappear_time<?php echo 'disappear_time' == $top_order && !isset($_GET['direction']) ? '&direction=desc' : ''; ?>#top50"><?= $locales->DATE; ?> <i class="fa fa-sort" aria-hidden="true"></i></a></th>
							<?php if (201 == $pokemon->id) {
                ?>
								<th>Form</th>
							<?php
            } ?>
						</tr>
					</thead>
				
					<tbody>
						<?php
                        $i = 0;
            foreach ($top as $top50) {
                ++$i;
                $move1 = $top50->move_1;
                $move2 = $top50->move_2; ?>

							<tr>
								<td><?= $i; ?></td>
								<td><?= isset($top50->cp) ? $top50->cp : '???'; ?></td>
								<td><?= $top50->IV; ?> %</td>
								<td><?= $top50->individual_attack; ?></td>
								<td><?= $top50->individual_defense; ?></td>
								<td><?= $top50->individual_stamina; ?></td>
								<td><?php echo $move->$move1->name; ?></td>
								<td><?php echo $move->$move2->name; ?></td>
								<td><a href="https://maps.google.com/?q=<?= $top50->latitude; ?>,<?= $top50->longitude; ?>&ll=<?= $top50->latitude; ?>,<?= $top50->longitude; ?>&z=16"
									target="_blank"><?=$top50->distime; ?></a></td>
								<?php if (201 == $pokemon->id && $top50->form) {
                    ?>
									<td><?php echo $form_array[$top50->form]; ?></td>
								<?php
                } else {
                    ?>
									<td></td>
								<?php
                } ?>
							</tr>
							<?php
            } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
        } ?>
