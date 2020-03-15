
$(document).ready(function() {

    $(".update-item").click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        $.ajax({
            method: "POST",
            url: "/item/getItem",
            data: 'id_item=' + id,
            dataType: "json",
            success: function (response) {
                $('#modal-update').modal('show');
                $('#modal-update').find('input[name="idItem"]').val(response.id_item);
                $('#modal-update').find('input[name="nome"]').val(response.nome);
                $('#modal-update').find('input[name="quantidade"]').val(response.quantidade);
                $('#modal-update').find('select[name="unidade"]').val(response.unidade);
                $('#modal-update').find('input[name="descricao"]').val(response.descricao);
            }
        });
    });

    $(".delete-item").click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        $('#modal-delete').modal('show');
        $(".btn-delete").on("click", function (ev) {

            ev.preventDefault();

            $.ajax({
                method: "POST",
                url: "/item/delete",
                data: 'id_item=' + id,
                success: function (response) {
                    $('#delete-modal').modal('hide');
                    location.reload();
                }
            });
        });
    });

    $('#btn-delete-itens').click(function () {

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

            $('#modal-delete').modal('show');
            $(".btn-delete").on("click", function (ev) {
                $.ajax({
                    method: "POST",
                    url: "/item/delete",
                    data: 'id_item=' + itens,
                    success: function (response) {
                        $('#delete-modal').modal('hide');
                        location.reload();
                    }
                });
            });
        }
    });
});