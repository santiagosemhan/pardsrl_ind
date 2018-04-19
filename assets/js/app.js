// CSS
require('bootstrap/dist/css/bootstrap.min.css');
require('admin-lte/dist/css/AdminLTE.min.css');
require('admin-lte/dist/css/skins/_all-skins.min.css');
require('font-awesome/css/font-awesome.min.css');
require('ionicons/dist/css/ionicons.min.css');
require('select2/dist/css/select2.min.css');
require('bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css');
require('datatables-bootstrap/css/dataTables.bootstrap.min.css');
require('pace-js/themes/green/pace-theme-minimal.css');
require('noty/lib/noty.css');
require('noty/lib/themes/sunset.css');
require('../../web/dashboard/Source-Sans-Pro/css/fonts.css');
require('../../web/bundles/app/app.css');

// assets
require('admin-lte/dist/img/avatar5.png');
require('admin-lte/dist/img/avatar2.png');

// JavaScript
global.$ = global.jQuery = $;
// Moment Js
global.moment = require('moment-timezone');
// Se carga socket.io en todo el site, debido a su uso principalmente en las notificaciones
global.io = require('socket.io-client');

global.Noty = require('noty');

require('bootstrap');
// bootstrap timepicker
require('bootstrap-datepicker');
require('bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min');

// datatables
require('datatables-bootstrap');

require('select2');

// AdminLTE App
require('admin-lte/dist/js/adminlte.min');
require('pace-js/pace.min.js');


//Codigo ejecutado al Inicio, común a todas las ventanas.
$(document).ready(function () {

    /**
     * Funciones relacionadas con los modales
     */
    $('.delete-obj').click(function(){
        $('#modal-delete-obj').modal('show',this);
        return false;
    });

    $('#modal-delete-obj').on('show.bs.modal', function(e) {

        $(this).find('.btn-confirm').click(function(){

            var objId = $(e.relatedTarget).data('id');

            var actionString = $('.delete-obj-form form').attr('action');

            actionString = actionString.replace(/__obj_id__/g, objId);

            $('.delete-obj-form form').attr('action',actionString);

            $('.delete-obj-form form').submit();
        });

    });

    /**
     *  Se inicializan todos los datepickers.
     */
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'es',
        autoclose: true
    });

    $(".data-table").DataTable({
        "paging": false,
        "autoWidth": true,
        "info": false,
        "scrollX": true,
        "order": [],
        "language": {
            "search": "Buscar:",
            "zeroRecords": "Sin resultados"
        }
    });

    $('.select2').select2();

    $(document).ajaxStart(function() { Pace.restart(); });

});

/**
 * Funcion que deja en estado cargando el box especificado.
 * @param boxId
 */
global.overlayBox = function (boxId){
   $(boxId).append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
};

global.removeOverlayBox = function (boxId){
    $(boxId).find('.overlay').remove();
};

global.notificate = function (type,text){
    new Noty({
        text: text,
        type: type,
        layout: 'bottomRight',
        theme: 'sunset',
        progressBar: true,
        closeWith: ['click', 'button'],
        animation: {
          open: 'noty_effects_open',
          close: 'noty_effects_close'
        },
        timeout: 3000,
        id: false,
        force: false,
        killer: false,
        queue: 'global',
        container: false,
        buttons: [],
        sounds: {
          sources: [],
          volume: 1,
          conditions: []
        },
        titleCount: {
          conditions: []
        },
        modal: false
    }).show();
};
