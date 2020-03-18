
$(document).ready(function() {

    let btnUpdateCliente = $(".update-cliente");
    let btnDeleteCliente = $(".delete-cliente");
    let btnDeleteClientes = $('#btn-delete-clientes');
    let modalUpdate = $('#modal-update');
    let modalDelete = $('#modal-delete');
    let selectEstado = $('.select-estado');
    let selectCidade = $('.select-cidade');
    let cidade = '';

    function getEstados(callback){

        $.ajax({
            method: "GET",
            url: "https://servicodados.ibge.gov.br/api/v1/localidades/estados",
            dataType: "json",
            success: function (response) {
                callback(response);
            }
        });
    }

    function getMunicipios(id, callback){

        $.ajax({
            method: "GET",
            url: "https://servicodados.ibge.gov.br/api/v1/localidades/estados/" + id +"/municipios",
            dataType: "json",
            success: function (response) {
                callback(response);
            }
        });
    }

    function getClient(id, callback){

        $.ajax({
            method: "POST",
            url: "/cliente/getCliente",
            data: 'id_cliente=' + id,
            dataType: "json",
            success: function (response) {
                callback(response)
            }
        });
    }

    function deleteClient(id, callback){

        $.ajax({
            method: "POST",
            url: "/cliente/delete",
            data: 'id_cliente=' + id,
            success: function (response) {
                callback(response);
            }
        });
    }

    /***************************** AÇÕES *****************************/

    getEstados(function(response){
        $.each(response, function (key, estado) {
            selectEstado.append($('<option></option>').attr('value', estado.sigla).text(estado.sigla).data('id', estado.id));
        });
    });

    btnUpdateCliente.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        getClient(id, function(response){

            modalUpdate.modal('show');
            modalUpdate.find('input[name="idCliente"]').val(response.id_cliente);
            modalUpdate.find('input[name="nomeCliente"]').val(response.nome_cliente);
            modalUpdate.find('input[name="telefoneCliente"]').val(response.telefone_cliente);
            modalUpdate.find('input[name="cpfCnpjCliente"]').val(response.cpfcnpj_cliente);
            modalUpdate.find('select[name="enderecoEstado"]').val(response.endereco_estado);
            modalUpdate.find('input[name="enderecoRua"]').val(response.endereco_rua);
            modalUpdate.find('input[name="enderecoNumero"]').val(response.endereco_numero);
            modalUpdate.find('input[name="enderecoBairro"]').val(response.endereco_bairro);
            cidade = response.endereco_cidade;
            selectEstado.trigger( "change" );
        });
    });

    btnDeleteCliente.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        modalDelete.modal('show');
        $(".btn-delete").on("click", function (ev) {

            ev.preventDefault();

            deleteClient(id, function(response){
                location.reload();
            });
        });
    });

    btnDeleteClientes.click(function () {

        let checkbox = $('table tbody input[type="checkbox"]');
        let clientes = [];

        checkbox.each(function () {
            if (this.checked) {
                clientes.push($(this).val());
            }
        });

        if (clientes.length === 0) {
            alert('Nenhum registro selecionado.');
        } else {

            modalDelete.modal('show');
            $(".btn-delete").on("click", function (ev) {

                ev.preventDefault();

                deleteClient(clientes, function(response){
                    location.reload();
                });
            });
        }
    });

    selectEstado.on('change', function(){

        let option = $(this).children("option:selected");
        selectCidade.text('');
        selectCidade.append($('<option></option>').attr('value', '').text(''));

        if(option.val() === ''){
            selectCidade.prop("disabled", true);
        }else{
            getMunicipios(option.data('id'), function(response){

                $.each(response, function (key, estado) {
                    selectCidade.append($('<option></option>').attr('value', estado.nome).text(estado.nome));
                });
                selectCidade.prop("disabled", false);

                if(cidade !== ''){
                    selectCidade.val(cidade);
                    cidade = '';
                }
            });
        }
    });
});