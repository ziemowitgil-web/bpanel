<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witamy w panelu eduk@cja</title>
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
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 26px;
            color: #0055a5;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333333;
        }
        p {
            margin-bottom: 15px;
        }
        .login-data {
            background-color: #f0f0f5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .login-data strong {
            display: inline-block;
            width: 150px;
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
        ul {
            margin: 0 0 15px 20px;
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
    <h1>Witamy na platformie eduk@cja!</h1>
    <p>Cieszymy się, że dołączyłeś/dołączyłaś do naszego panelu. Poniżej znajdziesz informacje potrzebne do pierwszego logowania oraz krótkie wprowadzenie do funkcji, jakie oferuje panel.</p>

    <h2>Twoje dane logowania</h2>
    <div class="login-data">
        <p><strong>Login (email):</strong> {{ $user->email }}</p>
        <p><strong>Hasło tymczasowe:</strong> {{ $password }}</p>
    </div>

    <a href="{{ env('APP_URL') }}/login" class="button">Zaloguj się do panelu</a>

    <h2>Co znajdziesz w panelu</h2>
    <ul>
        <li>aktywację konta Microsoft 365 dla Edukacji,</li>
        <li>licencje specjalnie dla naszych kursantów,</li>
        <li>linki do zajęć i materiały edukacyjne (dostępne w zależności od wybranych zajęć),</li>
        <li>możliwość zarządzania środowiskiem testowym,</li>
        <li>narzędzia do rozliczeń płatności i inne funkcje administracyjne.</li>
    </ul>

    <p>Z pozdrowieniami,<br>
        <strong>Fundacja Edukacji Empatii i Rozwoju „FEER”</p></strong><br>

    <div class="footer">
        Administratorem Twoich danych osobowych jest <strong>Fundacja Edukacji Empatii i Rozwoju „FEER”</strong>.<br>
        Szczegółowe informacje o przetwarzaniu danych znajdziesz tutaj:
        <a href="https://feer.org.pl/rodo">https://feer.org.pl/rodo</a>
    </div>
</div>
</body>
</html>
