$(document).ready(function() {

    let selectEstado = $('.select-estado');
    let selectCidade = $('.select-cidade');
    let idCliente = $('input[name="idCliente"]');
    let cpfCnpjCliente = $('input[name="cpfCnpjCliente"]');
    let nomeCliente = $('input[name="nomeCliente"]');
    let telefoneCliente = $('input[name="telefoneCliente"]');
    let enderecoEstadoCliente = $('select[name="enderecoEstado"]');
    let enderecoCidadeCliente = $('select[name="enderecoCidade"]');
    let enderecoRuaCliente = $('input[name="enderecoRua"]');
    let enderecoBairroCliente = $('input[name="enderecoBairro"]');
    let enderecoNumeroCliente = $('input[name="enderecoNumero"]');
    let idProduto = $('input[name="idProduto"]');
    let referenciaProduto = $('input[name="referenciaProduto"]');
    let descricaoProduto = $('input[name="descricaoProduto"]');
    let tensaoProduto = $('select[name="tensaoProduto"]');
    let garantiaProduto = $('input[name="garantiaProduto"]');
    let dataCompra = $('input[name="dataCompra"]');
    let dataEmissao = $('input[name="dataEmissao"]');
    let idOrdemServico = $('input[name="idOrdemServico"]');
    let notaFiscal = $('input[name="notaFiscal"]');
    let statusGarantia = $('input[name="statusGarantia"]');
    let btnUpdateOrdemServico = $(".update-ordem-servico");
    let btnDeleteOrdemServico = $(".delete-ordem-servico");
    let btnFinalizarOrdemServico = $('.finalizar-ordem-servico');
    let btnAddNovoItem = $('.add-novo-item');
    let btnDeleteOrdensServico = $('#btn-delete-ordens-servico');
    let selectFiltro = $('#filtroOrdens');
    let modalUpdate = $('#modal-update');
    let modalDelete = $('#modal-delete');
    let modalFinish = $('#modal-finish');
    let itens = [];
    let countRows = 1;

    let cidade = '';
    disableClientFields();
    disableProductFields();

    function enableClientFields(){
        nomeCliente.prop('disabled', false);
        telefoneCliente.prop('disabled', false);
        enderecoEstadoCliente.prop('disabled', false);
        enderecoRuaCliente.prop('disabled', false);
        enderecoBairroCliente.prop('disabled', false);
        enderecoNumeroCliente.prop('disabled', false);
        idCliente.val('');
        nomeCliente.val('');
        telefoneCliente.val('');
        enderecoEstadoCliente.val('');
        enderecoCidadeCliente.val('');
        enderecoRuaCliente.val('');
        enderecoBairroCliente.val('');
        enderecoNumeroCliente.val('');
    }

    function disableClientFields(){
        nomeCliente.prop('disabled', true);
        telefoneCliente.prop('disabled', true);
        enderecoEstadoCliente.prop('disabled', true);
        enderecoCidadeCliente.prop('disabled', true);
        enderecoRuaCliente.prop('disabled', true);
        enderecoBairroCliente.prop('disabled', true);
        enderecoNumeroCliente.prop('disabled', true);
    }

    function enableProductFields(){
        descricaoProduto.prop('disabled', false);
        tensaoProduto.prop('disabled', false);
        garantiaProduto.prop('disabled', false);
        idProduto.val('');
        descricaoProduto.val('');
        tensaoProduto.val('');
        garantiaProduto.val('');
    }

    function disableProductFields(){
        descricaoProduto.prop('disabled', true);
        tensaoProduto.prop('disabled', true);
        garantiaProduto.prop('disabled', true);
    }

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

    function getItens(callback){

        $.ajax({
            method: "POST",
            url: "/item/getItens",
            dataType: "json",
            success: function (response) {
                callback(response)
            }
        });

    }

    function getOrdemServico(id, callback){

        $.ajax({
            method: "POST",
            url: "/ordemServico/getOrdemServico",
            data: 'id_ordem_servico=' + id,
            dataType: "json",
            success: function (response) {
                callback(response)
            }
        });
    }

    function deleteOrdemServico(id, callback){

        $.ajax({
            method: "POST",
            url: "/ordemServico/delete",
            data: 'id_ordem_servico=' + id,
            success: function (response) {
                callback(response);
            }
        });
    }

    function checkGarantia(input){

        let dataCorrente = new Date();
        let garantia = garantiaProduto.val();
        let data = new Date(input.val());
        let dataGarantia = new Date(data.setMonth(data.getMonth()+parseInt(garantia)));
        dataGarantia.setDate(dataGarantia.getDate() + 1);

        let status = 'Não';

        if(dataGarantia.getFullYear() > dataCorrente.getFullYear()){
            status = 'Sim';
        }

        if(dataGarantia.getFullYear() === dataCorrente.getFullYear()){
            if(dataGarantia.getMonth() > dataCorrente.getMonth()){
                status = 'Sim';
            }else if(dataGarantia.getMonth() === dataCorrente.getMonth()){
                if(dataGarantia.getDate() >= dataCorrente.getDate()){
                    status = 'Sim';
                }
            }
        }

        statusGarantia.val(status);
    }

    getEstados(function(response){
        $.each(response, function (key, estado) {
            selectEstado.append($('<option></option>').attr('value', estado.sigla).text(estado.sigla).data('id', estado.id));
        });
    });

    getItens(function(response){
       itens = response;
    });

    /***************************** AÇÕES *****************************/

    cpfCnpjCliente.on('focusout', function(){

        let cpfCnpj = $(this).val();

        if(cpfCnpj === ''){
            disableClientFields();
        }else{
            $.ajax({
                method: "POST",
                url: "/cliente/getCliente",
                data: 'cpfcnpj_cliente=' + cpfCnpj,
                dataType: "json",
                success: function (response) {

                    if(!response){
                        enableClientFields();
                        nomeCliente.focus();
                    }else{

                        disableClientFields();
                        idCliente.val(response.id_cliente);
                        nomeCliente.val(response.nome_cliente);
                        telefoneCliente.val(response.telefone_cliente);
                        enderecoEstadoCliente.val(response.endereco_estado);
                        enderecoCidadeCliente.val(response.endereco_cidade);
                        enderecoRuaCliente.val(response.endereco_rua);
                        enderecoBairroCliente.val(response.endereco_bairro);
                        enderecoNumeroCliente.val(response.endereco_numero);

                        selectCidade.find('option').remove().end();
                        selectCidade.append($('<option></option>').attr('value', response.endereco_cidade).text(response.endereco_cidade));
                    }
                }
            });
        }
    });

    referenciaProduto.on('focusout', function(){

        let referencia = $(this).val();

        if(referencia === ''){
            disableProductFields();
        }else{
            $.ajax({
                method: "POST",
                url: "/produto/getProduto",
                data: 'referencia_produto=' + referencia,
                dataType: "json",
                success: function (response) {

                    if(!response){
                        enableProductFields();
                        descricaoProduto.focus();
                    }else{
                        disableProductFields();
                        idProduto.val(response.id_produto);
                        descricaoProduto.val(response.descricao_produto);
                        tensaoProduto.val(response.tensao_produto);
                        garantiaProduto.val(response.garantia_produto);
                    }
                }
            });
        }
    });

    dataCompra.on('focusout', function(){
        checkGarantia($(this));
    });

    garantiaProduto.on('focusout', function(){
       // checkGarantia();
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

                    enderecoCidadeCliente.prop('disabled', true);

                    if(cidade !== ''){
                        selectCidade.val(cidade);
                        cidade = '';
                    }else{
                        selectCidade.prop("disabled", false);
                    }
                }
            });
        }
    });

    btnUpdateOrdemServico.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        getOrdemServico(id, function(response){

            modalUpdate.modal('show');

            idOrdemServico.val(response.id_ordem_servico);
            idProduto.val(response.id_produto);
            descricaoProduto.val(response.descricao_produto);
            tensaoProduto.val(response.tensao_produto);
            garantiaProduto.val(response.garantia_produto);
            referenciaProduto.val(response.referencia_produto);
            idCliente.val(response.id_cliente);
            nomeCliente.val(response.nome_cliente);
            telefoneCliente.val(response.telefone_cliente);
            cpfCnpjCliente.val(response.cpfcnpj_cliente);
            enderecoEstadoCliente.val(response.endereco_estado);
            enderecoRuaCliente.val(response.endereco_rua);
            enderecoNumeroCliente.val(response.endereco_numero);
            enderecoBairroCliente.val(response.endereco_bairro);
            notaFiscal.val(response.nota_fiscal);
            dataCompra.val(response.data_compra);
            dataEmissao.val(response.data_emissao);
            statusGarantia.val(response.status_garantia);
            selectCidade.find('option').remove().end();
            selectCidade.append($('<option></option>').attr('value', response.endereco_cidade).text(response.endereco_cidade));
        });
    });

    btnDeleteOrdemServico.click(function () {

        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        modalDelete.modal('show');
        $(".btn-delete").on("click", function (ev) {

            ev.preventDefault();

            deleteOrdemServico(id, function(response){
                location.reload();
            });
        });
    });

    btnDeleteOrdensServico.click(function () {

        let checkbox = $('table tbody input[type="checkbox"]');
        let ordens = [];

        checkbox.each(function () {
            if (this.checked) {
                ordens.push($(this).val());
            }
        });

        if (ordens.length === 0) {
            alert('Nenhum registro selecionado.');
        } else {

            modalDelete.modal('show');
            $(".btn-delete").on("click", function (ev) {

                ev.preventDefault();

                deleteOrdemServico(ordens, function(response){
                    location.reload();
                });
            });
        }
    });


    btnAddNovoItem.click(function(){

        let row = $('<div class="row">');
        let count = countRows+1;
        countRows += 1;

        /* Campo descrição */
        let col = $('<div class="col-md-9 col-xs-9 no-padding">');
        let group = $('<div class="form-group">');
        let label = $('<label for="'+ "itemOrdemServico" + count + '">Descrição</label>');
        let select = $('<select class="form-control" name="'+ "itemOrdemServico" + count + '">');

        select.append($('<option></option>'));
        $.each(itens, function (key, item) {
            select.append($('<option></option>').attr('value', item.id_item).text(item.descricao_item));
        });

        row.append(col);
        col.append(group);
        group.append(label);
        group.append(select);

        /* Campo quantidade */
        let group2 = $('<div class="form-group">');
        let col2 = $('<div class="col-md-2 col-xs-2 no-padding">');
        let label2 = $('<label for="'+ "quantidade" + count + '">Quantidade</label>');
        let input = $('<input type="number" class="form-control" name="'+ "quantidade" + count + '">');

        row.append(col2);
        col2.append(group2);
        group2.append(label2);
        group2.append(input);


        /* Icone remover */
        let col3 = $('<div class="col-md-1 col-xs-1 no-padding">');
        let group3 = $('<div class="form-group">');
        let link = $('<a class="del-novo-item" href="#">');
        let icon = $('<i class="fas fa-minus remove-item" data-toggle="tooltip" title="Remover item">');

        link.click(function(){
           $(this).parent().parent().parent().remove();
        });

        row.append(col3);
        col3.append(group3);
        group3.append(link);
        link.append(icon);

        modalFinish.find('.modal-body').append(row);

    });

    btnFinalizarOrdemServico.click(function(){
        /* Recupera o id da linha <tr> */
        let id = $(this).parent().parent().data("id");

        modalFinish.modal('show');
        modalFinish.find('#idOrdemServico').val(id);
    });

    init();

    function init(){

        $('.valor-excedente').maskMoney();

        selectFiltro.change(function(){
            window.location.replace('/ordemServico/index/' + $(this).val());
        });

        let url = window.location.href.split('/');
        let id = url.slice(-1)[0];

        if(!isNaN(id) ){
            selectFiltro.val(id);
        }
    }

});