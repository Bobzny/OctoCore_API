<?php
class Jwt {
    private static function getChave() {
        require_once __DIR__ . '/../config/config.php';
        if (defined('CHAVE_JWT')) {
            return CHAVE_JWT;
        } else {
            throw new Exception('CHAVE_JWT não definida no config.php');
        }
    }

    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function Gerar($idUsuario) {
        $key = self::getChave();
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'idUsuario' => $idUsuario,
            'iat' => time(),
            'exp' => time() + 86400 // Expira em 24 horas 
        ]);

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $key, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    public static function Validar($jwt) {
        $key = self::getChave();
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) return false;

        //Cria uma variável para cada parte do JWT
        list($header, $payload, $signature) = $parts;

        //Recalcula o hash do payload recebido
        $validSignature = self::base64UrlEncode(
            hash_hmac('sha256', "$header.$payload", $key, true)
        );

        //Compara o hash recalculado com o hash recebido
        if (!hash_equals($validSignature, $signature)) return false;

        //Verifica a validade da assinatura
        $payloadData = json_decode(self::base64UrlDecode($payload), true);
        if (!$payloadData || !isset($payloadData['exp']) || $payloadData['exp'] < time()) {
            return false; // Expirado ou inválido
        }

        return $payloadData;
    }
}
?>