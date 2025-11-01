# ER図（正規化後：画像・タグは既存のまま）

以下はMermaidのER図です。VS Codeのプレビューで表示するには、このファイルを開いて右クリック→Open Preview（または Cmd+K, V）してください。図にならない場合は「Markdown Preview Mermaid Support」拡張を入れてください。

```mermaid
erDiagram
  MEMOS ||--|| WEATHER_CONDITIONS : "1:1"
  MEMOS ||--|| RIVER_CONDITIONS : "1:1"
  MEMOS ||--o{ BAITS : "1:N"
  MEMOS ||--o{ CATCHES : "1:N"

  MEMOS {
    bigint id PK
    date   fishing_date
    time   fishing_time_start
    time   fishing_time_end
    varchar fishing_spot
    text   content
    timestamps timestamps
  }

  WEATHER_CONDITIONS {
    bigint id PK
    bigint memo_id FK "UNIQUE, -> memos.id"
    enum weather_code "sunny,cloudy,rain,sunny_cloudy,sunny_rain,cloudy_sunny,cloudy_rain,rain_sunny,rain_cloudy,other (nullable)"
    tinyint wind_speed_min "unsigned, nullable"
    tinyint wind_speed_max "unsigned, nullable"
    enum wind_direction "N,NE,E,SE,S,SW,W,NW (nullable)"
    timestamps timestamps
  }

  RIVER_CONDITIONS {
    bigint id PK
    bigint memo_id FK "UNIQUE, -> memos.id"
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

  %% 天気マスタ／ピボットは廃止し、天気は WEATHER_CONDITIONS.weather_code に保持します。
```

補足
- 天気＋風（WEATHER_CONDITIONS）と川の状態（RIVER_CONDITIONS）は、それぞれメモ（MEMOS）と1:1です。
- エサ（BAITS）/ 釣果（CATCHES）は1:Nで position により表示順管理（UNIQUE(memo_id, position) 推奨）。
- 天気は weather_code として WEATHER_CONDITIONS に直接格納します（UIのセレクト値と一致）。
- 画像・タグ系は既存のままなので本図には含めていません（必要であれば追記可能）。
