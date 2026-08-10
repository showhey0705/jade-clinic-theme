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

### CSS の minify 配信(A方式 — 本番のみ)

`style.css` と `assets/styles/japanese-typography.css` は、本番だけ minify 版(`.min.css`)が配信される(WordPress コアと同じ `.min` 並置方式)。

- **開発時**(`WP_DEBUG` / `SCRIPT_DEBUG` が true — DevKinsta はこれ)は**素の CSS がそのまま配信される**ので、普段は「CSS を編集 → リロード」だけでよい。ビルド不要
- **本番**では `functions.php` の `minified_css()` が `.min.css` に切り替える。`.min` が未生成の環境では素ファイルにフォールバックするので壊れない
- **リリース時の約束**: 上記 2 ファイルを変更したら、コミット前に必ず再生成して `.min.css` も一緒にコミットする:

```bash
npm ci             # 初回のみ(package-lock.json どおりに esbuild が入る)
npm run build:min  # style.min.css / japanese-typography.min.css を再生成
```

`node_modules/` は git 管理外、`package-lock.json` は管理下。`npm install` ではなく **`npm ci`** を使う(ロックを書き換えないので、どの環境でも同じ依存が入る)。`npm ci` が `package.json and package-lock.json are in sync` で落ちたら、依存を足したのに lock を更新していない状態 —— `npm install` を 1 回走らせて **lock もコミット**する。

⚠️ 再生成を忘れると**ローカルでは直っているのに本番だけ古いスタイルのまま**になる(逆パターンの白画面リスクはフォールバックがあるので無い)。対象ファイルを触った PR には `.min.css` の diff が含まれているはず、をレビュー観点にすると安全。

### リリース前チェック

テーマもプラグイン同様、**リリースのたびに 1 度ドキュメントを見直す**。

1. `style.css` ヘッダの `Version` を bump したか(`functions.php` の `version()` はここを読むので二重管理は無い)
2. CSS/JS を触ったなら `npm run build:min` 済みで `.min.css` もコミットしたか
3. **この README が実装と食い違っていないか** —— 撤去した方式の説明が残っていないか / 新しい落とし穴を書いたか / コマンドがコピペでそのまま動くか
4. 翻訳文字列を足したなら `languages/ja.po` → `.mo` を再生成したか

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
