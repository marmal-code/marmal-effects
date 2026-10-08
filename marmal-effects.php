<?php
/**
 * Plugin Name:       MarMal Effects
 * Plugin URI:        https://marmal.cz
 * Description:       Knihovna CSS efektů (rámečky, tlačítka, karty, obrázky, hero, text, animace při scrollu) pro Breakdance a jakýkoliv WordPress web. Galerie s náhledy v administraci, aktualizace z GitHubu.
 * Version:           1.2.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Martin Malý – MarMal
 * Author URI:        https://marmal.cz
 * License:           GPL-2.0-or-later
 * Text Domain:       marmal-effects
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Efekty už běží jako modul pluginu Marmal – Breakdance Plus (typicky při
 * opětovné aktivaci tohoto pluginu) → nic nenačítat, jinak by se funkce
 * deklarovaly dvakrát. Při dalším načtení stránky Breakdance Plus pozná, že
 * běží starý plugin, a nabídne převod.
 */
if ( function_exists( 'mm_effects_version' ) ) {
	return;
}

define( 'MM_EFFECTS_FILE', __FILE__ );

require_once __DIR__ . '/includes/plugin.php';
