<?php

namespace Mateus\ProtocolTrackerV2\Repository;

use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Usuario;
use PDO;
use PDOStatement;

class UsuarioRepository implements UsuarioRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function all(): array
    {
        $sqlQuery = "SELECT * FROM usuarios ORDER BY data_Criacao DESC;";

        $stmt = $this->connection->query($sqlQuery);

        return $this->hidrataLista($stmt);
    }

    public function allByPermissao(string $permissao): array
    {
        $sqlQuery = "SELECT * FROM usuarios WHERE permissao = :permissao";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':permissao', $permissao);

        $stmt->execute();

        return $this->hidrataLista($stmt);
    }

    public function findById(int $id): ?Usuario
    {
        $sqlQuery = "SELECT * FROM usuarios WHERE id = :id;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':id', $id);

        $stmt->execute();

        $dadosUsuario = $stmt->fetch();
        if ($dadosUsuario === false) {
            return null;
        }

        return Usuario::fromArray($dadosUsuario);
    }

    public function findByCpf(string $cpf): ?Usuario
    {
        $sqlQuery = "SELECT * FROM usuarios WHERE cpf = :cpf;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':cpf', $cpf);

        $stmt->execute();

        $dadosUsuario = $stmt->fetch();
        if ($dadosUsuario === false) {
            return null;
        }

        return Usuario::fromArray($dadosUsuario);
    }

    public function findByEmail(string $email): ?Usuario
    {
        $sqlQuery = "SELECT * FROM usuarios WHERE email = :email;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':email', $email);

        $stmt->execute();

        $dadosUsuario = $stmt->fetch();
        if ($dadosUsuario === false) {
            return null;
        }

        return Usuario::fromArray($dadosUsuario);
    }

    public function add(Usuario $usuario): void
    {
        $sqlQuery = "INSERT INTO usuarios (nome, email, cpf, hash_senha, permissao) VALUES (:nome, :email, :cpf, :hash_senha, :permissao);";

        $usuarioArray = $usuario->toArray();

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':nome', $usuarioArray['nome']);
        $stmt->bindValue(':email', $usuarioArray['email']);
        $stmt->bindValue(':cpf', $usuarioArray['cpf']);
        $stmt->bindValue(':hash_senha', $usuarioArray['hash_senha']);
        $stmt->bindValue(':permissao', $usuarioArray['permissao']);

        $stmt->execute();
    }

    public function update(Usuario $usuario): void
    {
        $sqlQuery = "UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, permissao = :permissao, status = :status WHERE id = :id;";

        $usuarioArray = $usuario->toArray();

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':nome', $usuarioArray['nome']);
        $stmt->bindValue(':email', $usuarioArray['email']);
        $stmt->bindValue(':cpf', $usuarioArray['cpf']);
        $stmt->bindValue(':permissao', $usuarioArray['permissao']);
        $stmt->bindValue(':status', $usuarioArray['status']);
        $stmt->bindValue(':id', $usuarioArray['id']);

        $stmt->execute();
    }

    public function alteraSenha(int $id, string $hash_senha): void
    {
        $sqlQuery = "UPDATE usuarios SET hash_senha = :hash_senha WHERE id = :id;";

        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(':hash_senha', $hash_senha);
        $stmt->bindValue(':id', $id);

        $stmt->execute();
    }

    private function hidrataLista(PDOStatement $stmt): array
    {
        $listaDeUsuarios = [];

        while($dadosUsuario = $stmt->fetch()){
            $listaDeUsuarios[] = Usuario::fromArray($dadosUsuario);
        }

        return $listaDeUsuarios;
    }
}