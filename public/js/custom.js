$(document).ready(function(){

    // Select/Deselect checkboxes
    var checkbox = $('table tbody input[type="checkbox"]');
    $("#selectAll").click(function(){
        if(this.checked){
            checkbox.each(function(){
                this.checked = true;
            });
        } else{
            checkbox.each(function(){
                this.checked = false;
            });
        }
    });

    checkbox.click(function(){
        if(!this.checked){
            $("#selectAll").prop("checked", false);
        }
    });

    $(".celular").mask('(00) 00000-0000');
    $(".cnpj").mask("99.999.999/9999-99");
    $('.modal').on('hidden.bs.modal', function () {
        $("form").trigger('reset');
        $('.select-cidade').find('option').remove().end();
    });
});