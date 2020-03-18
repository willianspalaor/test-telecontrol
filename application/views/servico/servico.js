
$(document).ready(function() {

    let btnUpdateServico = $(".update-servico");
    let btnDeleteServico = $(".delete-servico");
    let btnDeleteServicos = $('#btn-delete-servicos');
    let modalUpdate = $('#modal-update');
    let modalDelete = $('#modal-delete');

    function getServico(id, callback){

        $.ajax({
            method: "POST",
            url: "/servico/getServico",
            data: 'id_servico=' + id,
            dataType: "json",
            success: function (response) {
                callback(response);
            }
        });
    }

    function deleteServico(id, callback){

        $.ajax({
            method: "POST",
            url: "/servico/delete",
            data: 'id_servico=' + id,
            success: function (response) {
                callback(response);
            }
        });
    }

    /***************************** AÇÕES *****************************/

    btnUpdateServico.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        getServico(id, function(response){
            modalUpdate.modal('show');
            modalUpdate.find('input[name="idServico"]').val(response.id_servico);
            modalUpdate.find('input[name="nome"]').val(response.nome);
            modalUpdate.find('input[name="valor"]').val(response.valor);
            modalUpdate.find('input[name="descricao"]').val(response.descricao);
        });
    });

    btnDeleteServico.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        modalDelete.modal('show');
        $(".btn-delete").on("click", function (ev) {

            ev.preventDefault();

            deleteServico(id, function(response){
                location.reload();
            });
        });
    });

    btnDeleteServicos.click(function () {

        let checkbox = $('table tbody input[type="checkbox"]');
        let servicos = [];

        checkbox.each(function () {
            if (this.checked) {
                servicos.push($(this).val());
            }
        });

        if (servicos.length === 0) {
            alert('Nenhum registro selecionado.');
        } else {

            modalDelete.modal('show');
            $(".btn-delete").on("click", function (ev) {

                ev.preventDefault();

                deleteServico(servicos, function(response){
                    location.reload();
                });
            });
        }
    });

    $('.valor-servico').maskMoney();
});