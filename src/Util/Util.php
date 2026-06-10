<?php

/**
 * Classe Utilitária do sistema.
 * Responsável por centralizar funções auxiliares relacionadas a data/hora,
 * manipulação de caminhos de arquivos (fotos e logotipos) e formatação de dados.
 *
 * Essas funções são usadas em diferentes módulos do sistema para evitar repetição de código.
 */
class Util
{



    /**
     * Retorna a data e hora atual formatada.
     *
     * Regra de negócio:
     * Utilizado para registrar timestamps consistentes no sistema
     * (ex: presença, logs, auditoria).
     */
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

    /**
     * Monta o caminho da foto do colaborador.
     *
     * Regra de negócio:
     * - Cada colaborador pode ter uma ou mais fotos salvas no servidor
     * - O sistema sempre busca a imagem mais recente baseada no padrão "colab_ID_*"
     * - Caso não exista foto, retorna uma imagem padrão do sistema
     */
    function montarCaminhoFoto($diretorioDasFotos, $idColaborador)
    {
        // Caminho físico (servidor)
        $caminhoFisico = $_SERVER['DOCUMENT_ROOT'] . '/gestor/fotos/';

        // Caminho web (navegador)
        $caminhoWeb = '/gestor/fotos/';

        // fallback padrão CORRETO
        $fallback = '/etreinamento/imagens/user_image.png';

        if (empty($idColaborador) || !is_dir($caminhoFisico)) {
            return $fallback;
        }

        // 🔥 busca com qualquer extensão
        $arquivos = glob($caminhoFisico . "colab_" . $idColaborador . "_*.*");

        if (!empty($arquivos)) {

            // ordena pela mais recente
            usort($arquivos, function ($a, $b) {
                return filemtime($b) - filemtime($a);
            });

            return $caminhoWeb . basename($arquivos[0]);
        }

        return $fallback;
    }

    /**
     * Monta o caminho do logotipo da empresa.
     *
     * Regra de negócio:
     * - Cada empresa pode ter logotipo em diferentes formatos (jpg, png, etc.)
     * - O sistema tenta encontrar o arquivo com base no ID da empresa
     * - Se não encontrar, utiliza um logotipo padrão
     */
    function montarCaminhoLogotipo($diretorioLogotipos, $idEmpresa)
    {
        $extensoesPermitidas = ["jpg", "jpeg", "png", "gif"];

        // Caso diretório não exista ou não seja informado, usa padrão
        if ($diretorioLogotipos == null || !is_dir($diretorioLogotipos)) {
            $diretorioLogotipos = "../imagens/logotipos/";
        }

        // Verifica todas as extensões possíveis
        foreach ($extensoesPermitidas as $extensao) {
            $nomeDoArquivo = $idEmpresa . "." . $extensao;
            $caminhoDoLogotipo = $diretorioLogotipos . $nomeDoArquivo;

            if (file_exists($caminhoDoLogotipo)) {
                return $caminhoDoLogotipo;
            }
        }

        // fallback padrão
        return $diretorioLogotipos . "default_logotipo.png";
    }

    /**
     * Formata data do banco para padrão brasileiro.
     *
     * Regra:
     * Padroniza exibição de datas no sistema (relatórios e interfaces).
     */
    function formatarData($dataDoBancoDeDados)
    {
        $data = new DateTime($dataDoBancoDeDados);
        return $data->format('d/m/y');
    }


    /**
     * Separa uma string de data e hora em partes distintas.
     *
     * Regra:
     * Usado para telas onde data e hora precisam ser exibidas separadamente.
     */
    function separarHoraData($dataeHora)
    {
        $partes = explode(' ', $dataeHora);
        $data = $partes[0];
        $hora = $partes[1];

        return array('data' => $data, 'hora' => $hora);
    }
}