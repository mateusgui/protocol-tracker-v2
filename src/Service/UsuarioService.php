<?php

namespace Mateus\ProtocolTrackerV2\Service;

use Exception;
use Mateus\ProtocolTrackerV2\Interfaces\UsuarioRepositoryInterface;
use Mateus\ProtocolTrackerV2\Model\Usuario;

class UsuarioService
{
    public function __construct(
        private UsuarioRepositoryInterface $usuarioRepository
    ) {}

    public function novoUsuario(string $nome, string $email, string $cpf, string $senha, string $permissao): void
    {
        $this->validaCpf($cpf);
        $this->validaSenha($senha);
        $this->validaEmail($email);

        if($permissao !== 'preparador' && $permissao !== 'digitalizador' && $permissao !== 'administrador'){
            throw new Exception("Permissão selecionada não existe");
        }

        //Validar se o cpf já está cadastrado
        if($this->usuarioRepository->findByCpf($cpf) !== null){
            throw new Exception("O CPF informado já está cadastrado");
        }

        //Validar se o email já está cadastrado
        if($this->usuarioRepository->findByEmail($email) !== null){
            throw new Exception("O email informado já está cadastrado");
        }

        $hash_senha = password_hash($senha, PASSWORD_ARGON2ID);

        $usuario = new Usuario(
            null,
            $nome,
            $email,
            $cpf,
            $hash_senha,
            $permissao,
            null,
            null
        );

        $this->usuarioRepository->add($usuario);
    }

    public function atualizaUsuario(string $nome, string $email, string $cpf, string $permissao, bool $status, int $id): void
    {
        $usuarioAntigo = $this->usuarioRepository->findById($id);
        if ($usuarioAntigo === null) {
            throw new Exception("O usuário informado não existe");
        }

        $this->validaCpf($cpf);
        $this->validaEmail($email);

        if($this->usuarioRepository->findByCpf($cpf) !== null && $usuarioAntigo->getCpf() !== $cpf){
            throw new Exception("O CPF informado já está cadastrado");
        }

        if($this->usuarioRepository->findByEmail($email) !== null && $usuarioAntigo->getEmail() !== $email){
            throw new Exception("O email informado já está cadastrado");
        }

        if($permissao !== 'preparador' && $permissao !== 'digitalizador' && $permissao !== 'administrador'){
            throw new Exception("Permissão selecionada não existe");
        }

        $usuario = new Usuario(
            $id,
            $nome,
            $email,
            $cpf,
            $usuarioAntigo->getHashSenha(),
            $permissao,
            $usuarioAntigo->getDataCriacao(),
            $status
        );

        $this->usuarioRepository->update($usuario);
    }

    public function alteraSenha(int $id, string $senha): void
    {
        $usuario = $this->usuarioRepository->findById($id);
        if ($usuario === null) {
            throw new Exception("O usuário informado não existe");
        }

        if(!$usuario->getStatus()){
            throw new Exception("Não é possível redefinir a senha de usuários inativos");
        }

        $this->validaSenha($senha);

        $hash_senha = password_hash($senha, PASSWORD_ARGON2ID);

        $this->usuarioRepository->alteraSenha($id, $hash_senha);
    }

    public function confirmarSenha(int $id_usuario, string $senhaFornecida): bool
    {
        $usuario = $this->usuarioRepository->findById($id_usuario);
        if ($usuario === null) {
            throw new Exception("Usuário não encontrado para verificação de senha.");
        }

        return password_verify($senhaFornecida, $usuario->getHashSenha());
    }

    private function validaCpf(string $cpf): void
    {
        // Verifica se o CPF tem 11 dígitos numéricos
        if (!preg_match('/^\d{11}$/', $cpf)) {
            throw new Exception("O CPF precisa ter 11 dígitos numéricos");
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/^(\d)\1*$/', $cpf)) {
            throw new Exception("O CPF informado é inválido.");
        }

        //Calcula o primeiro dígito
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += (int)$cpf[$i] * (10 - $i);
        }
        $primeiroDigito = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);

        //Calcula o segundo dígito
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += (int)$cpf[$i] * (11 - $i);
        }
        $segundoDigito = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);

        $cpfValido = ($cpf[9] == $primeiroDigito && $cpf[10] == $segundoDigito);
        if (!$cpfValido) {
            throw new Exception("O CPF informado é inválido.");
        }
    }

    /**
     * Valida o Email que o Usuario digitar
     * @throws Exception Se os dados forem inválidos.
     * @param string $email
     * @return void
     */
    private function validaEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("O e-mail informado é inválido");
        }
    }

    /**
     * Valida a senha e confirmação de senha que o Usuario digitar
     * @throws Exception Se os dados forem inválidos.
     * @param string $senha
     * @param string $confirmaSenha
     * @return void
     */
    private function validaSenha(string $senha): void
    {
        if (strlen($senha) < 6) {
            throw new Exception("A senha deve ter pelo menos 6 caracteres.");
        }
    }
}


