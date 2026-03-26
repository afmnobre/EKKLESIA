// Escutador global para elementos do modal de endereço
document.addEventListener('keyup', function(event) {
    if (event.target && event.target.id === 'membro_cep') {
        let cep = event.target.value.replace(/\D/g, '');

        // Aplica máscara visual 00000-000
        if (cep.length > 5) {
            event.target.value = cep.slice(0, 5) + '-' + cep.slice(5, 8);
        }

        // Quando atingir 8 números, busca automaticamente
        if (cep.length === 8) {
            consultarViaCep(cep);
        }
    }
});

document.addEventListener('click', function(event) {
    if (event.target && event.target.id === 'btnBuscarCep' || event.target.closest('#btnBuscarCep')) {
        const cep = document.getElementById('membro_cep').value.replace(/\D/g, '');
        consultarViaCep(cep);
    }
});

function consultarViaCep(cep) {
    if (cep.length !== 8) return;

    // Sinaliza carregamento nos campos
    const campos = ['rua', 'bairro', 'cidade', 'uf'];
    campos.forEach(c => document.getElementById('membro_' + c).value = '...');

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                alert("CEP não encontrado!");
                campos.forEach(c => document.getElementById('membro_' + c).value = '');
                return;
            }
            // Preenche os campos com o retorno da API
            document.getElementById('membro_rua').value = data.logradouro;
            document.getElementById('membro_bairro').value = data.bairro;
            document.getElementById('membro_cidade').value = data.localidade;
            document.getElementById('membro_uf').value = data.uf;

            // Foca no número para agilizar o preenchimento
            document.getElementById('membro_numero').focus();
        })
        .catch(err => {
            console.error("Erro na busca do CEP:", err);
            alert("Erro ao conectar com o serviço de CEP.");
        });
}
