document.addEventListener('DOMContentLoaded', () => {

    // --- LÓGICA PARA A FLASH MESSAGE ---
    const flashMessage = document.querySelector('.flash-message');

    if (flashMessage) {
        setTimeout(() => {
            flashMessage.classList.add('fade-out');
            setTimeout(() => {
                flashMessage.remove();
            }, 500);
        }, 4000);
    }


    // --- LÓGICA PARA A MÁSCARA DE CPF ---
    const campoCpfVisivel = document.getElementById('cpf_formatado');
    const campoCpfPuro = document.getElementById('cpf_puro');

    if (campoCpfVisivel && campoCpfPuro) {
        
        campoCpfVisivel.addEventListener('input', () => {
            let valorLimpo = campoCpfVisivel.value.replace(/\D/g, '');

            valorLimpo = valorLimpo.substring(0, 11);

            campoCpfPuro.value = valorLimpo;

            let valorFormatado = valorLimpo;
            if (valorLimpo.length > 3) {
                valorFormatado = valorLimpo.replace(/(\d{3})(\d)/, '$1.$2');
            }
            if (valorLimpo.length > 6) {
                valorFormatado = valorFormatado.replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
            }
            if (valorLimpo.length > 9) {
                valorFormatado = valorFormatado.replace(/(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
            }
            
            campoCpfVisivel.value = valorFormatado;
        });
    }


    // --- LÓGICA PARA O DROPDOWN DE USUÁRIO ---
    const userMenuToggle = document.getElementById('user-menu-toggle');
    const userMenu = document.getElementById('user-menu');

    if (userMenuToggle && userMenu) {
        userMenuToggle.addEventListener('click', (event) => {
            event.preventDefault();
            userMenu.parentElement.classList.toggle('show');
        });

        window.addEventListener('click', (event) => {
            if (!userMenu.parentElement.contains(event.target)) {
                userMenu.parentElement.classList.remove('show');
            }
        });
    }


    // --- LÓGICA PARA INICIALIZAR O DATATABLES ---
    $('#tabela-protocolos').DataTable({
        searching: false,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json',
        },
        order: [1], 
        pageLength: 5, 
        lengthMenu: [5, 10, 25, 50, 100],
    });

    // --- LÓGICA PARA O MENU ACORDEÃO DA SIDEBAR ---
    // Aplica a altura correta para os menus que já carregam abertos
    document.querySelectorAll('.has-submenu.open').forEach(openLi => {
        const submenu = openLi.querySelector('.submenu');
        if (submenu) {
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
        }
    });

    const submenuToggleLinks = document.querySelectorAll('.has-submenu > a');
    submenuToggleLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const parentLi = this.parentElement;

            // Fecha todos os outros submenus abertos para ter apenas um aberto por vez
            document.querySelectorAll('.has-submenu.open').forEach(openLi => {
                if (openLi !== parentLi) {
                    openLi.classList.remove('open');
                    openLi.querySelector('.submenu').style.maxHeight = '0px';
                }
            });

            // Abre ou fecha o submenu atual
            parentLi.classList.toggle('open');
            
            const submenu = this.nextElementSibling;
            if (parentLi.classList.contains('open')) {
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
            } else {
                submenu.style.maxHeight = '0px';
            }
        });
    });

    // --- LÓGICA PARA A MODAL DE PREPARAÇÃO DE PROTOCOLO ---
    const modal = document.getElementById('modal-preparar');
    const btnsAbrirModal = document.querySelectorAll('.open-modal-preparar');
    const btnsFecharModal = document.querySelectorAll('.close-modal-btn');

    // Verifica se os elementos da modal existem na página
    if (modal && btnsAbrirModal.length > 0) {
        
        // Adiciona evento para todos os botões "Preparar"
        btnsAbrirModal.forEach(btn => {
            btn.addEventListener('click', function() {
                // Pega os dados do protocolo a partir dos atributos data-* do botão
                const protocoloId = this.dataset.protocoloId;
                const protocoloNumero = this.dataset.protocoloNumero;

                // Preenche os campos da modal com os dados
                document.getElementById('modal-protocolo-id').value = protocoloId;
                document.getElementById('modal-protocolo-numero').textContent = protocoloNumero;
                
                // Exibe a modal
                modal.style.display = 'flex';
            });
        });

        // Adiciona evento para fechar a modal
        btnsFecharModal.forEach(btn => {
            btn.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        });

        // Fecha a modal se o usuário clicar fora dela (no overlay)
        window.addEventListener('click', (event) => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    }


    // --- LÓGICA PARA A MODAL DE DIGITALIZAÇÃO DE PROTOCOLO ---
    const modalDigitalizar = document.getElementById('modal-digitalizar');
    const btnsAbrirModalDigitalizar = document.querySelectorAll('.open-modal-digitalizar');

    // Seleciona os botões de fechar da nova modal
    const btnsFecharModalDigitalizar = modalDigitalizar?.querySelectorAll('.close-modal-btn');

    if (modalDigitalizar && btnsAbrirModalDigitalizar.length > 0) {
        
        btnsAbrirModalDigitalizar.forEach(btn => {
            btn.addEventListener('click', function() {
                // Pega os dados do protocolo a partir dos atributos data-*
                const protocoloId = this.dataset.protocoloId;
                const protocoloNumero = this.dataset.protocoloNumero;
                const protocoloObservacoes = this.dataset.protocoloObservacoes;

                // Preenche os campos da modal
                document.getElementById('modal-protocolo-id-dig').value = protocoloId;
                document.getElementById('modal-protocolo-numero-dig').textContent = protocoloNumero;
                document.getElementById('observacoes_modal_dig').value = protocoloObservacoes; // Carrega a observação do preparador
                
                modalDigitalizar.style.display = 'flex';
            });
        });

        btnsFecharModalDigitalizar.forEach(btn => {
            btn.addEventListener('click', () => {
                modalDigitalizar.style.display = 'none';
            });
        });

        window.addEventListener('click', (event) => {
            if (event.target == modalDigitalizar) {
                modalDigitalizar.style.display = 'none';
            }
        });
    }


    // --- LÓGICA PARA A MODAL DE ENTREGA DE REMESSA ---
const modalEntregar = document.getElementById('modal-entregar');
const btnsAbrirModalEntregar = document.querySelectorAll('.open-modal-entregar');

if (modalEntregar && btnsAbrirModalEntregar.length > 0) {
    const btnsFecharModalEntregar = modalEntregar.querySelectorAll('.close-modal-btn');
    
    btnsAbrirModalEntregar.forEach(btn => {
        btn.addEventListener('click', function() {
            const remessaId = this.dataset.remessaId;
            const remessaNumero = this.dataset.remessaNumero;

            // Preenche os campos da modal
            modalEntregar.querySelector('#modal-remessa-id').value = remessaId;
            modalEntregar.querySelector('#modal-remessa-numero').textContent = remessaNumero;
            
            // Limpa o campo de senha ao abrir
            modalEntregar.querySelector('#senha_confirmacao').value = '';

            // Exibe a modal
            modalEntregar.style.display = 'flex';
        });
    });

    btnsFecharModalEntregar.forEach(btn => {
        btn.addEventListener('click', () => {
            modalEntregar.style.display = 'none';
        });
    });

    window.addEventListener('click', (event) => {
        if (event.target == modalEntregar) {
            modalEntregar.style.display = 'none';
        }
    });
}

});