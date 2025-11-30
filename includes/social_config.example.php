<?php
// Configuración de Login Social (PLANTILLA)
// RENOMBRA ESTE ARCHIVO A social_config.php Y AGREGA TUS CREDENCIALES

return [
    'google' => [
        'client_id'     => 'TU_CLIENT_ID_AQUI',
        'client_secret' => 'TU_CLIENT_SECRET_AQUI',
        'redirect_uri'  => 'https://tudominio.com/google_auth.php',
    ],
    'facebook' => [
        'app_id'        => 'TU_APP_ID_AQUI',
        'app_secret'    => 'TU_APP_SECRET_AQUI',
        'default_graph_version' => 'v16.0',
        'redirect_uri'  => 'https://tudominio.com/facebook_auth.php',
    ]
];
