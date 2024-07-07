<?php

return [
    /*
    |----------------------------------------------------------------------------
    | Google application name
    |----------------------------------------------------------------------------
    */
    'application_name' => env('GOOGLE_APPLICATION_NAME', ''),

    /*
    |----------------------------------------------------------------------------
    | Google OAuth 2.0 access
    |----------------------------------------------------------------------------
    |
    | Keys for OAuth 2.0 access, see the API console at
    | https://developers.google.com/console
    |
    */
    'client_id' => env('GOOGLE_CLIENT_ID', ''),
    'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
    'redirect_uri' => env('GOOGLE_REDIRECT', ''),
    'scopes' => [\Google\Service\Sheets::DRIVE, \Google\Service\Sheets::SPREADSHEETS],
    'access_type' => 'online',
    'approval_prompt' => 'auto',
    'spreadsheet_id' => '1H8zzCuEFcMa4o-1QbTCXhyNWXVgHeEr_fi5xVNmNSGw',

    /*
    |----------------------------------------------------------------------------
    | Google developer key
    |----------------------------------------------------------------------------
    |
    | Simple API access key, also from the API console. Ensure you get
    | a Server key, and not a Browser key.
    |
    */
    'developer_key' => env('GOOGLE_DEVELOPER_KEY', ''),

    /*
    |----------------------------------------------------------------------------
    | Google service account
    |----------------------------------------------------------------------------
    |
    | Set the credentials JSON's location to use assert credentials, otherwise
    | app engine or compute engine will be used.
    |
    */
    'service' => [
        /*
        | Enable service account auth or not.
        */
        'enable' => env('GOOGLE_SERVICE_ENABLED', false),

        /*
         * Path to service account json file. You can also pass the credentials as an array
         * instead of a file path.
         */
        'file' => [
            "type" => "service_account",
            "project_id" => "expoinf",
            "private_key_id" => "12c91600690f9bf17d7e07f418593b1f3b90a65c",
            "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCx2X4WhQiQ2Tcq\ngEBo+lgy69N+1xOA7hlZ212D78GMpk5zoXU54gaXxursxSiBzYkFgIB44Fz4MvHt\nikQquI9UGO2i3eUN99gfz2jSkMNiMDP/sDcRYCgpcTY31p0a/hHWxQjbPhBqyms/\njaF3GKRFlxKhaJWi4Oxt0eWN5PmD8cs/pkW16kxtxRbrytG0yDWLLGBBrWT+o1gI\n0VjIRuWVz+kltQjrBPI/M+MgrzlGA/rMmCN9JIuhwvx943vOc/qr13GoU4fORk2T\ncoClrUzpYG4y60nRi1tXdCFqX2H15HsKXD33a7Gf/Ty3fMam6GmPfUkApeL9BRKa\nHrHIErZJAgMBAAECggEABvOnzMYhXXXAz6ZwzhcCBqcxQ/cbe1nN5R/7XevBB/1y\nBIL6RRt8szxVOhEjVL6QWsYTpNXtSw4vvB+gVj9S/+rTssmZDq9CSWJcYtsB03cr\noyoTyaK1ndgVEitEsRWfDs3Hxg0dIRV9ih/gFhALz29jglUR60p+3LSR3uE6mmxd\njfH4uz1LwWc+Pc4143OdvuxPSewiHyOCt03PmHR7jhIu4FFZOwZDSv9zhCfcHgT+\nOfAeduaMV4Gv2apFJaHkYDkpY2hIuzU2JWGinVeCwYTzM/Qlm8aBBgE5Frj2z4k5\n6WtWSGTz0cqpvhjzbeTqg/26PAIl8u5rid1Aj84NYQKBgQDfzozZVGgu/f6w9JWi\nx2fuCBt2+l5aLihkRGXZ3tkjCWlSy3i8K2QuJy1tOKMUE49n+jtfjh+cEwmkJYW4\nwgnNaSoeoknHexcKrQe6WquYa6l3bqdSaq0zHL728hEIqT+Hl0qGU+tc2y4w9z8f\n2Lj7UsBQxz0e4ze2hK8eYqKuoQKBgQDLbp1yX1lAR+ws37c6JS0G8mx3aibRoBY7\n5FAGLaVwP3yQv2/sStLBS23T4x8XxbRTxUyOiLUF7+XgItLCzeLKk8gbl1cuez0V\nWJ7/m2cpMHmNs1iROT7N7z3hyVFKFJLrEMbOMkHsFtfg1SuGBI1NT2fTISy12jML\ne+0GQnyuqQKBgQCqFSFrJD9MvxAoUKfLHfl4psagg6Liq8Ncd7JjgXzIqKVXG3R0\npBEtFe7q2yjr3/WS0lL+ylZxWPJxwWY/Q48JgMtWnoStSXj4TwjZcYCIhAE0123n\nL1TSzBzANLQHPgAevjYPoYTze0o+QhfBzwS9UmT+vntPluPHHEytLc5uIQKBgQCp\nW814Cikjer0EHrtLjOePgQML5zOqELxx4AE/I4E3kaMCXl2vOht7EHC+lbas88rM\nctTpVKZf6vhVQKh1xTGptJ5DWhsadz7DJqQ36AhaIiEP9t8lB6NhjmrWLeOe6MHn\nMHBR+UUoZ1wRsWYqjF+WnSSygEvYmW8lgGYzb+adAQKBgQDYjX3MYcfHV+7DncAn\nJB5OLxsegdPT7yyRIDcEu15idH8/23pTNPmOpSVYTcvQ8FXvPBQ4/SYkaeSsSmdu\n+w6LLJGtPucxdbuy8eWQeuStcgzhd7EC+UX1Hi5WGtzWvIhbKpAgse9QoAM5dATw\nhWG2UQyZCa0F0+NgwYltxk7kqg==\n-----END PRIVATE KEY-----\n",
            "client_email" => "google-sheet-api@expoinf.iam.gserviceaccount.com",
            "client_id" => "116288463541989575513",
            "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            "token_uri" => "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/google-sheet-api%40expoinf.iam.gserviceaccount.com",
            "universe_domain" => "googleapis.com"
        ],
    ],

    /*
    |----------------------------------------------------------------------------
    | Additional config for the Google Client
    |----------------------------------------------------------------------------
    |
    | Set any additional config variables supported by the Google Client
    | Details can be found here:
    | https://github.com/google/google-api-php-client/blob/master/src/Google/Client.php
    |
    | NOTE: If client id is specified here, it will get over written by the one above.
    |
    */
    'config' => [],
];
