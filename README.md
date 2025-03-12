# 制作ストップ中

育児支援アプリ開発概要

📋 機能概要 1.基本機能

- ユーザー認証（親アカウント、家族アカウント）
- 子どもプロフィール管理（複数子ども対応）
- 成長記録（身長、体重、写真）
- 予防接種スケジュール管理
- 日々の記録機能

  2.日々の記録機能

- 授乳記録（時間、量、メモ）
- 睡眠記録
- おむつ替え記録
- 体温記録
- 離乳食記録

```
プロジェクトセットアップ
composer create-project laravel/laravel baby-care-app "11.*"
npm init vue@latest
```

```
php.ini ファイル確認コマンド
php -i | findstr "php.ini"
```

```
PowerShell でファイル作成
New-Item -Path "c:\Users\Owner\Desktop\git\アプリ開発\baby-care-app\backend\laravel\routes\api.php" -ItemType File
```

```
テストファイル作成
php artisan make:test ChildApiTest --pest
```
