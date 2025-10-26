# 釣りアプリ — ER図 (Mermaid)

以下は本アプリのコアデータモデルのER図（Mermaid記法）です。polymorphic（多態）関係を使うテーブル（photos, comments, likes）は複数のエンティティに紐づくことを注記しています。

```mermaid
erDiagram
    USERS {
        BIGINT id PK
        VARCHAR name
        VARCHAR username
        VARCHAR email
        VARCHAR avatar_url
        BOOLEAN share_location
        JSON privacy_settings
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    SPOTS {
        BIGINT id PK
        UNSIGNED_BIGINT created_by FK
        VARCHAR name
        DECIMAL latitude
        DECIMAL longitude
        BOOLEAN is_public
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    TRIPS {
        BIGINT id PK
        UNSIGNED_BIGINT user_id FK
        UNSIGNED_BIGINT spot_id FK
        DATETIME started_at
        DATETIME ended_at
        VARCHAR method
        BOOLEAN is_public
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    CATCHES {
        BIGINT id PK
        UNSIGNED_BIGINT user_id FK
        UNSIGNED_BIGINT trip_id FK
        UNSIGNED_BIGINT species_id FK
        DECIMAL length_cm
        DECIMAL weight_kg
        UNSIGNED_BIGINT tackle_id FK
        DATETIME caught_at
        DECIMAL latitude
        DECIMAL longitude
        BOOLEAN released
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    SPECIES {
        BIGINT id PK
        VARCHAR common_name
        VARCHAR scientific_name
        VARCHAR family
        VARCHAR image_url
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    TACKLES {
        BIGINT id PK
        UNSIGNED_BIGINT user_id FK
        VARCHAR name
        VARCHAR type
        JSON details
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    PHOTOS {
        BIGINT id PK
        VARCHAR photoable_type
        UNSIGNED_BIGINT photoable_id
        VARCHAR path
        VARCHAR thumb_path
        JSON exif
        UNSIGNED_BIGINT uploaded_by FK
        BOOLEAN is_public
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    TAGS {
        BIGINT id PK
        VARCHAR name
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    CATCH_TAG {
        UNSIGNED_BIGINT catch_id FK
        UNSIGNED_BIGINT tag_id FK
    }

    COMMENTS {
        BIGINT id PK
        UNSIGNED_BIGINT user_id FK
        VARCHAR commentable_type
        UNSIGNED_BIGINT commentable_id
        TEXT body
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    LIKES {
        BIGINT id PK
        UNSIGNED_BIGINT user_id FK
        VARCHAR likeable_type
        UNSIGNED_BIGINT likeable_id
        TIMESTAMP created_at
    }

    %% Relationships
    USERS ||--o{ SPOTS : "creates"
    USERS ||--o{ TRIPS : "logs"
    USERS ||--o{ CATCHES : "records"
    USERS ||--o{ TACKLES : "owns"
    USERS ||--o{ PHOTOS : "uploads"
    USERS ||--o{ COMMENTS : "writes"
    USERS ||--o{ LIKES : "gives"

    SPOTS ||--o{ TRIPS : "hosts"
    TRIPS ||--o{ CATCHES : "includes"
    CATCHES }o--|| SPECIES : "is_a"
    TACKLES ||--o{ CATCHES : "used_in"

    %% many-to-many between catches and tags (pivot)
    CATCHES }o--o{ CATCH_TAG : "pivot"
    TAGS }o--o{ CATCH_TAG : "pivot"

    %% Polymorphic relations (photos, comments, likes)
    PHOTOS }o--|| CATCHES : "attached_to (polymorphic)"
    PHOTOS }o--|| TRIPS : "attached_to (polymorphic)"
    PHOTOS }o--|| SPOTS : "attached_to (polymorphic)"

    COMMENTS }o--|| CATCHES : "comment_on (polymorphic)"
    COMMENTS }o--|| TRIPS : "comment_on (polymorphic)"
    COMMENTS }o--|| SPOTS : "comment_on (polymorphic)"

    LIKES }o--|| CATCHES : "like (polymorphic)"
    LIKES }o--|| PHOTOS : "like (polymorphic)"
    LIKES }o--|| TRIPS : "like (polymorphic)"
```

注記:
- 上図では主キー(PK)・外部キー(FK)・主要フィールドを簡潔に示しています。実際のマイグレーションでは型・制約・インデックスを環境（MySQL/Postgres）に合わせて調整してください。
- photos, comments, likes は polymorphic（photoable/commentable/likeable）で複数のモデルに紐づきます。Mermaidでは補助的に複数の関連線で表現しています。
- 多対多は catch_tag のようなピボットテーブルで表現します。
