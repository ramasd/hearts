<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cards</title>

    <style>
        .opacity{
            opacity: 0.25;
            padding: 3px;
        }

        .border{
            border-style: solid;
            border-color: yellowgreen;
            border-width: 3px;
            border-radius: 11px;
        }
    </style>
</head>
<body>
<div>
    @foreach($cards as $suit => $types)
        <div>
            @foreach($types as $type)
                @php $card = $suit . "_" . $type; @endphp
                <img class="{{ in_array($card, $playedCards) ? 'border' : "opacity" }}"  src="{{ url("images/cards/$card.svg") }}" alt="{{$card}}" height="150" width="100"/>
            @endforeach
        </div>
    @endforeach
</div>
</body>
</html>
