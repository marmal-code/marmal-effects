# Návod: z GitHubu plugin s automatickými aktualizacemi

Jednorázové nastavení asi na 20 minut. Potom už jen upravuješ soubory a pushuješ.

## 1. Přepni repozitář na veřejný

Repozitář `marmal-code/marmal-effects` je teď soukromý. Weby by si z něj aktualizace
nestáhly (musel bys na každý web vkládat přístupový token). V pluginu je jen CSS a JS,
nic tajného, proto ho přepni na veřejný:

1. Otevři repozitář na GitHubu → nahoře záložka **Settings**.
2. Sjeď úplně dolů do části **Danger Zone** → **Change visibility** → **Change to public**.
3. Potvrď (GitHub se zeptá na název repozitáře, napiš `marmal-code/marmal-effects`).

## 2. Rozbal plugin v počítači

1. Stáhni `marmal-effects-1.1.0.zip` (máš ode mě).
2. Pravým tlačítkem → **Extrahovat vše**. Vznikne složka `marmal-effects`.
3. Otevři ji. Uvnitř musí být mimo jiné `marmal-effects.php`, složky `assets`, `data`,
   `includes`, `lib` a složka **`.github`**.

## 3. Nahraj soubory přes web GitHubu

1. Otevři `https://github.com/marmal-code/marmal-effects`.
2. Na prázdném repozitáři klikni na odkaz **uploading an existing file**
   (nebo nahoře **Add file → Upload files**).
3. Ve Windows Průzkumníku označ ve složce `marmal-effects` **všechno** (Ctrl + A)
   a přetáhni to do okna prohlížeče. Přetahuj **obsah** složky, ne složku samotnou,
   aby byl `marmal-effects.php` v kořeni repozitáře.
4. Počkej, až se načtou všechny soubory (asi 60).
5. Dole do **Commit changes** napiš `První verze 1.1.0`, nech vybrané
   **Commit directly to the main branch** a klikni na **Commit changes**.
6. Zkontroluj, že je v repozitáři vidět složka `.github`. Když chybí
   (některé prohlížeče ji při přetahování vynechají):
   **Add file → Create new file**, do názvu napiš `.github/workflows/release.yml`,
   do obsahu vlož celý text souboru `release.yml` (máš ho ode mě zvlášť) a klikni na **Commit changes**.

## 4. Zkontroluj, že vznikl release

1. V repozitáři otevři záložku **Actions**. Běží (nebo proběhl) workflow **Release**,
   zelená fajfka = v pořádku. Trvá to asi minutu.
2. Na hlavní stránce repozitáře vpravo v části **Releases** je `v1.1.0` se souborem
   `marmal-effects.zip`.

Když Action spadne s chybou oprávnění: **Settings → Actions → General → Workflow permissions →
Read and write permissions → Save**, pak v Actions otevři neúspěšný běh a klikni na **Re-run all jobs**.

## 5. Instalace na web

1. Stáhni `marmal-effects.zip` z releasu (nebo použij ZIP, který máš ode mě).
2. WordPress → **Pluginy → Instalace pluginů → Nahrát plugin** → vyber ZIP → **Instalovat** → **Aktivovat**.
3. V seznamu pluginů u MarMal Effects klikni na **Povolit automatické aktualizace**.
4. **MarMal Efekty → Nastavení**: nech „Automaticky z Breakdance“, nebo přepni na „Ručně“ a zadej barvy.

Opakuj na každém webu. Od teď se všechny aktualizují samy.

## 6. Jak vydáš novou verzi

1. Uprav soubory (nový efekt do `assets/css/mm-effects.css` + záznam do `data/effects.json`).
   Nejjednodušší je poslat zadání Claudovi a nechat si připravit upravené soubory.
2. V `marmal-effects.php` zvyš `Version:` – oprava 1.1.0 → 1.1.1, nové efekty 1.1.0 → 1.2.0.
3. Na GitHubu **Add file → Upload files**, přetáhni změněné soubory do správné složky
   (nebo otevři soubor → ikona tužky → vlož nový obsah) → **Commit changes**.
4. Action sama vydá release. Weby ho najdou do 12 hodin; hned to vynutíš odkazem
   **Check for updates** u pluginu v seznamu pluginů.
5. Stejný ZIP nahraj i do aplikace (stránka „plugin“ → MarMal Effects → Nahrát novou verzi),
   ať se na nové weby instaluje aktuální verze.

Bez zvýšení verze se nic nevydá. To je záměr: můžeš pushovat rozpracované věci
a vydat je, až zvedneš verzi.

## Když něco nefunguje

| Problém | Řešení |
|---|---|
| Aktualizace se neukazuje | Zvýšil jsi `Version`? Existuje release v GitHubu? Klikni „Check for updates“. |
| Adresa repa | V `marmal-effects.php` musí být správné jméno účtu a `/` na konci. |
| Po aktualizaci se změna neprojevila | Vymaž cache (cache plugin, Breakdance → Settings → Tools → Regenerate CSS, Cloudflare). |
| Animovaný prvek je na webu neviditelný | Zkontroluj, že je v Nastavení zapnutý JavaScript. |
| Efekt na tlačítku nefunguje | Třída patří na element Button, ne na jeho rodiče. |
