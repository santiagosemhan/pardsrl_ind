var Highcharts = require('highcharts/highstock');

require('highcharts/modules/exporting')(Highcharts);
require('highcharts/modules/offline-exporting')(Highcharts);
require('highcharts/highcharts-more')(Highcharts);
require('highcharts/highcharts-3d')(Highcharts);
require('highcharts/modules/solid-gauge')(Highcharts);

Highcharts.setOptions({
    global: {
        useUTC: false
    },
    lang: {
        loading: 'Cargando...',
        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        weekdays: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'],
        shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        exportButtonTitle: "Exportar",
        printButtonTitle: "Importar",
        rangeSelectorFrom: "De",
        rangeSelectorTo: "A",
        rangeSelectorZoom: "Periodo",
        downloadPNG: 'Descargar gráfica PNG',
        downloadJPEG: 'Descargar gráfica JPEG',
        downloadPDF: 'Descargar gráfica PDF',
        downloadSVG: 'Descargar gráfica SVG',
        printChart: 'Imprimir Gráfica',
        thousandsSep: ",",
        decimalPoint: '.'
    }
});

global.Highcharts = Highcharts;
