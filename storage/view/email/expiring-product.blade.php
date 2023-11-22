<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 19px;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2e8422;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        p {
            line-height: 1.6;
            font-size: 15px;
            margin-bottom: 15px;
        }

        .expire-date {
            font-size: 20px;
            font-weight: bold;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-top: 15px;
        }

        .italic-bold {
            font-style: italic;
            font-weight: bold;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
<div class="container">
    @php
        use Carbon\Carbon;

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        $firstName = explode(' ', $userName)[0];
        $expireDate = Carbon::parse($productExpireDate)->format('d/m');
        $expireMessage = $productDaysUntilExpiry < 1 ? 'hoje' : "vencerá em {$productDaysUntilExpiry} dias";
    @endphp

    <h1>Olá, {{ $firstName }}!</h1>
    <p>Seu produto, <strong>{{ $productName }}</strong>, está prestes a vencer!</p>
    <img src="{{ $productImageUrl }}" alt="{{ $productName }}">
    <p>Vencerá em <span class="expire-date">{{ $expireDate }}</span>, {{ $expireMessage }}!</p>
    <p>Recomendamos consumir este produto antes do vencimento para aproveitar sua frescura.</p>
    <p class="italic-bold">Lembre-se, <span class="bold">escolhas sustentáveis</span> fazem a diferença! Reduza o desperdício usando os produtos de forma consciente.</p>
    <p class="italic-bold">Obrigado por fazer parte da nossa comunidade.</p>
</div>
</body>

</html>
