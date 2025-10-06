<?php

namespace Mateus\ProtocolTrackerV2\Model;

use DateTimeImmutable;
use Exception;

class Usuario {

    public function __construct(
        private readonly ?int $id,
        private readonly string $nome,
        private readonly string $email,
        private readonly string $cpf,
        private readonly string $hash_senha,
        private readonly string $permissao,
        private readonly DateTimeImmutable $data_criacao,
        private readonly bool $status
    )
    {}

    //GETTERS
    /**
     * @return int|null id
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return int nome
     */
    public function getNome(): string {
        return $this->nome;
    }

    /**
     * @return int email
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * @return int cpf
     */
    public function getCpf(): string {
        return $this->cpf;
    }

    /**
     * @return int hash_senha
     */
    public function getHashSenha(): string {
        return $this->hash_senha;
    }

    /**
     * @return int permissao
     */
    public function getPermissao(): string {
        return $this->permissao;
    }

    /**
     * @return int data_criacao
     */
    public function getDataCriacao(): DateTimeImmutable {
        return $this->data_criacao;
    }

    /**
     * @return bool status
     */
    public function getStatus(): bool {
        return $this->status;
    }

    /**
     * Converte um array associativo em Usuario
     * @param array $array Array associativo
     * @return Usuario
     */
    public static function fromArray(array $array): self
    {
        
        try {
            $data_criacao = new DateTimeImmutable($array['data_criacao']);
        } catch (Exception $e) {
            throw new Exception("Falha ao criar data");
        }

        return new self(
            (int) $array['id'],
            $array['nome'],
            $array['email'],
            $array['cpf'],
            $array['hash_senha'],
            $array['permissao'],
            $data_criacao,
            (bool) $array['status']
        );
    }

    /**
     * Converte um Usuario em array associativo
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id ?? null,
            'nome' => $this->nome,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'hash_senha' => $this->hash_senha,
            'permissao' => $this->permissao,
            'data_criacao' => $this->data_criacao->format('Y-m-d H:i:s'),
            'status' => $this->status
        ];
    }
}