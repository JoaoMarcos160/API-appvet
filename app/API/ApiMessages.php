<?php

namespace App\API;

class ApiMessages
{
    //aqui estão armazenadas todas as mensagens de resposta da API
    //para facilitar a manutenção e concistencia

    public static function message(int $message_id, $additional_information = null)
    {
        $message = null;
        switch ($message_id) {
            case 1:
                $message = "Voce precisa estar logado";
                break;
            case 2:
                $message = $additional_information == null ? "Houve um erro ao realizar alguma operacao" : "Houve um erro ao realizar a operacao de " . $additional_information;
                break;
            case 3:
                $message = "Login nao encontrado";
                break;
            case 4:
                $message = "Sucesso";
                break;
            case 5:
                $message = "Senha incorreta";
                break;
            case 6:
                $message = "Criado com sucesso";
                break;
            case 7:
                $message = "Ja existe um usuario com esse login";
                break;
            case 8:
                $message = "Algum campo esta incorreto";
                break;
            case 9:
                $message = "Alterado com sucesso";
                break;
            case 10:
                $message = "Ja existe um usuario com esse login";
                break;
            case 11:
                $message = "Deletado com sucesso";
                break;
            case 12:
                $message = $additional_information == null ? "Nao encontrado" : $additional_information . " nao encontrado";
                break;
            case 13:
                $message = "Token invalido";
                break;
            case 14:
                $message = "Erro ao inserir imagem!";
                break;
            case 15:
                $message = "Extensao invalida!";
                break;
            case 16:
                $message = "Tamanho da imagem invalido!";
                break;
            default:
                $message = "Erro desconhecido";
                break;
        }
        return $message;
    }
}
