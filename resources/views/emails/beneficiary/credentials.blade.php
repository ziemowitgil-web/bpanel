<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twoje dane logowania</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 15px;
        }
        .login-data {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .login-data strong {
            display: inline-block;
            width: 120px;
        }
        .button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #0077cc;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .footer {
            font-size: 12px;
            color: #777777;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }
        .footer a {
            color: #0077cc;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Witaj, {{ $user->name }}!</h1>
    <p>Twoje konto w panelu <strong>eduk@cja</strong> zostało pomyślnie utworzone. Poniżej znajdziesz dane do logowania:</p>

    <div class="login-data">
        <p><strong>Login (email):</strong> {{ $user->email }}</p>
        <p><strong>Hasło tymczasowe:</strong> {{ $password }}</p>
    </div>

    <a href="{{ route('volunteer.login') }}" class="button">Zaloguj się do panelu</a>

    <p>Po pierwszym logowaniu zalecamy zmianę hasła na własne, aby zapewnić bezpieczeństwo konta.</p>

    <p>Z pozdrowieniami,<br>
        <strong>Zespół FEER</strong><br>
        Fundacja Edukacji Empatii i Rozwoju „FEER”</p>

    <div class="footer">
        Administratorem Twoich danych osobowych jest <strong>Fundacja Edukacji Empatii i Rozwoju „FEER”</strong>.<br>
        Szczegółowe informacje o przetwarzaniu danych znajdziesz tutaj:
        <a href="https://feer.org.pl/rodo">https://feer.org.pl/rodo</a>
    </div>
</div>
</body>
</html>
