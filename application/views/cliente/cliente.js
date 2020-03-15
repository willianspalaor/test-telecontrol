
$(document).ready(function() {

    let selectEstado = $('.select-estado');
    let selectCidade = $('.select-cidade');

    $.ajax({
        method: "GET",
        url: "https://servicodados.ibge.gov.br/api/v1/localidades/estados",
        dataType: "json",
        success: function (response) {
            $.each(response, function (key, estado) {

                let option =
                selectEstado.append($('<option></option>').attr('value', estado.sigla).text(estado.sigla).data('id', estado.id));
            });
        }
    });

    selectEstado.on('change', function(){

        let estado = $(this).children("option:selected");

        selectCidade.text('');
        selectCidade.append($('<option></option>').attr('value', '').text(''));

        if(estado.val() === ''){
            selectCidade.prop("disabled", true);
        }else{
            $.ajax({
                method: "GET",
                url: "https://servicodados.ibge.gov.br/api/v1/localidades/estados/" + estado.data('id') +"/municipios",
                dataType: "json",
                success: function (response) {
                    $.each(response, function (key, estado) {
                        selectCidade.append($('<option></option>').attr('value', estado.nome).text(estado.nome));
                    });
                    selectCidade.prop("disabled", false);
                }
            });
        }
    });

    $(".update-cliente").click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        $.ajax({
            method: "POST",
            url: "/cliente/getCliente",
            data: 'id_cliente=' + id,
            dataType: "json",
            success: function (response) {
                $('#modal-update').modal('show');
                $('#modal-update').find('input[name="idCliente"]').val(response.id_cliente);
                $('#modal-update').find('input[name="nome"]').val(response.nome);
                $('#modal-update').find('input[name="telefone"]').val(response.telefone);
                $('#modal-update').find('input[name="cpfCnpj"]').val(response.cpf_cnpj);
                $('#modal-update').find('input[name="enderecoRua"]').val(response.endereco_cidade);
                $('#modal-update').find('input[name="enderecoRua"]').val(response.endereco_rua);
                $('#modal-update').find('input[name="enderecoNumero"]').val(response.endereco_numero);
                $('#modal-update').find('input[name="enderecoBairro"]').val(response.endereco_bairro);
            }
        });
    });

    $(".delete-cliente").click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        $('#modal-delete').modal('show');
        $(".btn-delete").on("click", function (ev) {

            ev.preventDefault();

            $.ajax({
                method: "POST",
                url: "/cliente/delete",
                data: 'id_cliente=' + id,
                success: function (response) {
                    $('#delete-modal').modal('hide');
                    location.reload();
                }
            });
        });
    });

    $('#btn-delete-clientes').click(function () {

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

            $('#modal-delete').modal('show');
            $(".btn-delete").on("click", function (ev) {
                $.ajax({
                    method: "POST",
                    url: "/cliente/delete",
                    data: 'id_cliente=' + clientes,
                    success: function (response) {
                        $('#delete-modal').modal('hide');
                        location.reload();
                    }
                });
            });
        }
    });
});