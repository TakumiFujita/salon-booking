<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メールアドレス確認</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f7fafc;
            color: #2d3748;
            padding: 50px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .message {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .button {
            background-color: #3182ce;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            display: inline-block;
            margin-top: 10px;
        }

        .button:hover {
            background-color: #2b6cb0;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            メールアドレスの確認が必要です
        </div>

        <div class="message">
            ご登録いただいたメールアドレスに確認メールが送信されました。確認メールに記載されたリンクをクリックして、メールアドレスの確認を完了してください。
        </div>

        <div class="message">
            もし確認メールが届いていない場合、下記のボタンをクリックして再送信してください。
        </div>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="button">確認メールを再送信</button>
        </form>

        @if (session('message'))
            <div class="message" style="color: green; margin-top: 20px;">
                {{ session('message') }}
            </div>
        @endif
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} あなたのサイト名</p>
    </div>

</body>

</html>
