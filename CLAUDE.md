# BCP Theme (vip2026) — Claude 作業メモ

> **5 リポジトリ共通の規約（同期の向き・バージョンの正・着手前の確認・落とし穴）は
> `vip2026-starter/docs/PROJECT_RULES.md` が唯一の正。** ここには JADE テーマ固有の
> 配信（beta / stable の 2 チャネル）と 1 リポ N サイトの運用だけを書く。

Ollie 親テーマの子テーマ。美容クリニック向けの**製品テーマ**で、
Beauty Clinic Patterns (BCP) プラグインと対で使う。

- **表示名**: BCP Theme
- **内部 slug / ディレクトリ / textdomain**: `vip2026`
- **リポジトリ**: `showhey0705/jade-clinic-theme`

表示名と内部 slug が違うのは意図的。`vip2026` は**プラットフォーム**の名前
(VIPCREW の 2026 年版 Ollie 子テーマ基盤)、`BCP Theme` は**業種パッケージ**の名前。
将来クリニック以外の業種を同じ基盤で出すときに分岐できるようにしてある。

**ディレクトリ名 `vip2026` は変えないこと。** 配信 zip はこの名前で展開される。
変えると WordPress が「別のテーマ」として二重にインストールし、既存サイトは
`wp_options.stylesheet` を書き換えるまでテーマが外れる。

---

## 1 リポジトリ N サイト

このテーマは**全クリニックで共通**。サイトごとにリポジトリを分けない。

2026-08-18 に jade → one-est の手動同期を実際にやって確認したところ、
**131 ファイル中 125 が完全一致**で、実質的な差は「テーマヘッダ 4 行」と
「既定サイト slug 1 行」だけだった。この程度の差のためにリポジトリを増やすと、
共通改修のたびにサイトの数だけ cherry-pick が要る。クリニックが 10 院、50 院に
なった時点で破綻する。

サイト固有のものは 3 箇所に閉じ込める。

| 置き場所 | 例 |
|---|---|
| `wp-config.php` の `VIP2026_SITE` 定数 | どのサイトモジュールを読むか |
| `inc/sites/<slug>.php` | SEO / トラッキング / 構造化データ |
| `styles/*.json` (スタイルバリエーション) | ブランドカラー、タイポグラフィ |

ブランドの見た目そのものは**データベース側**(グローバルスタイル、ページ、
メニュー、画像)にある。テーマは器で、サイトごとの塗りは DB。だから器を
1 本にして配れる。

新しいサイトを立てるときは、`wp-config.php` に 1 行入れるだけでよい。

    define( 'VIP2026_SITE', 'clinic-slug' );

未設定なら既定は `jadeclinic`。`'none'` を指定すると何も読まない
(素の汎用テーマとして動く)。

---

## 配信 (自動更新)

BCP プラグインと**同じ経路・同じライブラリ**。Supabase Storage に zip と
メタデータ JSON を置き、各サイトの更新チェッカーが 12 時間ごとに取りに行く。

| | 置き場所 |
|---|---|
| zip | `plugin-releases/themes/vip2026/vip2026.zip` |
| stable メタデータ | `plugin-releases/themes/vip2026/theme.json` |
| beta メタデータ | `plugin-releases/themes/vip2026/beta/theme.json` |

ライブラリは YahnisElsts Plugin Update Checker v5.6 (`vendor/`)。
プラグインとテーマの両方に対応していて、テーマディレクトリを渡すと
`Theme\UpdateChecker` が返る。実装は `inc/update-checker.php`。

### 2 チャネル (stable / beta)

**テーマの自動更新はプラグインより怖い。** CSS の一手で全クライアントの
見た目が同時に壊れるため。そこで配信先を 2 系統に分けてある。

| タグ | 配信先 | 届く相手 |
|---|---|---|
| `v0.13.0` | stable + beta | 全サイト (顧客サイト含む) |
| `v0.13.0-beta.1` | beta のみ | `VIP2026_UPDATE_CHANNEL = 'beta'` のサイトだけ |

