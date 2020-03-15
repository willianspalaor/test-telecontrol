$(".update-fornecedor").click(function() {

    /* Recupera o id da linha <tr> */
    let id = $(this).parent().parent().data("id");

    $.ajax({
        method: "POST",
        url: "/fornecedor/getFornecedor",
        data: 'id_fornecedor=' + id,
        dataType:"json",
        success: function (response) {
            $('#modal-update').modal('show');
            $('#modal-update').find('input[name="idFornecedor"]').val(response.id_fornecedor);
            $('#modal-update').find('input[name="nomeFantasia"]').val(response.nome_fantasia);
            $('#modal-update').find('input[name="razaoSocial"]').val(response.razao_social);
            $('#modal-update').find('input[name="cnpj"]').val(response.cnpj);
            $('#modal-update').find('input[name="enderecoRua"]').val(response.endereco_rua);
            $('#modal-update').find('input[name="enderecoNumero"]').val(response.endereco_numero);
            $('#modal-update').find('input[name="enderecoBairro"]').val(response.endereco_bairro);
        }
    });
});

$(".delete-fornecedor").click(function() {

    /* Recupera o id da linha <tr> */
    let id = $(this).parent().parent().data("id");

    $('#modal-delete').modal('show');
    $(".btn-delete").on("click", function (ev) {

        ev.preventDefault();

        $.ajax({
            method: "POST",
            url: "/fornecedor/delete",
            data: 'id_fornecedor=' + id,
            success: function(response) {
                $('#delete-modal').modal('hide');
                location.reload();
            }
        });
    });
});

$('#btn-delete-fornecedores').click(function(){

    let checkbox = $('table tbody input[type="checkbox"]');
    let fornecedores = [];

    checkbox.each(function(){
        if(this.checked){
            fornecedores.push($(this).val());
        }
    });

   if(fornecedores.length === 0){
       alert('Nenhum registro selecionado.');
   }else{

       $('#modal-delete').modal('show');
       $(".btn-delete").on("click", function (ev) {
           $.ajax({
               method: "POST",
               url: "/fornecedor/delete",
               data: 'id_fornecedor=' + fornecedores,
               success: function(response) {
                   $('#delete-modal').modal('hide');
                   location.reload();
               }
           });
       });
   }
});