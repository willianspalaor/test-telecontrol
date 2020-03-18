
$(document).ready(function() {

    let btnUpdateItem = $(".update-item");
    let btnDeleteItem = $(".delete-item");
    let btnDeleteItens = $('#btn-delete-itens');
    let modalUpdate = $('#modal-update');
    let modalDelete = $('#modal-delete');

    function getItem(id, callback){

        $.ajax({
            method: "POST",
            url: "/item/getItem",
            data: 'id_item=' + id,
            dataType: "json",
            success: function (response) {
               callback(response);
            }
        });
    }

    function deleteItem(id, callback){

        $.ajax({
            method: "POST",
            url: "/item/delete",
            data: 'id_item=' + id,
            success: function (response) {
                callback(response);
            }
        });
    }

    /***************************** AÇÕES *****************************/

    btnUpdateItem.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        getItem(id, function(response){
            modalUpdate.modal('show');
            modalUpdate.find('input[name="idItem"]').val(response.id_item);
            modalUpdate.find('select[name="unidadeItem"]').val(response.unidade_item);
            modalUpdate.find('input[name="descricaoItem"]').val(response.descricao_item);
        })
    });

    btnDeleteItem.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        modalDelete.modal('show');
        $(".btn-delete").on("click", function (ev) {

            ev.preventDefault();

            deleteItem(id, function(response){
                location.reload();
            });
        });
    });

    btnDeleteItens.click(function () {

        let checkbox = $('table tbody input[type="checkbox"]');
        let itens = [];

        checkbox.each(function () {
            if (this.checked) {
                itens.push($(this).val());
            }
        });

        if (itens.length === 0) {
            alert('Nenhum registro selecionado.');
        } else {

            modalDelete.modal('show');
            $(".btn-delete").on("click", function (ev) {

                ev.preventDefault();

                deleteItem(itens, function(response){
                    location.reload();
                })
            });
        }
    });
});