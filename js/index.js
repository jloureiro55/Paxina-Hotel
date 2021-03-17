window.onload = function () {
    if (document.getElementById('barra-progreso') != null) {
        document.getElementById('barra-progreso').addEventListener('mouseup', (msg) => {
                var valor_actual = document.getElementById('barra-progreso').value;
                var parrafo = document.getElementById('presentacion');
                switch (valor_actual) {
                    case '0':
                        parrafo.style = '';
                        break;
                    case '50':
                        parrafo.style = 'font-size: 2vw !important';
                        break;
                    case '100':
                        parrafo.style = 'font-size: 2.5vw !important';
                        break;
                    default:
                        console.log('Valor no contemplado');
                        break;
                }
            });
    }
};

$(document).ready(function () {

    function callback() {
        setTimeout(function () {
            $("#effect").removeAttr("style").hide().fadeIn();
        }, 1000);
    };

    document.getElementById('effect').addEventListener('click', e => {
        e.preventDefault();
        // Efecto Jquery UI
        $("#effect").effect('bounce', {}, 500, callback);
    });

});