<?php

namespace Mateus\ProtocolTrackerV2\Controller\Admin;

use Exception;
use Mateus\ProtocolTrackerV2\Model\Usuario;
use Mateus\ProtocolTrackerV2\Repository\UsuarioRepository;
use Mateus\ProtocolTrackerV2\Service\UsuarioService;

class UsuarioController
{
    private ?Usuario $usuario_logado = null;
    private ?string $permissao = null;

    public function __construct(
        private UsuarioRepository $usuarioRepository,
        private UsuarioService $usuarioService
    ) {
        $id_usuario = $_SESSION['usuario_logado_id'] ?? null;
        if ($id_usuario) {
            $this->usuario_logado = $this->usuarioRepository->findById($id_usuario);
        }
        
        if ($this->usuario_logado) {
            $this->permissao = $this->usuario_logado->getPermissao();
        }
    }

    //GET
    public function exibirNovoUsuario(?string $erro = null)
    {
        try {
            $titulo_da_pagina = "Cadastrar Novo Usuário";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../../templates/admin/novo-usuario.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //POST
    public function novoUsuario()
    {
        try {
            //public function novoUsuario(string $nome, string $email, string $cpf, string $senha, string $permissao): void
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $cpf = $_POST['cpf'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $permissao = $_POST['permissao'] ?? '';

            $this->usuarioService->novoUsuario($nome, $email, $cpf, $senha, $permissao);

            $_SESSION['mensagem_sucesso'] = "Usuário criado com sucesso!";

            header('Location: /admin/usuarios/visualizar-usuarios');
            exit();
        } catch (Exception $e) {
            $this->exibirNovoUsuario($e->getMessage());
        }
    }

    //GET
    public function exibirUsuarios()
    {
        try {
            $listaUsuarios = $this->usuarioRepository->all();

            $titulo_da_pagina = "Lista de Usuários";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../../templates/admin/visualizar-usuarios.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //GET
    public function exibirEditarUsuario(?string $erro = null)
    {
        try {
            $id_usuario = $_GET['id'] ?? '';
            $usuario = $this->usuarioRepository->findById($id_usuario);

            $titulo_da_pagina = "Edição de Usuário";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../../templates/admin/editar-usuario.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //POST
    public function editarUsuario()
    {
        try {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $cpf = $_POST['cpf'] ?? '';
            $permissao = $_POST['permissao'] ?? '';
            $status = $_POST['status'] ?? '';
            $id = $_POST['id'] ?? '';

            $this->usuarioService->atualizaUsuario($nome, $email, $cpf, $permissao, $status, $id);

            $_SESSION['mensagem_sucesso'] = "Usuário editado com sucesso!";

            header('Location: /admin/usuarios/visualizar-usuarios');
            exit();
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //GET
    public function exibirResetarSenhaUsuario()
    {
        try {
            $id_usuario = $_GET['id'] ?? '';
            $usuario = $this->usuarioRepository->findById($id_usuario);

            $titulo_da_pagina = "Resetar Senha";
            $usuario_logado = $this->usuario_logado;
            $permissao = $this->permissao;

            require __DIR__ . '/../../../templates/admin/resetar-senha.php';
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    //POST
    public function resetarSenhaUsuario()
    {
        try {
            $id = $_POST['id'] ?? '';
            $senha = $_POST['nova_senha'] ?? '';

            $this->usuarioService->alteraSenha($id, $senha);

            $_SESSION['mensagem_sucesso'] = "Senha resetada com sucesso!";

            header('Location: /admin/usuarios/visualizar-usuarios');
            exit();
        } catch (Exception $e) {
            $this->home($e->getMessage());
        }
    }

    private function home(?string $erro = null)
    {
        $titulo_da_pagina = "Home";
        $usuario_logado = $this->usuario_logado;
        $permissao = $this->permissao;

        require __DIR__ . '/../../../templates/home.php';
    }
}
