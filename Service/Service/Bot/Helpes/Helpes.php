<?php

function checkToken($token) {
    $tokenExplode = explode('.', $token);
    $tokenPayload = $tokenExplode[1] ?? false;

    if(!$tokenPayload) {
        return false;
    }

    $tokenPayloadDecode = base64_decode($tokenPayload);
    $tokenPayloadDecodeJson = json_decode($tokenPayloadDecode, true);

    if (isset($tokenPayloadDecodeJson['exp']) && isset($tokenPayloadDecodeJson['ar.gob.abc.slm.perfil'])) {
        $tokenExp = $tokenPayloadDecodeJson['exp'];
        $tokenExpDate = date('Y-m-d H:i:s', $tokenExp);

        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');

        if ($tokenExpDate < $now) {
            return false;
        }

        if($tokenPayloadDecodeJson['ar.gob.abc.slm.perfil'] != 'PRESTADORA') {
            return false;
        }

        return true;
    }

    return false;
}