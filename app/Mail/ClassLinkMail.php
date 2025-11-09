<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Link do zajęć</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .card {
            max-width: 500px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #0d6efd;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .card-body {
            padding: 20px;
            text-align: center;
            color: #333333;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 20px 0;
            font-size: 16px;
            color: #ffffff;
            background-color: #0d6efd;
            text-decoration: none;
            border-radius: 5px;
        }
        .small-text {
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            Twój link do zajęć
        </div>
        <div class="card-body">
            <p>Cześć <strong>{{ $beneficiary->first_name }} {{ $beneficiary->last_name }}</strong>,</p>
            <p>Poniżej znajdziesz link do Twoich zajęć:</p>

            @if($beneficiary->class_link)
            <a href="{{ $beneficiary->class_link }}" class="btn" target="_blank">Otwórz zajęcia</a>
            <p class="small-text">
                Jeśli przycisk nie działa, użyj linku:<br>
                <a href="{{ $beneficiary->class_link }}">{{ $beneficiary->class_link }}</a>
            </p>
            @else
            <p class="small-text" style="color: red;">Brak linku do zajęć dla tego beneficjenta.</p>
            @endif

            <p class="small-text">Pozdrawiamy,<br> Fundacja Edukacji Empatii Rozwoju "FEER"</p>
        </div>
    </div>
</div>
</body>
</html>
