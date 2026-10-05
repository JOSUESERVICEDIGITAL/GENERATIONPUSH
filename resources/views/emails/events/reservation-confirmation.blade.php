<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Confirmation de réservation — Generation PUSH
    </title>
</head>

<body style="
    margin:0;
    padding:0;
    background:#f4f4f4;
    font-family:Arial, Helvetica, sans-serif;
    color:#1A1A1A;
">

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="background:#f4f4f4; padding:30px 15px;"
>
    <tr>
        <td align="center">

            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                border="0"
                style="
                    max-width:620px;
                    background:#ffffff;
                    border-radius:16px;
                    overflow:hidden;
                "
            >

                {{-- HEADER --}}
                <tr>
                    <td
                        style="
                            background:#1A1A1A;
                            padding:30px;
                            text-align:center;
                        "
                    >

                       <img
    src="{{ $message->embed(public_path('front/images/logo.png')) }}"
    alt="Generation PUSH"
    width="150"
    style="
        display:block;
        width:150px;
        max-width:100%;
        height:auto;
        margin:0 auto 15px;
        border:0;
    "
>

                        <div
                            style="
                                color:#ffffff;
                                font-size:21px;
                                font-weight:700;
                            "
                        >
                            Generation
                            <span style="color:#E8631A;">
                                PUSH
                            </span>
                        </div>

                    </td>
                </tr>


                {{-- CONFIRMATION --}}
                <tr>
                    <td style="padding:36px 35px 15px;">

                        <div
                            style="
                                color:#E8631A;
                                font-size:13px;
                                font-weight:700;
                                text-transform:uppercase;
                                letter-spacing:1px;
                                margin-bottom:10px;
                            "
                        >
                            Réservation confirmée
                        </div>


                        <h1
                            style="
                                margin:0 0 18px;
                                font-size:25px;
                                line-height:1.3;
                                color:#1A1A1A;
                            "
                        >
                            Votre place est bien prise en compte !
                        </h1>


                        <p
                            style="
                                margin:0 0 15px;
                                font-size:15px;
                                line-height:1.7;
                                color:#555555;
                            "
                        >
                            Bonjour
                            <strong style="color:#1A1A1A;">
                                {{ $reservation->name ?? $reservation->full_name ?? 'cher participant' }}
                            </strong>,
                        </p>


                        <p
                            style="
                                margin:0;
                                font-size:15px;
                                line-height:1.7;
                                color:#555555;
                            "
                        >
                            Nous avons le plaisir de vous confirmer que
                            votre réservation pour l'événement

                            <strong style="color:#1A1A1A;">
                                {{ $event->title }}
                            </strong>

                            a bien été prise en compte.
                        </p>

                    </td>
                </tr>


                {{-- INFORMATIONS ÉVÉNEMENT --}}
                <tr>
                    <td style="padding:15px 35px;">

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            style="
                                background:#fff7f2;
                                border:1px solid #f7d7c4;
                                border-radius:12px;
                            "
                        >

                            <tr>
                                <td style="padding:22px;">

                                    <h2
                                        style="
                                            margin:0 0 18px;
                                            font-size:17px;
                                            color:#1A1A1A;
                                        "
                                    >
                                        Votre événement
                                    </h2>


                                    {{-- DATE --}}
                                    <p
                                        style="
                                            margin:0 0 12px;
                                            font-size:14px;
                                            color:#555555;
                                        "
                                    >
                                        <strong style="color:#E8631A;">
                                            Date :
                                        </strong>

                                        {{ $event->starts_at->translatedFormat('d F Y') }}
                                    </p>


                                    {{-- HEURE --}}
                                    <p
                                        style="
                                            margin:0 0 12px;
                                            font-size:14px;
                                            color:#555555;
                                        "
                                    >
                                        <strong style="color:#E8631A;">
                                            Heure :
                                        </strong>

                                        {{ $event->starts_at->format('H:i') }}
                                    </p>


                                    {{-- FORMAT --}}
                                    <p
                                        style="
                                            margin:0 0 12px;
                                            font-size:14px;
                                            color:#555555;
                                        "
                                    >
                                        <strong style="color:#E8631A;">
                                            Format :
                                        </strong>

                                        @switch($event->format)

                                            @case('online')
                                                En ligne
                                                @break

                                            @case('hybrid')
                                                Hybride
                                                @break

                                            @default
                                                Présentiel

                                        @endswitch
                                    </p>


                                    {{-- LIEU --}}
                                    @if($event->format !== 'online')

                                        <p
                                            style="
                                                margin:0 0 12px;
                                                font-size:14px;
                                                color:#555555;
                                            "
                                        >
                                            <strong style="color:#E8631A;">
                                                Lieu :
                                            </strong>

                                            {{ collect([
                                                $event->venue,
                                                $event->city,
                                                $event->country
                                            ])->filter()->implode(', ') ?: 'À confirmer' }}
                                        </p>

                                    @endif


                                    {{-- NOMBRE DE PLACES --}}
                                    @if($reservation->quantity)

                                        <p
                                            style="
                                                margin:0;
                                                font-size:14px;
                                                color:#555555;
                                            "
                                        >
                                            <strong style="color:#E8631A;">
                                                Nombre de places :
                                            </strong>

                                            {{ $reservation->quantity }}
                                        </p>

                                    @endif

                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>


                {{-- MESSAGE HUMAIN --}}
                <tr>
                    <td style="padding:15px 35px;">

                        <p
                            style="
                                margin:0 0 15px;
                                font-size:15px;
                                line-height:1.7;
                                color:#555555;
                            "
                        >
                            L'équipe
                            <strong style="color:#1A1A1A;">
                                Generation PUSH
                            </strong>
                            vous contactera prochainement si des
                            informations complémentaires sont nécessaires
                            pour votre participation.
                        </p>


                        <div
                            style="
                                background:#1A1A1A;
                                border-radius:12px;
                                padding:22px;
                                margin-top:20px;
                                text-align:center;
                            "
                        >

                            <p
                                style="
                                    margin:0 0 8px;
                                    color:#ffffff;
                                    font-size:18px;
                                    font-weight:700;
                                "
                            >
                                Vous êtes vivement attendu(e) !
                            </p>

                            <p
                                style="
                                    margin:0;
                                    color:#cccccc;
                                    font-size:14px;
                                    line-height:1.6;
                                "
                            >
                                Préparez-vous à vivre un moment de
                                partage, d'apprentissage, de connexion
                                et d'impact.
                            </p>

                        </div>

                    </td>
                </tr>


                {{-- RÉFÉRENCE --}}
                @if($reservation->reference)

                    <tr>
                        <td
                            style="
                                padding:10px 35px 25px;
                                text-align:center;
                            "
                        >

                            <p
                                style="
                                    margin:0;
                                    font-size:12px;
                                    color:#999999;
                                "
                            >
                                Référence de réservation
                            </p>

                            <strong
                                style="
                                    display:block;
                                    margin-top:5px;
                                    color:#1A1A1A;
                                    font-size:14px;
                                "
                            >
                                {{ $reservation->reference }}
                            </strong>

                        </td>
                    </tr>

                @endif


                {{-- SIGNATURE --}}
                <tr>
                    <td
                        style="
                            padding:25px 35px;
                            border-top:1px solid #eeeeee;
                        "
                    >

                        <p
                            style="
                                margin:0 0 5px;
                                font-size:14px;
                                color:#555555;
                            "
                        >
                            À très bientôt,
                        </p>

                        <p
                            style="
                                margin:0;
                                font-size:15px;
                                font-weight:700;
                                color:#1A1A1A;
                            "
                        >
                            L'équipe Generation PUSH
                        </p>

                    </td>
                </tr>


                {{-- FOOTER --}}
                <tr>
                    <td
                        style="
                            background:#1A1A1A;
                            padding:20px 30px;
                            text-align:center;
                        "
                    >

                        <p
                            style="
                                margin:0 0 6px;
                                color:#ffffff;
                                font-size:12px;
                            "
                        >
                            Generation PUSH
                        </p>

                        <p
                            style="
                                margin:0;
                                color:#E8631A;
                                font-size:11px;
                                font-weight:700;
                            "
                        >
                            We are not here to motivate you.
                            We are here to PUSH you.
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
