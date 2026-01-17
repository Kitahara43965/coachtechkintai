
## アプリケーション名<br>

coachtech勤怠管理アプリ

## 環境構築<br>

(イ) ローカルリポジトリの設定<br>
ローカルリポジトリを作成するディレクトリにおいてコマンドライン上で<br>
$ git clone git@github.com:Kitahara43965/coachtechkintai.git<br>
$ mv coachtechkintai (ローカルリポジトリ名){OS:apple}<br>
$ rename coachtechkintai (ローカルリポジトリ名){OS:windows,コマンドプロンプト}<br>
とすればリモートリポジトリのクローンが生成され、所望のローカルリポジトリ名のディレクトリが得られます。<br>
<br>
(ロ) docker の設定<br>
$ cd (ローカルリポジトリ名)<br>
docker が起動していることを確認した上で<br>
$ docker-compose up -d --build<br>
とします。<br>
$ cd docker/php<br>
$ docker-compose exec php bash<br>
で php コンテナ内に入り、<br>
$ composer install<br>
で composer をインストールします。<br>
<br>
(ハ) web アプリの立ち上げ<br>
(ハ-1) php コンテナ上で<br>
$ cp .env.example .env<br>
と入力し、.env ファイルを複製します。<br>
(ハ-2) .env ファイルで<br>
APP_LOCALE=ja {追加}<br>
DB_HOST=mysql<br>
DB_PORT=3306<br>
DB_DATABASE=laravel_db<br>
DB_USERNAME=laravel_user<br>
DB_PASSWORD=laravel_pass<br>
MAIL_FROM_ADDRESS=noreply@example.com<br>
SESSION_DRIVER=database<br>
とします。<br>

(ハ-3) php コンテナ上で<br>
$ php artisan key:generate<br>
$ php artisan migrate:fresh {もしくは $ php artisan migrate}<br>
$ php artisan db:seed<br>
と入力します。<br>
さらに、<br>
rm public/storage {既存のリンクを削除}<br>
php artisan storage:link {再度リンクの作成}<br>
をすることで web アプリを起動させることができます。<br>
<br>
(二) メール認証について<br>
(二-1) url入力欄に<br>
localhost:8025<br>
を入力するとmailhogに接続されます。<br>
(二-2) アプリでログアウトをせずに最新の届いたメールでメール認証をするとウェブアプリ「coachtech勤怠管理アプリ」に戻ります。<br>
(メールに接続しない場合は「認証はこちらから」ボタンを押下すると、認証が完了します)<br>


## 使用技術(実行環境)<br>

php 8.1<br>
Laravel 8.83.8<br>
mysql 8.0.26<br>
nginx 1.21.1<br>
mailhog v1.0.1<br>

## ER 図<br>



## URL

ホーム画面：http://localhost/attendance<br>
ユーザー登録：http://localhost/register/<br>
phpMyAdmin: http://localhost:8080/<br>
mailhog: http://localhost:8025/<br>

## 管理者ユーザーとしてログイン

管理者はAdminでのログイン[メールアドレス]admintest@mail.com,[パスワード]admintest<br>
一般ユーザーはUserでのログイン[メールアドレス]usertest@mail.com,[パスワード]usertestも可能です<br>






