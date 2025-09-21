# ERP System

従業員管理を中心としたERP システム

## 技術スタック
- Backend: Laravel 11 + MySQL
- Frontend: React + Next.js  
- Infrastructure: Docker
- Database: MySQL 8.0

## 開発環境構築

### 1. Docker環境起動
```bash
docker-compose up -d
```

### 2. Laravel初期化
```bash
docker-compose exec backend composer install
docker-compose exec backend php artisan key:generate
docker-compose exec backend php artisan migrate
```

## 機能一覧
- [ ] 従業員管理
- [ ] 部署管理  
- [ ] 認証・権限管理

## 開発状況
- [x] プロジェクト初期化
- [ ] Docker環境構築
- [ ] Laravel基盤構築
- [ ] フロントエンド基盤構築