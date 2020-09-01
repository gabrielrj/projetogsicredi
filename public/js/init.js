/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/init.js":
/*!*************************************!*\
  !*** ./resources/assets/js/init.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(document).ready(function () {
  try {
    inicializarSideNav();
    inicializarParallax();
  } catch (e) {}

  try {
    inicializarSelectsEDropdown();
  } catch (e) {}

  try {
    inicializarCollapsible();
  } catch (e) {}

  try {
    inicializarTabs();
  } catch (e) {}

  try {
    inicializarDatePickers();
    inicializarTimePickers();
  } catch (e) {}

  try {
    inicializarTextAreaCharacterCounter();
  } catch (e) {}
  /*
  try {
      incializarModals();
  } catch (e) {
   }
   */


  try {
    inicializarTooltip();
  } catch (e) {}

  try {
    inicializarSlider();
  } catch (e) {}

  (function () {
    var Contrast = {
      storage: 'contrastState',
      cssClass: 'contrast',
      currentFontSize: null,
      check: checkContrast,
      getState: getContrastState,
      setState: setContrastState,
      toggle: toggleContrast,
      updateView: updateViewContrast
    };

    window.toggleContrast = function () {
      Contrast.toggle();
    };

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
      var body = document.body;
      if (this.currentState === null || typeof this.currentState === 'undefined') this.currentState = this.getState();
      if (this.currentState) body.classList.add(this.cssClass);else body.classList.remove(this.cssClass);
    }

    function toggleContrast() {
      this.setState(!this.currentState);
    }
  })();
}); // end of document ready

function inicializarRange() {
  var elems = document.querySelectorAll("input[type=range]");
  M.Range.init(elems);
}

function inicializarSideNav() {
  $('.sidenav').sidenav();
}

function inicializarParallax() {
  $('.parallax').parallax();
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
    constrain_width: false,
    // Does not change width of dropdown to that of the activator
    gutter: $('.dropdown-content').width() * 3 / 2.5 + 5,
    // Spacing from edge
    belowOrigin: false,
    // Displays dropdown below the button
    alignment: 'left' // Displays dropdown with edge aligned to the left of button

  });
}

function inicializarTooltip() {
  $('.tooltipped').tooltip();
}

function inicializarSelectsEDropdown() {
  try {
    inicializarDropdown();
  } catch (e) {}

  try {
    $('select').formSelect();
  } catch (e) {}
}

function inicializarTimePickers() {
  var traducaoDosTermosDoTimePicker = {
    cancel: 'Cancelar',
    clear: 'Limpar',
    done: 'Ok'
  };
  $('.timepicker').timepicker({
    i18n: traducaoDosTermosDoTimePicker,
    showClearBtn: true,
    twelveHour: false,
    // 12 horas, usa AM/PM
    autoclose: false //Fecha o timepicker automaticamente apos selecionar a hora

  });
}

function inicializarDatePickers() {
  var traducaoDosTermosDoDatePicker = {
    cancel: 'Cancelar',
    clear: 'Limpar',
    done: 'Ok',
    previousMonth: '‹',
    nextMonth: '›',
    months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
    monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
    weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
    weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
    weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S']
  };
  $(".datepicker[tipo*='periodo_antigo']").datepicker({
    i18n: traducaoDosTermosDoDatePicker,
    yearRange: [new Date().getFullYear() - 100, new Date().getFullYear() - 16],
    format: 'dd/mm/yyyy',
    maxDate: new Date(),
    showClearBtn: true,
    showMonthAfterYear: true
  });
  $(".datepicker[tipo*='periodo_atual']").datepicker({
    i18n: traducaoDosTermosDoDatePicker,
    yearRange: [new Date().getFullYear(), new Date().getFullYear() + 10],
    format: 'dd/mm/yyyy',
    minDate: new Date(),
    showClearBtn: true,
    showMonthAfterYear: true
  });
}

function inicializarTextAreaCharacterCounter() {
  $('.materialize-textarea').characterCounter();
}

function inicializarSlider() {
  var slider = document.querySelector('.slider');
  M.Slider.init(slider, {
    indicators: false,
    height: 550,
    transition: 500,
    interval: 6100
  });
}

/***/ }),

/***/ 1:
/*!*******************************************!*\
  !*** multi ./resources/assets/js/init.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\gsi_credi\resources\assets\js\init.js */"./resources/assets/js/init.js");


/***/ })

/******/ });