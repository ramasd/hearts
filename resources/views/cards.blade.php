<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Played Cards</title>

    <link rel="stylesheet" type="text/css" href="{{url('css/main.css')}}">
</head>
<body>
    <h1>Played Cards</h1>
    <div>
        <table>
            @foreach(config("constants.CARDS") as $suit => $types)
                <tr>
                    @foreach($types as $type)
                        @php $card = $suit . "_" . $type; @endphp
                        <td class="align-top">
                            <div>
                                <div>
                                    <img class="card-size {{ array_key_exists($card, $statistics['played_cards']) ? 'border' : "opacity" }}"  src="{{ url("images/cards/$card.svg") }}" alt="{{$card}}"/>
                                </div>
                                @if(isset($statistics['played_cards'][$card]))
                                    <div class="player-name" style="color:{{$playerColors[$statistics['played_cards'][$card]]}}">{{ $statistics['played_cards'][$card] }}</div>
                                @endif
                            </div>
                        </td>
                    @endforeach
                    <td>
                        <div class="played-cards-amount">{{$statistics['played_cards_amount'][$suit]}}<span>/13</span></div>
                    </td>
                </tr>
            @endforeach
        </table>

        <table>
            <tr>
                <th>Player</th>
                <th>Points</th>
            </tr>
            @foreach($statistics['player_points'] as $player => $points)
                <tr>
                    <td style="color:{{$playerColors[$player]}};" >{{ $player }}:</td>
                    <td style="border: solid;background-color:black;color:{{$playerColors[$player]}};" class="player-points">{{$points}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
