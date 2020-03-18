
$(document).ready(function() {

    let btnUpdateFornecedor = $(".update-fornecedor");
    let btnDeleteFornecedor = $(".delete-fornecedor");
    let btnDeleteFornecedores = $('#btn-delete-fornecedores');
    let modalUpdate = $('#modal-update');
    let modalDelete = $('#modal-delete');

    function getFornecedor(id, callback){

        $.ajax({
            method: "POST",
            url: "/fornecedor/getFornecedor",
            data: 'id_fornecedor=' + id,
            dataType: "json",
            success: function (response) {
                callback(response);
            }
        });
    }

    function deleteFornecedor(id, callback){

        $.ajax({
            method: "POST",
            url: "/fornecedor/delete",
            data: 'id_fornecedor=' + id,
            success: function (response) {
                callback(response);
            }
        });
    }

    /***************************** AÇÕES *****************************/
    btnUpdateFornecedor.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        getFornecedor(id, function(response){

            modalUpdate.modal('show');
            modalUpdate.find('input[name="idFornecedor"]').val(response.id_fornecedor);
            modalUpdate.find('input[name="nomeFantasia"]').val(response.nome_fantasia);
            modalUpdate.find('input[name="razaoSocial"]').val(response.razao_social);
            modalUpdate.find('input[name="cnpj"]').val(response.cnpj);
            modalUpdate.find('input[name="enderecoRua"]').val(response.endereco_rua);
            modalUpdate.find('input[name="enderecoNumero"]').val(response.endereco_numero);
            modalUpdate.find('input[name="enderecoBairro"]').val(response.endereco_bairro);
        });
    });

    btnDeleteFornecedor.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        modalDelete.modal('show');
        $(".btn-delete").on("click", function (ev) {

            ev.preventDefault();

            deleteFornecedor(id, function(response){
                location.reload();
            });
        });
    });

    btnDeleteFornecedores.click(function () {

        let checkbox = $('table tbody input[type="checkbox"]');
        let fornecedores = [];

        checkbox.each(function () {
            if (this.checked) {
                fornecedores.push($(this).val());
            }
        });

        if (fornecedores.length === 0) {
            alert('Nenhum registro selecionado.');
        } else {

            modalDelete.modal('show');
            $(".btn-delete").on("click", function (ev) {

                ev.preventDefault();

               deleteFornecedor(fornecedores, function(response){
                    location.reload();
                });
            });
        }
    });
});