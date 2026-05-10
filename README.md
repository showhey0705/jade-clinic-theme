# JADE CLINIC Theme (vip2026)

Ollie 親テーマをベースにした WordPress 子テーマ。jadeclinic.jp(美容皮膚科クリニック)向けのデプロイで稼働しているが、テーマ本体は**汎用 Ollie 子テーマ**として設計されており、jadeclinic 固有のロジックは `inc/jadeclinic.php` に隔離されている。

- **表示名 (Theme Name)**: JADE CLINIC
- **内部 slug / namespace / textdomain**: `vip2026`(他サイト転用を見据えたまま)
- **親テーマ**: [Ollie](https://wordpress.org/themes/ollie/)
- **PHP**: 7.4+ / **WordPress**: 6.6+

---

## 設計思想

「**汎用部分は `inc/` 直下、サイト固有部分は `inc/{site}.php` に隔離**」。

`functions.php` の末尾で `inc/jadeclinic.php` を読み込んでおり、別サイトに転用するときはこの `require_once` 1 行を外せばクリニック固有のロジック(FB Pixel ドメイン認証 / MedicalClinic JSON-LD / femcare LP のヘッダフッタ非表示)が全て止まる構造。

---

## ディレクトリ構成

```
vip2026/
├── functions.php          子テーマ初期設定 / Adobe Fonts / inc/* の読み込み
├── style.css              子テーマヘッダ + 補助 CSS
├── theme.json             ブロックエディタ設定(色 / タイポ / グラデ)
├── screenshot.png         テーマ一覧用サムネイル
├── inc/
│   ├── jadeclinic.php     jadeclinic.jp 専用(別サイト転用時はここを切り離す)
│   ├── block-styles.php   子テーマ独自のブロックスタイル登録
│   ├── editor-controls.php エディタ UX(タイポ/スペーシング/シャドウ/枠線を常時表示)
│   └── pattern-styles.php  assets/styles/patterns/{block}--{class}.css の自動 enqueue
├── patterns/              ブロックパターン(tabs / horizontal-scroll / card-stack ほか)
├── templates/             FSE テンプレート(404 / single / archive / カスタム CPT 用 ほか)
├── assets/
│   ├── styles/            ブロックパターン専用 CSS / 日本語タイポグラフィ
│   ├── fonts/
│   └── js/
└── languages/             ja.po / ja.mo / ja.l10n.php(textdomain: vip2026)
```

---

## 主要機能

### Adobe Fonts(Typekit)

`functions.php` で kit ID `bzy5pnl` を enqueue。`wp_resource_hints` フィルタで `use.typekit.net` / `p.typekit.net` への preconnect + crossorigin を付与する。

### パターン専用 CSS / JS の規約ベース自動ロード(`inc/pattern-styles.php`)

`assets/styles/patterns/{block}--{class}.css` を起動時にスキャンし、`is-style-{class}` がレンダリング HTML 内に出現したときだけ条件付き enqueue する。

### jadeclinic.jp 専用処理(`inc/jadeclinic.php`)

| 機能 | 説明 |
|---|---|
| Facebook ドメイン認証 | `<meta name="facebook-domain-verification">` を `wp_head` に出力 |
| MedicalClinic JSON-LD | 院情報 / 営業時間 / 提供サービス / 連絡先などを構造化データで出力 |
| femcare LP のクローム非表示 | `/femcare/` 固定ページではテーマのヘッダ・フッタ・ナビを CSS で `display:none` |

### カスタム CPT / タクソノミー対応テンプレート

- `archive-case-gallery.html` / `single-case-gallery.html`
- `archive-director-blog.html` / `single-director-blog.html`
- `taxonomy-dr-tags.html`

> 上記 CPT(`case-gallery`、`director-blog`)とタクソノミー(`dr-tags`)の登録元はこのテーマ外(別プラグイン側)。

---

## 開発

### バージョン管理

`style.css` のヘッダ `Version` と `functions.php` の `VERSION` 定数の **両方を必ず一致** させる。PR を作る際は version bump コミットを 1 本含めること。

### 翻訳

textdomain は `vip2026`(変更しない)。文字列を追加・変更したら `languages/ja.po` を更新し、`wp i18n make-mo` で `.mo` を再生成する。

---

## 別サイトへの転用

1. `vip2026` ディレクトリを別サイトの `wp-content/themes/` 配下にコピー
2. `functions.php` の末尾から `require_once .../inc/jadeclinic.php` を削除(または `inc/jadeclinic.php` 自体を別ファイル名で複製してロジックを差し替え)
3. `style.css` ヘッダの `Theme Name` / `Description` / `Theme URI` / `Author` を新サイト向けに差し替え
4. `screenshot.png` を新サイト向けに差し替え

内部 slug(`vip2026`)・namespace(`VIP2026`)・textdomain(`vip2026`)・enqueue handle(`vip2026-*`)は意図的に汎用名のまま据え置いている。

---

## ライセンス

GPL v2 or later
