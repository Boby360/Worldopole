<!-- Global Metas -->

		<link rel="apple-touch-icon" sizes="57x57" href="core/meta-icons/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="core/meta-icons/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="core/meta-icons/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="core/meta-icons/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="core/meta-icons/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="core/meta-icons/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="core/meta-icons/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="core/meta-icons/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="core/meta-icons/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192" href="core/meta-icons/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="core/meta-icons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="core/meta-icons/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="core/meta-icons/favicon-16x16.png">
		<link rel="manifest" href="core/meta-icons/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="core/meta-icons/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">

<?php if (!isset($_GET['page'])) {
    ?>

		<title><?= $config->infos->site_name; ?> | <?= strip_tags($locales->SITE_TITLE); ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= strip_tags($locales->SITE_TITLE); ?> " />
		<meta property="og:description" content="<?= sprintf($locales->METADESCRIPTION, $config->infos->city); ?>" />

		<meta property="og:url" content="<?= HOST_URL; ?>" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="description" content="<?= sprintf($locales->METADESCRIPTION, $config->infos->city); ?>"/>

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= sprintf($locales->METADESCRIPTION, $config->infos->city); ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= strip_tags($locales->SITE_TITLE); ?> " />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

<?php
} else {
        switch ($_GET['page']) {
        case 'pokemon':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= $locales->FIND; ?> <?= $pokemon->name; ?> <?= sprintf($locales->INCITY, $config->infos->city); ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>pokemon/<?= $pokemon->id; ?>" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= $pokemon->name; ?>" />
		<meta property="og:description" content="<?= $locales->FIND; ?> <?= $pokemon->name; ?> <?= sprintf($locales->INCITY, $config->infos->city); ?>" />
		<meta property="og:url" content="<?= HOST_URL; ?>pokemon/<?= $pokemon->id; ?>" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/pokemons/<?= $pokemon->id; ?>.png" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= $locales->FIND; ?> <?= $pokemon->name; ?> <?= sprintf($locales->INCITY, $config->infos->city); ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= $pokemon->name; ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/pokemons/<?= $pokemon->id; ?>.png" />

		<?php
            break;

        case 'pokestops':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= sprintf($locales->POKESTOPS_METATITLE, $config->infos->city); ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>pokestops" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= sprintf($locales->POKESTOPS_METATITLE, $config->infos->city); ?>" />
		<meta property="og:description" content="<?= sprintf($locales->POKESTOPS_METADESC, $config->infos->city); ?>" />
		<meta property="og:url" content="<?= HOST_URL; ?>pokestops" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= sprintf($locales->POKESTOPS_METADESC, $config->infos->city); ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= sprintf($locales->POKESTOPS_METATITLE, $config->infos->city); ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;

        case 'gym':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= $locales->GYMS_METATITLE; ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>gym" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= $locales->GYMS_METATITLE; ?>" />
		<meta property="og:description" content="<?= $locales->GYMS_METADESC; ?>" />
		<meta property="og:url" content="<?= HOST_URL; ?>gym" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= $locales->GYMS_METADESC; ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= $locales->GYMS_METATITLE; ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;

        case 'trainer':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= $locales->TRAINERS_METATITLE; ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>trainer" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= $locales->TRAINERS_METATITLE; ?>" />
		<meta property="og:description" content="<?= $locales->TRAINERS_METADESC; ?>" />
		<meta property="og:url" content="<?= HOST_URL; ?>trainer" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= $locales->TRAINERS_METADESC; ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= $locales->TRAINERS_METATITLE; ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;

        case 'dashboard':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= $locales->NAV_DASHBOARD; ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>dashboard" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= $locales->NAV_DASHBOARD; ?>" />
		<meta property="og:description" content="Data lover? We've got plenty for you!" />
		<meta property="og:url" content="<?= HOST_URL; ?>dashboard" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="Data lover? We've got plenty for you!" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= $locales->NAV_DASHBOARD; ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;

        case 'pokedex':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= $locales->POKEDEX_METATITLE; ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>pokemon" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= $locales->POKEDEX_METATITLE; ?>" />
		<meta property="og:description" content="<?= $locales->POKEDEX_METADESC; ?>" />
		<meta property="og:url" content="<?= HOST_URL; ?>pokemon" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= $locales->POKEDEX_METADESC; ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= $locales->POKEDEX_METATITLE; ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;

        case 'captcha':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= $locales->CAPTCHA_METATITLE; ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>captcha" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= $locales->CAPTCHA_METATITLE; ?>" />
		<meta property="og:description" content="<?= $locales->CAPTCHA_METADESC; ?>" />
		<meta property="og:url" content="<?= HOST_URL; ?>captcha" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= $locales->CAPTCHA_METADESC; ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= $locales->CAPTCHA_METATITLE; ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;

        case 'telegram':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= $locales->TELEGRAM_METATITLE; ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>telegram" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= $locales->TELEGRAM_METATITLE; ?>" />
		<meta property="og:description" content="<?= $locales->TELEGRAM_METADESC; ?>" />
		<meta property="og:url" content="<?= HOST_URL; ?>telegram" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= $locales->TELEGRAM_METADESC; ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= $locales->TELEGRAM_METATITLE; ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;

        case 'terms':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= $locales->TERMS_METATITLE; ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>terms" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= $locales->TERMS_METATITLE; ?>" />
		<meta property="og:description" content="<?= $locales->TERMS_METADESC; ?>" />
		<meta property="og:url" content="<?= HOST_URL; ?>terms" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= $locales->TERMS_METADESC; ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= $locales->TERMS_METATITLE; ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;

        case 'nests':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= $locales->NESTS_METADESC; ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>nests" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= $locales->NESTS_METADESC; ?>" />
		<meta property="og:description" content="<?= $locales->NESTS_METADESC; ?>" />
		<meta property="og:url" content="<?= HOST_URL; ?>nests" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= $locales->NESTS_METADESC; ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= $locales->NESTS_METADESC; ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;

        case 'raids':
        ?>

		<title><?= $config->infos->site_name; ?> | <?= $locales->NAV_RAIDS; ?></title>

		<link rel="canonical" href="<?= HOST_URL; ?>raids" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | <?= $locales->NAV_RAIDS; ?>" />
		<meta property="og:description" content="<?= $locales->RAIDS_METADESC; ?>" />
		<meta property="og:url" content="<?= HOST_URL; ?>raids" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="<?= $locales->RAIDS_METADESC; ?>" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | <?= $locales->NAV_RAIDS; ?>" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;

        case 'gymhistory':
        ?>

		<title><?= $config->infos->site_name; ?> | Gym History</title>

		<link rel="canonical" href="<?= HOST_URL; ?>gymhistory" />
		<base href="<?= HOST_URL; ?>">

		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $config->infos->site_name; ?> | Gym History" />
		<meta property="og:description" content="Gym History" />
		<meta property="og:url" content="<?= HOST_URL; ?>gymhistory" />
		<meta property="og:site_name" content="<?= $config->infos->site_name; ?>" />
		<meta property="og:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:description" content="Gym History" />
		<meta name="twitter:title" content="<?= $config->infos->site_name; ?> | Gym History" />
		<meta name="twitter:image" content="<?= HOST_URL; ?>core/img/logo.jpg" />

		<?php
            break;
    }
    }

?>
