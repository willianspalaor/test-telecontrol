
$(document).ready(function() {

    let btnUpdateProduto = $(".update-produto");
    let btnDeleteProduto = $(".delete-produto");
    let btnDeleteProdutos = $('#btn-delete-produtos');
    let modalUpdate = $('#modal-update');
    let modalDelete = $('#modal-delete');

    function getProduto(id, callback){

        $.ajax({
            method: "POST",
            url: "/produto/getProduto",
            data: 'id_produto=' + id,
            dataType: "json",
            success: function (response) {
                callback(response);
            }
        });
    }

    function deleteProduto(id, callback){

        $.ajax({
            method: "POST",
            url: "/produto/delete",
            data: 'id_produto=' + id,
            success: function (response) {
                callback(response);
            }
        });
    }

    /***************************** AÇÕES *****************************/

    btnUpdateProduto.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        getProduto(id, function(response){
            modalUpdate.modal('show');
            modalUpdate.find('input[name="idProduto"]').val(response.id_produto);
            modalUpdate.find('input[name="descricaoProduto"]').val(response.descricao_produto);
            modalUpdate.find('input[name="referenciaProduto"]').val(response.referencia_produto);
            modalUpdate.find('input[name="garantiaProduto"]').val(response.garantia_produto);
            modalUpdate.find('select[name="tensaoProduto"]').val(response.tensao_produto);
        });
    });

    btnDeleteProduto.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        modalDelete.modal('show');
        $(".btn-delete").on("click", function (ev) {

            ev.preventDefault();

            deleteProduto(id, function(response){
                location.reload();
            });
        });
    });

    btnDeleteProdutos.click(function () {

        let checkbox = $('table tbody input[type="checkbox"]');
        let produtos = [];

        checkbox.each(function () {
            if (this.checked) {
                produtos.push($(this).val());
            }
        });

        if (produtos.length === 0) {
            alert('Nenhum registro selecionado.');
        } else {

            modalDelete.modal('show');
            $(".btn-delete").on("click", function (ev) {

                ev.preventDefault();

                deleteProduto(produtos, function(response){
                    location.reload();
                });
            });
        }
    });
});