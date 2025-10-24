# ER図（正規化後：画像・タグは既存のまま）

以下はMermaidのER図です。VS Codeのプレビューで表示するには、このファイルを開いて右クリック→Open Preview（または Cmd+K, V）してください。図にならない場合は「Markdown Preview Mermaid Support」拡張を入れてください。

```mermaid
erDiagram
  MEMOS ||--|| CONDITIONS : "1:1"
  MEMOS ||--o{ BAITS : "1:N"
  MEMOS ||--o{ CATCHES : "1:N"
  MEMOS ||--o{ WEATHER_TYPE_MEMO : "1:N"
  WEATHER_TYPES ||--o{ WEATHER_TYPE_MEMO : "1:N"

  MEMOS {
    bigint id PK
    date   fishing_date
    time   fishing_time_start
    time   fishing_time_end
    varchar fishing_spot
    text   content
    timestamps timestamps
  }

  CONDITIONS {
    bigint id PK
    bigint memo_id FK "UNIQUE, -> memos.id"
    tinyint wind_speed_min "unsigned, nullable"
    tinyint wind_speed_max "unsigned, nullable"
    enum wind_direction "N,NE,E,SE,S,SW,W,NW (nullable)"
    boolean has_flow "nullable"
    enum water_clarity "clear,slightly,turbid,very_turbid (nullable)"
    enum underwater_debris "none,slightly,present (nullable)"
    decimal water_level "nullable"
    tinyint water_temp "unsigned, nullable"
    timestamps timestamps
  }

  BAITS {
    bigint id PK
    bigint memo_id FK "-> memos.id"
    varchar name
    tinyint position "unsigned, default 0"
    timestamps timestamps
    %% UNIQUE(memo_id, position)
  }

  CATCHES {
    bigint id PK
    bigint memo_id FK "-> memos.id"
    varchar name
    smallint count "unsigned, default 0"
    smallint length_cm "unsigned, nullable"
    tinyint position "unsigned, default 0"
    timestamps timestamps
    %% UNIQUE(memo_id, position)
  }

  WEATHER_TYPES {
    bigint id PK
    varchar(20) code "UNIQUE (sunny, cloudy, rain, other)"
    varchar(50) label "nullable"
    timestamps timestamps
  }

  WEATHER_TYPE_MEMO {
    bigint weather_type_id FK "-> weather_types.id"
    bigint memo_id FK "-> memos.id"
    %% PRIMARY(weather_type_id, memo_id)
  }
```

補足
- 条件（CONDITIONS）はメモ（MEMOS）と1:1です。
- エサ（BAITS）/ 釣果（CATCHES）は1:Nで position により表示順管理（UNIQUE(memo_id, position) 推奨）。
- 天気（WEATHER_TYPES）はマスタ、weather_type_memo はピボットです。
- 画像・タグ系は既存のままなので本図には含めていません（必要であれば追記可能）。
