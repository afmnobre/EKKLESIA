<?php

namespace App\Core;

class Utils {

    public static function otimizarImagem($caminhoOriginal, $caminhoDestino, $limite = 800, $qualidade = 75) {
        if (!file_exists($caminhoOriginal)) return false;

        $info = getimagesize($caminhoOriginal);
        if (!$info) return false;

        list($larguraOriginal, $alturaOriginal, $tipo) = $info;

        $larguraNova = $larguraOriginal;
        $alturaNova = $alturaOriginal;

        if ($larguraOriginal > $limite || $alturaOriginal > $limite) {
            if ($larguraOriginal > $alturaOriginal) {
                $larguraNova = $limite;
                $alturaNova = floor($alturaOriginal * ($limite / $larguraOriginal));
            } else {
                $alturaNova = $limite;
                $larguraNova = floor($larguraOriginal * ($limite / $alturaOriginal));
            }
        }

        switch ($tipo) {
            case IMAGETYPE_JPEG: $origem = imagecreatefromjpeg($caminhoOriginal); break;
            case IMAGETYPE_PNG:  $origem = imagecreatefrompng($caminhoOriginal); break;
            case IMAGETYPE_GIF:  $origem = imagecreatefromgif($caminhoOriginal); break;
            case IMAGETYPE_WEBP: $origem = imagecreatefromwebp($caminhoOriginal); break;
            default: return false;
        }

        $imagemFinal = imagecreatetruecolor($larguraNova, $alturaNova);

        if ($tipo == IMAGETYPE_PNG || $tipo == IMAGETYPE_WEBP) {
            imagealphablending($imagemFinal, false);
            imagesavealpha($imagemFinal, true);
            $corFundo = imagecolorallocatealpha($imagemFinal, 255, 255, 255, 127);
            imagefill($imagemFinal, 0, 0, $corFundo);
        }

        imagecopyresampled($imagemFinal, $origem, 0, 0, 0, 0, $larguraNova, $alturaNova, $larguraOriginal, $alturaOriginal);

        // O PHP 8 vai destruir $origem e $imagemFinal automaticamente ao sair desta função.
        return imagejpeg($imagemFinal, $caminhoDestino, $qualidade);
    }
}
