<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos identifiants producteur — ABBEV</title>
</head>
<body style="margin:0; padding:0; background-color:#09090b; font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#09090b; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px; background-color:#18181b; border:1px solid #27272a; border-radius:16px; overflow:hidden;">

                    {{-- Bandeau --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#06b6d4,#0891b2); padding:28px 32px; text-align:center;">
                            <span style="display:inline-block; font-size:26px; font-weight:800; letter-spacing:2px; color:#ffffff;">ABBEV</span>
                            <div style="margin-top:4px; font-size:12px; letter-spacing:1px; color:#cffafe; text-transform:uppercase;">Espace Producteur</div>
                        </td>
                    </tr>

                    {{-- Corps --}}
                    <tr>
                        <td style="padding:32px;">
                            <h1 style="margin:0 0 8px; font-size:20px; color:#ffffff;">Bienvenue, {{ $name }} 👋</h1>
                            <p style="margin:0 0 24px; font-size:14px; line-height:1.6; color:#a1a1aa;">
                                Votre compte producteur ABBEV vient d'être créé. Voici vos identifiants
                                de connexion à l'espace d'administration.
                            </p>

                            {{-- Identifiants --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#09090b; border:1px solid #27272a; border-radius:12px;">
                                <tr>
                                    <td style="padding:16px 20px; border-bottom:1px solid #27272a;">
                                        <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#71717a; margin-bottom:4px;">Email de connexion</div>
                                        <div style="font-size:15px; color:#ffffff; font-family:Consolas,Menlo,monospace; word-break:break-all;">{{ $producerEmail }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <div style="font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#71717a; margin-bottom:4px;">Mot de passe</div>
                                        <div style="font-size:18px; color:#22d3ee; font-family:Consolas,Menlo,monospace; letter-spacing:1px; word-break:break-all;">{{ $password }}</div>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:28px 0 8px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $loginUrl }}" style="display:inline-block; background-color:#06b6d4; color:#ffffff; text-decoration:none; font-size:15px; font-weight:600; padding:14px 32px; border-radius:10px;">
                                            Me connecter à ABBEV
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0; font-size:13px; line-height:1.6; color:#a1a1aa;">
                                Pour votre sécurité, nous vous recommandons de
                                <strong style="color:#ffffff;">changer ce mot de passe</strong> après votre première connexion.
                                Ne partagez jamais ces identifiants.
                            </p>
                        </td>
                    </tr>

                    {{-- Pied --}}
                    <tr>
                        <td style="padding:20px 32px; border-top:1px solid #27272a; text-align:center;">
                            <p style="margin:0; font-size:12px; color:#52525b;">
                                Vous recevez cet email car un compte producteur a été créé pour vous sur ABBEV.<br>
                                Si vous n'êtes pas concerné, ignorez ce message.
                            </p>
                            <p style="margin:12px 0 0; font-size:12px; color:#3f3f46;">© {{ date('Y') }} ABBEV — Tous droits réservés</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
