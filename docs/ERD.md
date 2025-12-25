# Entity Relationship Diagram - Mini E-Commerce API

```mermaid
erDiagram
    USERS ||--o{ COMMENTS : writes
    PRODUCTS ||--o{ IMAGES : has
    PRODUCTS ||--o{ COMMENTS : receives
    
    USERS {
        bigint id PK
        string name
        string email UK
        string password
        enum role
        timestamp created_at
        timestamp updated_at
    }
    
    PRODUCTS {
        bigint id PK
        string title
        text description
        decimal price
        int stock
        timestamp created_at
        timestamp updated_at
    }
    
    IMAGES {
        bigint id PK
        bigint product_id FK
        string path
        timestamp created_at
        timestamp updated_at
    }
    
    COMMENTS {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        text content
        timestamp created_at
        timestamp updated_at
    }
