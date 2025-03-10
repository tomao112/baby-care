CORS とは何か、なぜ必要なのか
CORS の基本概念
CORS（Cross-Origin Resource Sharing、オリジン間リソース共有）は、あるオリジン（ドメイン、プロトコル、ポートの組み合わせ）で実行中のウェブアプリケーションが、別のオリジンのリソースにアクセスするための仕組みです。
ブラウザは「同一オリジンポリシー」という重要なセキュリティ機能を持っており、これによりあるオリジンのウェブページが別のオリジンのリソースにアクセスすることを制限しています。CORS はこの制限を安全に緩和するための標準的な方法です。
なぜ CORS が必要なのか

1. セキュリティ: 同一オリジンポリシーはウェブセキュリティの基本です。これがないと、悪意のあるサイトが他のサイトのデータを勝手に読み取れてしまいます。
   フロントエンドとバックエンドの分離: 現代のウェブ開発では、フロントエンド（Vue.js 等）とバックエンド（Laravel 等）を別々のサーバーで動かすことが一般的です。
   API の公開: 他のドメインからアクセス可能な API を提供する場合に必要です。
   設定ファイルの解説
   cors.php
   重要なポイント
1. paths: CORS を適用するパスを指定します。この例では、api/_（API エンドポイント）と sanctum/csrf-cookie（Laravel Sanctum の認証用）に適用されます。
   allowed_origins: ここでは http://localhost:5173（Vue.js の開発サーバー）からのアクセスのみを許可しています。本番環境では実際のフロントエンドの URL に変更する必要があります。
   supports_credentials: true に設定することで、認証情報（クッキーなど）を含むリクエストを許可します。これはログイン状態を維持するために重要です。
   実際の動作
   ブラウザが CORS 対象のリクエストを送信する前に、「プリフライトリクエスト」（OPTIONS）を送信します。
   サーバーはこのリクエストに対して、許可するオリジン、メソッド、ヘッダーなどを含むレスポンスを返します。
   ブラウザはこのレスポンスを見て、本来のリクエストを送信するかどうかを判断します。
   一般的な問題
   フロントエンドから API にアクセスできない場合、CORS の設定が正しくないことが多いです。
   開発環境と本番環境でオリジンが異なる場合は、両方を allowed_origins に追加するか、環境変数で管理します。
   supports_credentials を true にした場合、allowed_origins に_（ワイルドカード）は使用できません。
   この設定により、Vue.js の開発サーバー（localhost:5173）から Laravel バックエンド（おそらく別のポートで動作）への API リクエストが可能になります。

   ```
   api.phpが認識されない場合
   bootstrap/app.phpに以下の記述を行う
   <?php
   ```

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(**DIR**))
->withRouting(
using: function () {
Route::middleware('api')
->prefix('api')
->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
    ```

````
laravel error log
    tail -f storage/logs/laravel.log
    ```
````
