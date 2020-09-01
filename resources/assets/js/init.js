$(document).ready(function (){

    try{
        inicializarSideNav()
        inicializarParallax()
    }catch (e) {
    }

    try {
        inicializarSelectsEDropdown();
    } catch (e) {

    }


    try{
        inicializarCollapsible()
    }catch (e) {

    }

    try {
        inicializarTabs();
    }catch (e) {

    }

    try {
        inicializarDatePickers();
        inicializarTimePickers();
    } catch (e) {

    }


    try {
        inicializarTextAreaCharacterCounter();
    } catch (e) {

    }

    /*
    try {
        incializarModals();
    } catch (e) {

    }
     */

    try {
        inicializarTooltip();
    } catch (e) {

    }

    try {
        inicializarSlider();
    } catch (e) {

    }

    (function () {
        const Contrast = {
            storage: 'contrastState',
            cssClass: 'contrast',
            currentFontSize: null,
            check: checkContrast,
            getState: getContrastState,
            setState: setContrastState,
            toggle: toggleContrast,
            updateView: updateViewContrast
        };

        window.toggleContrast = function () { Contrast.toggle(); };

        Contrast.check();

        function checkContrast() {
            this.updateView();
        }

        function getContrastState() {
            return localStorage.getItem(this.storage) === 'true';
        }

        function setContrastState(state) {
            localStorage.setItem(this.storage, '' + state);
            this.currentState = state;
            this.updateView();
        }

        function updateViewContrast() {
            let body = document.body;

            if (this.currentState === null || typeof this.currentState === 'undefined')
                this.currentState = this.getState();

            if (this.currentState)
                body.classList.add(this.cssClass);
            else
                body.classList.remove(this.cssClass);
        }

        function toggleContrast() {
            this.setState(!this.currentState);
        }

    })();


}); // end of document ready

function inicializarRange(){
    var elems  = document.querySelectorAll("input[type=range]");
    M.Range.init(elems);
}

function inicializarSideNav() {
    $('.sidenav').sidenav();
}

function inicializarParallax() {
    $('.parallax').parallax()
}

function inicializarTabs() {
    $('.tabs').tabs();
}

function inicializarCollapsible() {
    $('.collapsible').collapsible();
}

function incializarModals() {
    $('.modal').modal();
}

function inicializarDropdown() {
    $(".dropdown-trigger").dropdown({
        coverTrigger: false,
        inDuration: 300,
        outDuration: 225,
        constrain_width: false, // Does not change width of dropdown to that of the activator
        gutter: ($('.dropdown-content').width()*3)/2.5 + 5, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'left' // Displays dropdown with edge aligned to the left of button
    });
}

function inicializarTooltip() {
    $('.tooltipped').tooltip();
}

function inicializarSelectsEDropdown() {
    try{
        inicializarDropdown()
    }catch (e) {

    }

    try{
        $('select').formSelect();
    }catch (e) {

    }


}

function inicializarTimePickers() {
    var traducaoDosTermosDoTimePicker = {
        cancel: 'Cancelar',
        clear: 'Limpar',
        done: 'Ok'
    }

    $('.timepicker').timepicker({
        i18n: traducaoDosTermosDoTimePicker,
        showClearBtn: true,
        twelveHour : false, // 12 horas, usa AM/PM
        autoclose: false  //Fecha o timepicker automaticamente apos selecionar a hora
    })

}

function inicializarDatePickers() {
    var traducaoDosTermosDoDatePicker = {
        cancel: 'Cancelar',
        clear: 'Limpar',
        done: 'Ok',
        previousMonth: '‹',
        nextMonth: '›',
        months: [
            'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ],
        monthsShort: [
            'Jan',
            'Fev',
            'Mar',
            'Abr',
            'Mai',
            'Jun',
            'Jul',
            'Ago',
            'Set',
            'Out',
            'Nov',
            'Dez'
        ],

        weekdays: [
            'Domingo',
            'Segunda',
            'Terça',
            'Quarta',
            'Quinta',
            'Sexta',
            'Sábado'
        ],

        weekdaysShort: [
            'Dom',
            'Seg',
            'Ter',
            'Qua',
            'Qui',
            'Sex',
            'Sáb'
        ],

        weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S']

    };

    $(".datepicker[tipo*='periodo_antigo']").datepicker({
        i18n: traducaoDosTermosDoDatePicker,
        yearRange: [(new Date().getFullYear()) - 100, (new Date().getFullYear()) - 16],
        format: 'dd/mm/yyyy',
        maxDate: new Date(),
        showClearBtn: true,
        showMonthAfterYear: true,
    })

    $(".datepicker[tipo*='periodo_atual']").datepicker({
        i18n: traducaoDosTermosDoDatePicker,
        yearRange: [(new Date().getFullYear()), (new Date().getFullYear() + 10)],
        format: 'dd/mm/yyyy',
        minDate: new Date(),
        showClearBtn: true,
        showMonthAfterYear: true,
    })

}


function inicializarTextAreaCharacterCounter() {
    $('.materialize-textarea').characterCounter();
}

function inicializarSlider() {
    const slider = document.querySelector('.slider');
    M.Slider.init(slider, {
        indicators: false,
        height: 550,
        transition: 500,
        interval: 6100
    });
}


