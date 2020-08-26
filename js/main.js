$(document).ready(function() {
    $('.button-collapse').sideNav();
    $('.slider').slider();
    $('.parallax').parallax();
    $('select').material_select();
    $('ul.tabs').tabs();
    $('#fecha_salida').pickadate({
        format: 'yyyy-mm-dd',
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year
        today: 'Today',
        close: 'Ok',
        closeOnSelect: false
    });
    $('#fecha_entrada').pickadate({
        format: 'yyyy-mm-dd',
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year
        today: 'Today',
        close: 'Ok',
        closeOnSelect: false
    });
    $('#fecha_inventario_inicial').pickadate({
        format: 'yyyy-mm-dd',
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year
        today: 'Today',
        close: 'Ok',
        closeOnSelect: false
    });
    $('#fecha_filtro').pickadate({
        format: 'yyyy-mm',
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15, // Creates a dropdown of 15 years to control year
        today: 'Today',
        close: 'Ok',
        closeOnSelect: false
    });
});