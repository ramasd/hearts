<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Played Cards</title>

    <style>
        body{
            font-family: 'Brush Script MT', cursive;
        }

        h1{
            text-decoration: underline;
        }

        table{
            margin-bottom: 20px;
            font-size: 20px;
        }

        th{
            text-align: left;
        }

        .opacity{
            opacity: 0.25;
            padding: 2px;
        }

        .border{
            border-style: solid;
            border-color: darkgrey;
            border-width: 2px;
            border-radius: 8px 8px 0 0;
            background-color: darkgrey;
        }

        .card-size{
            height: 120px;
            width: 80px;
        }

        .player-name {
            font-size: small;
            text-align: center;
            font-weight: bold;
            width: 72px;
            overflow: hidden;
            padding: 0 6px;
            margin-top: -6px;
            position: relative;
            background-color: darkgrey;
            border-radius: 0 0 8px 8px;
            color: darkgreen;
        }

        .played-cards-amount{
            font-size: 20px;
            color: darkslategray;
            padding-left: 20px;
            font-weight: bold;
        }

        .played-cards-amount span{
            color: darkgrey;
        }

        .player-points{
            border: solid;
            text-align: center;
        }

        .align-top{
            vertical-align: top;
        }
    </style>
</head>
<body>
    <h1>Played Cards</h1>
    <div>
        <table>
            @foreach($cards as $suit => $types)
                <tr>
                    @foreach($types as $type)
                        @php $card = $suit . "_" . $type; @endphp
                        <td class="align-top">
                            <div>
                                <div>
                                    <img class="card-size {{ array_key_exists($card, $playedCards) ? 'border' : "opacity" }}"  src="{{ url("images/cards/$card.svg") }}" alt="{{$card}}"/>
                                </div>
                                @if(isset($playedCards[$card]))
                                    <div class="player-name" style="color:{{$nicknameColors[$playedCards[$card]]}}">{{ $playedCards[$card] }}</div>
                                @endif
                            </div>
                        </td>
                    @endforeach
                    <td>
                        <div class="played-cards-amount">{{$playedCardsAmount[$suit]}}<span>/13</span></div>
                    </td>
                </tr>
            @endforeach
        </table>

        <table>
            <tr>
                <th>Player Name</th>
                <th>Points</th>
            </tr>
            @foreach($playerPoints as $player => $points)
                <tr>
                    <td style="color:{{$nicknameColors[$player]}};" >{{ $player }}:</td>
                    <td style="border: solid;background-color:black;color:{{$nicknameColors[$player]}};" class="player-points">{{$points}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
