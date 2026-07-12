<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos identifiants producteur — ABBEV</title>
</head>
<body style="margin:0; padding:0; font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:20px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px; background-color:#18181b; border:1px solid #27272a; border-radius:14px; overflow:hidden;">

                    {{-- Bandeau --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#06b6d4,#0891b2); padding:18px 28px; text-align:center;">
                            <span style="display:inline-block; font-size:22px; font-weight:800; letter-spacing:2px; color:#ffffff;">ABBEV</span>
                            <div style="margin-top:3px; font-size:11px; letter-spacing:1px; color:#cffafe; text-transform:uppercase;">Espace Producteur</div>
                        </td>
                    </tr>

                    {{-- Corps --}}
                    <tr>
                        <td style="padding:24px 28px;">
                            <h1 style="margin:0 0 6px; font-size:18px; color:#ffffff;">Bienvenue, {{ $name }}</h1>
                            <p style="margin:0 0 18px; font-size:14px; line-height:1.5; color:#a1a1aa;">
                                Votre compte producteur ABBEV vient d'être créé. Voici vos identifiants
                                de connexion à l'espace d'administration.
                            </p>

                            {{-- Identifiants --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#09090b; border:1px solid #27272a; border-radius:10px;">
                                <tr>
                                    <td style="padding:12px 16px; border-bottom:1px solid #27272a;">
                                        <div style="font-size:10px; text-transform:uppercase; letter-spacing:1px; color:#71717a; margin-bottom:3px;">Email de connexion</div>
                                        <div style="font-size:14px; color:#ffffff; font-family:Consolas,Menlo,monospace; word-break:break-all;">{{ $producerEmail }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;">
                                        <div style="font-size:10px; text-transform:uppercase; letter-spacing:1px; color:#71717a; margin-bottom:3px;">Mot de passe</div>
                                        <div style="font-size:17px; color:#22d3ee; font-family:Consolas,Menlo,monospace; letter-spacing:1px; word-break:break-all;">{{ $password }}</div>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0 4px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $loginUrl }}" style="display:inline-block; background-color:#06b6d4; color:#ffffff; text-decoration:none; font-size:14px; font-weight:600; padding:12px 28px; border-radius:8px;">
                                            Me connecter à ABBEV
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:16px 0 0; font-size:12px; line-height:1.5; color:#a1a1aa;">
                                Pour votre sécurité, nous vous recommandons de
                                <strong style="color:#ffffff;">changer ce mot de passe</strong> après votre première connexion.
                                Ne partagez jamais ces identifiants.
                            </p>
                        </td>
                    </tr>

                    {{-- Pied --}}
                    <tr>
                        <td style="padding:14px 28px; border-top:1px solid #27272a; text-align:center;">
                            <p style="margin:0; font-size:11px; color:#52525b; line-height:1.5;">
                                Vous recevez cet email car un compte producteur a été créé pour vous sur ABBEV.
                                Si vous n'êtes pas concerné, ignorez ce message.
                            </p>
                            <p style="margin:8px 0 0; font-size:11px; color:#3f3f46;">© {{ date('Y') }} ABBEV — Tous droits réservés</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
