=== Life Stages ===
Requires at least: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later

Audience "stages" for a personal finance blog: Starting Out, Growing, Building Wealth.

== What it adds ==
* Posts → Stages: tick one or more stages on each post (separate from topic categories).
* "Learning path" box on each post: its position in that stage's "Read these first" list.
* A "Next step in your stage" card at the end of every article.
* Shortcodes:
  [ls_path stage="starting-out"]   ordered learning path
  [ls_signup stage="growing" title="..." text="..." button="..."]   stage-tagged newsletter form
  [ls_tools]   SIP, inflation and 60/40 rebalancing calculators
* Settings → Life Stages: paste one newsletter form URL per stage (e.g. Kit forms that add
  the stage tag). Empty = demo mode.
* Settings → Life Stages → "Import sample content": 13 articles, 9 pages, categories,
  stages and the header menu. Safe to run twice. WP-CLI: wp life-stages import-demo
