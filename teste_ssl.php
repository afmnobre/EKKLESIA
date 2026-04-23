<?php
echo "OpenSSL ativo: " . (extension_loaded('openssl') ? 'SIM' : 'NÃO') . "<br>";
$fp = @fsockopen("smtp.gmail.com", 587, $errno, $errstr, 5);
if (!$fp) {
    echo "Erro de conexão: $errstr ($errno)<br>";
} else {
    echo "Conexão com Gmail OK!<br>";
    fclose($fp);
}
?>