先行検証したいサイトの `wp-config.php` に:

    define( 'VIP2026_UPDATE_CHANNEL', 'beta' );

jadeclinic のローカルは beta に設定済み。

### リリース手順

    bash scripts/bump-version.sh 0.14.0
    npm ci && npm run build:min
    git add -A && git commit -m "chore(release): v0.14.0"
    git push origin main

    git tag v0.14.0-beta.1 && git push origin v0.14.0-beta.1   # まず beta
    # beta のサイトで確認してから
    git tag v0.14.0 && git push origin v0.14.0                  # stable へ

**版数は必ず `scripts/bump-version.sh` を通す。** `style.css` のヘッダと
`supabase/theme.json` の両方を更新する。片方だけ直すとワークフローの
`Verify version matches tag` で落ちる (BCP で 3 回やらかしている)。

### CI が止めてくれること

`.github/workflows/release.yml` はタグ push で発火し、以下を機械的に検査する。

1. **版数がタグと一致しているか** — `style.css` と `supabase/theme.json`
2. **min アセットが最新か** — `npm run build:min` を走らせて差分が出たら落とす。
   2026-08-18 に、コミット `9793726` が `style.css` だけ直して `style.min.css` を
   作り直しておらず、**本番だけロゴの下線抑止が効いていない**という事故が
   実際に起きた。本番は minify 版を配信するので、素の CSS しか見ていないと気づけない。
3. **zip の中身** — `vendor/plugin-update-checker/` や `inc/update-checker.php` が
   入っているか、`node_modules/` や `supabase/` が混入していないか

---

## ハマりどころ

### `.gitignore` で `vendor/` を無視しないこと

**絶対に外してはいけない。** `vendor/plugin-update-checker/` は更新チェッカー
本体で、配信 zip に同梱されている必要がある。ここを無視すると、
**更新チェッカーの入っていないテーマが配信され、一度更新した顧客サイトには
以後二度と更新が届かなくなる。**

2026-08-18 に実際に `vendor/` を一括無視していて、CI の `Verify zip contents` が
堰き止めた。無視してよいのは `/vendor/composer/` だけ (BCP プラグインの
`.gitignore` も同じ方針)。

### 開発機で「テーマを更新」を押さない

WordPress のテーマ更新は「古いディレクトリを削除してから zip を展開する」。
開発機のテーマディレクトリは git リポジトリそのものなので、実行すると
`.git` ごと消える。インストールの検証をしたいときは、git 管理下にない場所
(STG か、配信 zip を展開した新規サイト) で行う。

### zsh に貼り付けるコマンドに `#` コメントを付けない

zsh の**対話シェルは `interactive_comments` が既定 off**。
`git push origin v1.2.3   # 再発火` の `#` 以降がコメントではなく引数として
渡り、`error: src refspec # does not match any` になる。

### Cowork から commit すると `.git/*.lock` が残る

リモート実行側のマウントが `unlink` を許さないため。
`fatal: cannot lock ref 'HEAD'` が出たらローカルのターミナルで先に消す。

    find .git -name '*.lock' -delete

### タグを打ち直すときはリモートを先に消す

    git push origin --delete vX.Y.Z
    git tag -f vX.Y.Z
    git push origin vX.Y.Z

`git tag vX.Y.Z` はローカルに同名タグが残っていると「already exists」で黙って
何もしない。その状態で push しても「up to date」で終わり、タグは古いコミットを
指したまま Actions も再発火しない。

---

## CSS の本番 minify 配信 (A方式)

本番では `style.min.css` と `assets/styles/japanese-typography.min.css` を配信する
(`functions.php` の `minified_css()`)。開発は素の CSS を直接編集し、
リリース前に `npm run build:min` を実行してコミットする。

esbuild のバージョンは `package-lock.json` に固定 (0.27.7)。
別バージョンでビルドすると出力が微妙に変わり、無意味な差分が出る。
必ず `npm ci` してから `npm run build:min` すること。
