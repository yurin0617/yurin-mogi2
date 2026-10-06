## アプリケーション名
BookShelf（書籍レビュー・管理アプリケーション）

## 環境構築
```bash
# 1. リポジトリからダウンロード
git clone <repository-url>
cd <repository-directory>

# 2. 環境変数ファイルの作成
cp .env.example .env

# 3. .env ファイルの DB 設定を変更
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

# 4. dockerコンテナを構築
docker-compose up -d --build

# 5. phpコンテナにログインしてLaravelをインストール
docker-compose exec php bash
composer install

# 6. アプリケーションキーを作成
php artisan key:generate

# 7. DBのテーブルを作成
php artisan migrate

# 8. DBのテーブルにダミーデータを投入
php artisan db:seed

# "The stream or file could not be opened" エラーが発生した場合
# storageディレクトリに権限を設定
chmod -R 777 storage

# 9. アップロードした画像をみれるようにするためシンボリックリンクを作成
php artisan storage:link
```

## テスト環境
```
# 1. テストDB用の環境ファイルを作成
cp .env .env.testing

# 2. .env.testing ファイルの文頭部分にある設定を編集
APP_ENV=testing
APP_KEY=
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

# 3. テスト用のアプリケーションキーを生成
php artisan key:generate --env=testing

# 4. キャッシュのクリアとテスト用マイグレーション・シーダーの実行
php artisan config:clear
php artisan migrate --env=testing
php artisan db:seed --env=testing

# 5. テストの実行
php artisan test
```

## 使用技術(実行環境)
```
- PHP: 8.5.11
- フレームワーク: Laravel 10.50.3
- データベース: MySQL 8.0.46
- インフラ・環境構築: Docker / Laravel Sail
- API認証・連携: Laravel Sanctum / RESTful API (V1)
- コード品質・規約チェック: Laravel Pint
- フロントエンド: Blade / Tailwind CSS
```

## URL
```
ログイン画面：http://localhost/login
ユーザー登録画面：http://localhost/register
書籍一覧画面：http://localhost/books （または http://localhost）
```

## ER図
![ER図](ER.drawio.png)

## 動作確認・テスト用のデフォルトユーザー
```
email：test@example.com
password：password123
```