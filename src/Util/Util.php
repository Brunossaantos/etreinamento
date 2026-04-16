<?php

class Util
{

    private $dataAtaual;

    function dataAtual()
    {
        $dataHoraAtual = new DateTime();
        $formato = 'd-m-Y H:i:s';
        $dataHoraFormatada = $dataHoraAtual->format($formato);
        return $dataHoraFormatada;
    }

    // function montarCaminhoFoto($diretorioDasFotos ,$matricula){
    //     $nomeDaFoto = $matricula . ".jpg";

    //     if($diretorioDasFotos == null){
    //         $diretorioDasFotos = "../imagens/colaboradores/";        
    //     } 

    //     $caminhoDaFoto = $diretorioDasFotos . $nomeDaFoto;

    //     if (file_exists($caminhoDaFoto)) {
    //         return $caminhoDaFoto;
    //     } else {
    //         $nomeDaFoto = "user_image.png";
    //         $caminhoDaFoto = $diretorioDasFotos . $nomeDaFoto;
    //         return $caminhoDaFoto;
    //     }

    // }

    function montarCaminhoFoto($diretorioDasFotos, $idColaborador)
    {

        // Caminho físico no servidor (C:\xampp\htdocs\...)
        $caminhoFisico = $_SERVER['DOCUMENT_ROOT'] . '/gestor/fotos/';

        // Caminho para o navegador (URL)
        $caminhoWeb = '/gestor/fotos/';

        // Verifica se a pasta existe
        if (!is_dir($caminhoFisico)) {
            return '/etreinamento/imagens/sem-logo.png';
        }

        // Procura arquivos no padrão: colab_ID_
        $arquivos = glob($caminhoFisico . "colab_" . $idColaborador . "_*");

        if (!empty($arquivos)) {

            // 🔥 pega o mais recente
            usort($arquivos, function ($a, $b) {
                return filemtime($b) - filemtime($a);
            });

            $nomeArquivo = basename($arquivos[0]);

            return $caminhoWeb . $nomeArquivo;
        }

        // fallback padrão
        return '/etreinamento/imagens/sem-logo.png';
    }

    function montarCaminhoLogotipo($diretorioLogotipos, $idEmpresa)
    {
        $extensoesPermitidas = ["jpg", "jpeg", "png", "gif"]; // Extensões de imagem permitidas

        // Verifique se o diretório de logotipos não é nulo e existe
        if ($diretorioLogotipos == null || !is_dir($diretorioLogotipos)) {
            $diretorioLogotipos = "../imagens/logotipos/";
        }

        // Verifique todas as extensões permitidas até encontrar uma correspondência
        foreach ($extensoesPermitidas as $extensao) {
            $nomeDoArquivo = $idEmpresa . "." . $extensao;
            $caminhoDoLogotipo = $diretorioLogotipos . $nomeDoArquivo;

            if (file_exists($caminhoDoLogotipo)) {
                return $caminhoDoLogotipo;
            }
        }

        // Se nenhuma imagem for encontrada, retorne o logotipo padrão
        $nomeDoArquivo = "default_logotipo.png";
        $caminhoDoLogotipo = $diretorioLogotipos . $nomeDoArquivo;
        return $caminhoDoLogotipo;
    }

    function formatarData($dataDoBancoDeDados)
    {
        $data = new DateTime($dataDoBancoDeDados);
        return $data->format('d/m/y');
    }

    function separarHoraData($dataeHora)
    {
        $partes = explode(' ', $dataeHora);
        $data = $partes[0];
        $hora = $partes[1];

        return array('data' => $data, 'hora' => $hora);
    }
}
