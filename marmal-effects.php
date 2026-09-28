<?php
/**
 * Plugin Name:       MarMal Effects
 * Plugin URI:        https://marmal.cz
 * Description:       Knihovna CSS efektů (rámečky, tlačítka, karty, obrázky, hero, text, animace při scrollu) pro Breakdance a jakýkoliv WordPress web. Galerie s náhledy v administraci, aktualizace z GitHubu.
 * Version:           1.1.0
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
 * ==========================================================================
 *  NASTAV JEN TOHLE: adresa tvého repozitáře na GitHubu (s lomítkem na konci).
 *  Pokud je repozitář soukromý, přidej do wp-config.php:
 *      define( 'MM_EFFECTS_GITHUB_TOKEN', 'github_pat_...' );
 * ==========================================================================
 */
if ( ! defined( 'MM_EFFECTS_GITHUB_REPO' ) ) {
	define( 'MM_EFFECTS_GITHUB_REPO', 'https://github.com/marmal-code/marmal-effects/' );
}

define( 'MM_EFFECTS_FILE', __FILE__ );
define( 'MM_EFFECTS_DIR', plugin_dir_path( __FILE__ ) );
define( 'MM_EFFECTS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Verze se čte z hlavičky výše – stačí ji měnit na jednom místě.
 */
function mm_effects_version() {
	static $version = null;
	if ( null === $version ) {
		$data    = get_file_data( MM_EFFECTS_FILE, array( 'Version' => 'Version' ) );
		$version = $data['Version'] ? $data['Version'] : '1.0.0';
	}
	return $version;
}

/* --------------------------------------------------------------------------
 * 1) Automatické aktualizace z GitHubu (Plugin Update Checker)
 * ----------------------------------------------------------------------- */
require_once MM_EFFECTS_DIR . 'lib/plugin-update-checker/plugin-update-checker.php';

add_action(
	'plugins_loaded',
	function () {
		if ( false !== strpos( MM_EFFECTS_GITHUB_REPO, 'TVUJ-GITHUB-UCET' ) ) {
			return; // Repozitář ještě není nastavený.
		}
		$checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
			MM_EFFECTS_GITHUB_REPO,
			MM_EFFECTS_FILE,
			'marmal-effects'
		);
		// Stahuje hotový ZIP, který vyrobí GitHub Action (.github/workflows/release.yml).
		$checker->getVcsApi()->enableReleaseAssets( '/marmal-effects\.zip($|[?&#])/i' );
		if ( defined( 'MM_EFFECTS_GITHUB_TOKEN' ) && MM_EFFECTS_GITHUB_TOKEN ) {
			$checker->setAuthentication( MM_EFFECTS_GITHUB_TOKEN );
		}
	}
);

/* --------------------------------------------------------------------------
 * 2) Nastavení barev (ručně / z Breakdance) – viz includes/settings.php
 * ----------------------------------------------------------------------- */
require_once MM_EFFECTS_DIR . 'includes/settings.php';

/* --------------------------------------------------------------------------
 * 3) Načtení na webu (i v editoru Breakdance)
 * ----------------------------------------------------------------------- */
function mm_effects_register_assets() {
	$v = mm_effects_version();
	wp_register_style( 'mm-effects', MM_EFFECTS_URL . 'assets/css/mm-effects.css', array(), $v );
	wp_register_script( 'mm-effects', MM_EFFECTS_URL . 'assets/js/mm-effects.js', array(), $v, true );
	$vars = mm_effects_inline_vars();
	if ( $vars ) {
		wp_add_inline_style( 'mm-effects', $vars );
	}
}
add_action( 'init', 'mm_effects_register_assets' );

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'mm-effects' );
		if ( mm_effects_settings()['load_js'] ) {
			wp_enqueue_script( 'mm-effects' );
		}
	},
	20
);

/**
 * Třídu html.mm-js přidáme hned v <head>, aby animované prvky neproblikly.
 * Pojistka: když se hlavní skript do 3 s nenačte, všechno se zase ukáže.
 * V editoru Breakdance se nic neschovává.
 */
add_action(
	'wp_head',
	function () {
		if ( ! mm_effects_settings()['load_js'] ) {
			return;
		}
		?>
<script id="mm-effects-early">(function(d,l){if(/[?&](breakdance|breakdance_iframe)=/.test(l.search))return;d.classList.add('mm-js');setTimeout(function(){if(!window.MMEffects)d.classList.remove('mm-js');},3000);})(document.documentElement,location);</script>
		<?php
	},
	1
);

/* --------------------------------------------------------------------------
 * 4) Administrace: galerie s náhledy + nastavení
 * ----------------------------------------------------------------------- */
add_action(
	'admin_menu',
	function () {
		add_menu_page(
			'MarMal Efekty',
			'MarMal Efekty',
			'edit_posts',
			'mm-effects',
			'mm_effects_render_gallery',
			'dashicons-art',
			59
		);
		add_submenu_page( 'mm-effects', 'Galerie efektů', 'Galerie efektů', 'edit_posts', 'mm-effects', 'mm_effects_render_gallery' );
		add_submenu_page( 'mm-effects', 'Nastavení efektů', 'Nastavení', 'manage_options', 'mm-effects-settings', 'mm_effects_render_settings' );
	}
);

add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		if ( 'toplevel_page_mm-effects' !== $hook ) {
			return;
		}
		$v = mm_effects_version();
		wp_enqueue_style( 'mm-effects' );
		wp_enqueue_style( 'mm-effects-gallery', MM_EFFECTS_URL . 'assets/admin/gallery.css', array( 'mm-effects' ), $v );
		wp_add_inline_style( 'mm-effects-gallery', ':root{--mmg-bg:#f0f0f1}' . mm_effects_admin_bde_css() );

		wp_enqueue_script( 'mm-effects' );
		wp_enqueue_script( 'mm-effects-gallery', MM_EFFECTS_URL . 'assets/admin/gallery.js', array( 'mm-effects' ), $v, true );

		$json = file_get_contents( MM_EFFECTS_DIR . 'data/effects.json' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$data = json_decode( (string) $json, true );
		wp_add_inline_script(
			'mm-effects-gallery',
			'window.MM_EFFECTS_DATA=' . wp_json_encode( $data ) . ';',
			'before'
		);
	}
);

// Skript efektů musí vědět, že v galerii má animace opravdu přehrávat.
add_filter(
	'script_loader_tag',
	function ( $tag, $handle ) {
		if ( 'mm-effects' === $handle && is_admin() ) {
			$tag = '<script>window.MM_EFFECTS_FORCE=true;</script>' . $tag;
		}
		return $tag;
	},
	10,
	2
);

function mm_effects_render_gallery() {
	echo '<div class="wrap"><div id="mm-gallery" class="mm-g"></div></div>';
}

// Odkaz „Galerie“ přímo v seznamu pluginů.
add_filter(
	'plugin_action_links_' . plugin_basename( __FILE__ ),
	function ( $links ) {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'admin.php?page=mm-effects' ) ) . '">Galerie</a>' );
		return $links;
	}
);

// Rychlý odkaz na galerii v horní liště (na webu i v administraci).
add_action(
	'admin_bar_menu',
	function ( $bar ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		$bar->add_node(
			array(
				'id'    => 'mm-effects',
				'title' => 'MarMal Efekty',
				'href'  => admin_url( 'admin.php?page=mm-effects' ),
				'meta'  => array( 'target' => '_blank' ),
			)
		);
	},
	90
);
