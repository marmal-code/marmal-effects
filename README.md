# MarMal Effects

> **Plugin se dál nevyvíjí (poslední verze 1.2.0).** Efekty jsou od října 2026 modul pluginu
> [Marmal – Breakdance Plus](https://github.com/marmal-code/marmal-breakdance-plus) (0.7.0+):
> po instalaci **MarMal → Moduly → Efekty → Převést ze starého pluginu**. Nastavení barev i třídy `mm-…` zůstanou.
> Nové efekty přidávej do `modules/efekty/` v Breakdance Plus.

Knihovna CSS efektů pro WordPress weby stavěné v Breakdance (funguje i bez něj).
89 efektů v 10 sekcích, galerie s živými náhledy v administraci a automatické
aktualizace z GitHubu.

| Sekce | Příklady tříd |
|---|---|
| Rámečky | `mm-border-gradient`, `mm-border-spin`, `mm-border-draw`, `mm-border-corners` |
| Tlačítka | `mm-btn-shine`, `mm-btn-fill`, `mm-btn-arrow`, `mm-btn-pulse`, `mm-btn-magnetic` |
| Karty a sloupce | `mm-card-lift`, `mm-card-tilt`, `mm-card-spotlight`, `mm-card-glass`, `mm-cols-dividers` |
| Obrázky | `mm-img-zoom`, `mm-img-gray`, `mm-img-frame`, `mm-img-reveal`, `mm-img-parallax` |
| Ikony a detaily | `mm-icon-pulse`, `mm-icon-ping`, `mm-icon-bell`, `mm-icon-circle`, `mm-dot-live`, `mm-group` |
| Hero a sekce | `mm-hero-full`, `mm-hero-overlay`, `mm-hero-blobs`, `mm-hero-grid`, `mm-hero-curve` |
| Animovaná pozadí | `mm-anim-gradient`, `mm-anim-orb`, `mm-anim-mesh`, `mm-anim-aurora`, `mm-anim-waves` + `mm-anim-subtle` / `mm-anim-bold` |
| Text a nadpisy | `mm-text-gradient`, `mm-text-highlight`, `mm-text-eyebrow`, `mm-counter` |
| Animace při scrollu | `mm-fade-up`, `mm-zoom-in`, `mm-blur-in`, `mm-stagger`, `mm-delay-1…5`, `mm-slow` |
| Pozadí | `mm-bg-dots`, `mm-bg-lines`, `mm-bg-mesh`, `mm-bg-noise` |

Úplný seznam s náhledy: **WP administrace → MarMal Efekty**.

---

## Použití v Breakdance

1. Otevři galerii (menu **MarMal Efekty**, nebo odkaz v horní liště webu).
2. Klikni na název třídy, zkopíruje se.
3. V Breakdance vyber prvek a vlož třídu do pole **Classes**. Víc tříd odděl mezerou,
   např. `mm-card-lift mm-fade-up mm-delay-2`.

Kam třídu dát:

- **Tlačítka** – na element Button. Efekt se sám propíše na odkaz uvnitř.
- **Obrázky** – na element Image. Efekt míří na `<img>` uvnitř.
- **Hero** – na element Section.
- **Zvýraznění části nadpisu** – v textu nadpisu obal slova do
  `<span class="mm-text-highlight">…</span>`.
- **`mm-stagger`, `mm-cols-dividers`, `mm-cols-focus`** – na rodiče, ne na jednotlivé sloupce.

Tip: hover efekt a scroll animaci nedávej na stejný prvek (obě používají `transform`).
Animaci dej na obal, hover na kartu uvnitř.

### Barvy pro konkrétní web

**MarMal Efekty → Nastavení** má dva režimy:

- **Automaticky z Breakdance** – každá barva knihovny (1, 2, 3, tmavá, světlá) je napojená
  na globální barvu Breakdance (Brand, Odkazy, Nadpisy, Pozadí…) nebo na barvu z palety.
  Změníš barvu v Breakdance → efekty se přebarví samy. Vlastní barva slouží jako záloha.
- **Ručně** – barvy zadáš sám v color pickeru.

Výchozí napojení: Barva 1 = Brand, Barva 2 = Odkazy, Barva 3 = Brand hover,
Tmavá = Nadpisy, Světlá = Pozadí. Každou můžeš přepojit v rozbalovacím seznamu.

Pro jeden prvek můžeš barvy přepsat přímo v Breakdance (Custom CSS prvku):

```css
%%SELECTOR%% { --mm-accent: #e11d48; --mm-accent-2: #f59e0b; }
```

Proměnné: `--mm-accent`, `--mm-accent-2`, `--mm-accent-3`, `--mm-light`, `--mm-dark`, `--mm-radius`, `--mm-speed`,
`--mm-distance` (jak daleko animace vyjíždí), `--mm-reveal-dur` (délka animace),
`--mm-anim-o` (síla animovaného pozadí 0–1), `--mm-anim-s` (rychlost pozadí, 2 = 2× pomaleji), `--mm-live` (barva živé tečky).

---

## Jak přidat nový efekt

1. Do `assets/css/mm-effects.css` přidej CSS do správné sekce. Třída začíná `mm-`.
2. Do `data/effects.json` přidej záznam, aby se ukázal v galerii:

```json
{ "cls": "mm-card-neco", "cat": "karty", "name": "Český název", "desc": "Co to dělá a kam to dát.", "demo": "card" }
```

- `cat`: `ramecky`, `tlacitka`, `karty`, `obrazky`, `ikony`, `hero`, `animbg`, `text`, `scroll`, `pozadi`
- `demo` (jak vypadá náhled): `box`, `btn`, `link`, `card`, `card-dark`, `card-glass`, `cols`,
  `img`, `hero`, `hero-photo`, `heading`, `heading-span`, `eyebrow`, `counter`, `bg`, `bg-color`,
  `anim`, `anim-dark`, `icon` (+ `"icon": "bell"|"arrow"|"arrow-down"|"sun"`), `live`, `group`
- volitelně `"js": true` (potřebuje skript), `"replay": true` (tlačítko Přehrát v galerii)

3. V `marmal-effects.php` zvyš `Version:` (např. 1.1.0 → 1.2.0).
4. Commit a push. Hotovo – viz níže.

---

## Jak fungují aktualizace

1. Pushneš změny do větve `main`.
2. GitHub Action (`.github/workflows/release.yml`) přečte verzi z hlavičky pluginu.
   Když ještě neexistuje release s touto verzí, sestaví `marmal-effects.zip`
   a vydá release `vX.Y.Z`.
3. Weby s pluginem se na GitHub ptají dvakrát denně (knihovna
   [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker)).
   Nová verze se ukáže v Pluginech jako běžná aktualizace. Se zapnutými
   automatickými aktualizacemi se nainstaluje sama.

Když nezvýšíš verzi, nic se nevydá a weby nic nestáhnou.
Hned zkontrolovat: v seznamu pluginů u MarMal Effects klikni na **Check for updates**.

### Soukromý repozitář

Veřejný repozitář je nejjednodušší (je to jen CSS). Pokud chceš soukromý:
na GitHubu vytvoř token (Settings → Developer settings → Fine-grained tokens,
přístup jen k tomuto repozitáři, oprávnění Contents: Read-only) a na každém webu
přidej do `wp-config.php`:

```php
define( 'MM_EFFECTS_GITHUB_TOKEN', 'github_pat_...' );
```

---

## Struktura

```
marmal-effects/
├── marmal-effects.php          hlavička + pojistka (když běží modul v Breakdance Plus, nic nenačte)
├── includes/plugin.php         tělo pluginu (aktualizace, načtení, admin)
├── includes/settings.php       nastavení barev (ručně / z Breakdance)
├── assets/css/mm-effects.css   všechny efekty
├── assets/js/mm-effects.js     scroll animace, počítadla, náklon, parallax
├── assets/admin/               galerie náhledů
├── data/effects.json           seznam efektů pro galerii
├── lib/plugin-update-checker/  aktualizace z GitHubu (MIT)
└── .github/workflows/release.yml
```

Přístupnost: kdo má v systému zapnuté „omezit pohyb“, uvidí vše hned a bez animací.
V editoru Breakdance se animované prvky neschovávají, aby šly upravovat.
