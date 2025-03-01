/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/checkout-add-attendees-form.js":
/*!***********************************************!*\
  !*** ./src/js/checkout-add-attendees-form.js ***!
  \***********************************************/
/***/ (function() {

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
var _this = this;
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return exports; }; var exports = {}, Op = Object.prototype, hasOwn = Op.hasOwnProperty, defineProperty = Object.defineProperty || function (obj, key, desc) { obj[key] = desc.value; }, $Symbol = "function" == typeof Symbol ? Symbol : {}, iteratorSymbol = $Symbol.iterator || "@@iterator", asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator", toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag"; function define(obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: !0, configurable: !0, writable: !0 }), obj[key]; } try { define({}, ""); } catch (err) { define = function define(obj, key, value) { return obj[key] = value; }; } function wrap(innerFn, outerFn, self, tryLocsList) { var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator, generator = Object.create(protoGenerator.prototype), context = new Context(tryLocsList || []); return defineProperty(generator, "_invoke", { value: makeInvokeMethod(innerFn, self, context) }), generator; } function tryCatch(fn, obj, arg) { try { return { type: "normal", arg: fn.call(obj, arg) }; } catch (err) { return { type: "throw", arg: err }; } } exports.wrap = wrap; var ContinueSentinel = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var IteratorPrototype = {}; define(IteratorPrototype, iteratorSymbol, function () { return this; }); var getProto = Object.getPrototypeOf, NativeIteratorPrototype = getProto && getProto(getProto(values([]))); NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype); var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype); function defineIteratorMethods(prototype) { ["next", "throw", "return"].forEach(function (method) { define(prototype, method, function (arg) { return this._invoke(method, arg); }); }); } function AsyncIterator(generator, PromiseImpl) { function invoke(method, arg, resolve, reject) { var record = tryCatch(generator[method], generator, arg); if ("throw" !== record.type) { var result = record.arg, value = result.value; return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) { invoke("next", value, resolve, reject); }, function (err) { invoke("throw", err, resolve, reject); }) : PromiseImpl.resolve(value).then(function (unwrapped) { result.value = unwrapped, resolve(result); }, function (error) { return invoke("throw", error, resolve, reject); }); } reject(record.arg); } var previousPromise; defineProperty(this, "_invoke", { value: function value(method, arg) { function callInvokeWithMethodAndArg() { return new PromiseImpl(function (resolve, reject) { invoke(method, arg, resolve, reject); }); } return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(innerFn, self, context) { var state = "suspendedStart"; return function (method, arg) { if ("executing" === state) throw new Error("Generator is already running"); if ("completed" === state) { if ("throw" === method) throw arg; return doneResult(); } for (context.method = method, context.arg = arg;;) { var delegate = context.delegate; if (delegate) { var delegateResult = maybeInvokeDelegate(delegate, context); if (delegateResult) { if (delegateResult === ContinueSentinel) continue; return delegateResult; } } if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) { if ("suspendedStart" === state) throw state = "completed", context.arg; context.dispatchException(context.arg); } else "return" === context.method && context.abrupt("return", context.arg); state = "executing"; var record = tryCatch(innerFn, self, context); if ("normal" === record.type) { if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue; return { value: record.arg, done: context.done }; } "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg); } }; } function maybeInvokeDelegate(delegate, context) { var methodName = context.method, method = delegate.iterator[methodName]; if (undefined === method) return context.delegate = null, "throw" === methodName && delegate.iterator["return"] && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method) || "return" !== methodName && (context.method = "throw", context.arg = new TypeError("The iterator does not provide a '" + methodName + "' method")), ContinueSentinel; var record = tryCatch(method, delegate.iterator, context.arg); if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel; var info = record.arg; return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel); } function pushTryEntry(locs) { var entry = { tryLoc: locs[0] }; 1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry); } function resetTryEntry(entry) { var record = entry.completion || {}; record.type = "normal", delete record.arg, entry.completion = record; } function Context(tryLocsList) { this.tryEntries = [{ tryLoc: "root" }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0); } function values(iterable) { if (iterable) { var iteratorMethod = iterable[iteratorSymbol]; if (iteratorMethod) return iteratorMethod.call(iterable); if ("function" == typeof iterable.next) return iterable; if (!isNaN(iterable.length)) { var i = -1, next = function next() { for (; ++i < iterable.length;) if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next; return next.value = undefined, next.done = !0, next; }; return next.next = next; } } return { next: doneResult }; } function doneResult() { return { value: undefined, done: !0 }; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, defineProperty(Gp, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), defineProperty(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) { var ctor = "function" == typeof genFun && genFun.constructor; return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name)); }, exports.mark = function (genFun) { return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun; }, exports.awrap = function (arg) { return { __await: arg }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () { return this; }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) { void 0 === PromiseImpl && (PromiseImpl = Promise); var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl); return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) { return result.done ? result.value : iter.next(); }); }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () { return this; }), define(Gp, "toString", function () { return "[object Generator]"; }), exports.keys = function (val) { var object = Object(val), keys = []; for (var key in object) keys.push(key); return keys.reverse(), function next() { for (; keys.length;) { var key = keys.pop(); if (key in object) return next.value = key, next.done = !1, next; } return next.done = !0, next; }; }, exports.values = values, Context.prototype = { constructor: Context, reset: function reset(skipTempReset) { if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined); }, stop: function stop() { this.done = !0; var rootRecord = this.tryEntries[0].completion; if ("throw" === rootRecord.type) throw rootRecord.arg; return this.rval; }, dispatchException: function dispatchException(exception) { if (this.done) throw exception; var context = this; function handle(loc, caught) { return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught; } for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i], record = entry.completion; if ("root" === entry.tryLoc) return handle("end"); if (entry.tryLoc <= this.prev) { var hasCatch = hasOwn.call(entry, "catchLoc"), hasFinally = hasOwn.call(entry, "finallyLoc"); if (hasCatch && hasFinally) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } else if (hasCatch) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); } else { if (!hasFinally) throw new Error("try statement without catch or finally"); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } } } }, abrupt: function abrupt(type, arg) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) { var finallyEntry = entry; break; } } finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null); var record = finallyEntry ? finallyEntry.completion : {}; return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record); }, complete: function complete(record, afterLoc) { if ("throw" === record.type) throw record.arg; return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel; }, finish: function finish(finallyLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel; } }, "catch": function _catch(tryLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc === tryLoc) { var record = entry.completion; if ("throw" === record.type) { var thrown = record.arg; resetTryEntry(entry); } return thrown; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(iterable, resultName, nextLoc) { return this.delegate = { iterator: values(iterable), resultName: resultName, nextLoc: nextLoc }, "next" === this.method && (this.arg = undefined), ContinueSentinel; } }, exports; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
/**
 * Add Attendees
 */

(function (w, $) {
  'use strict';

  var _PP_DATA = PP_DATA,
    ajax_url = _PP_DATA.ajax_url;
  var FORM_ID = '#ADD_ATTENDEES_FORM';
  var FormAddNewContact = null;
  var $trInprogress = null;
  function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
  }
  var findEmailSalesforceContacts = /*#__PURE__*/function () {
    var _ref = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee(email, event_id) {
      return _regeneratorRuntime().wrap(function _callee$(_context) {
        while (1) switch (_context.prev = _context.next) {
          case 0:
            _context.next = 2;
            return $.ajax({
              type: 'POST',
              url: ajax_url,
              data: {
                action: 'pp_ajax_find_contact_sf_by_email',
                email: email,
                event_id: event_id
              },
              error: function error(err) {
                console.log(err);
              }
            });
          case 2:
            return _context.abrupt("return", _context.sent);
          case 3:
          case "end":
            return _context.stop();
        }
      }, _callee);
    }));
    return function findEmailSalesforceContacts(_x, _x2) {
      return _ref.apply(this, arguments);
    };
  }();
  var updateSlotItem = function updateSlotItem($tr, contact) {
    var __Account_Data = contact.__Account_Data,
      Id = contact.Id,
      FirstName = contact.FirstName,
      LastName = contact.LastName,
      AccountId = contact.AccountId;
    $tr.find('input[name^="firstname"]').val(FirstName).prop('readonly', true);
    $tr.find('input[name^="lastname"]').val(LastName).prop('readonly', true);
    $tr.find('input[name^="organisation"]').val(AccountId);
    $tr.find('input[name^="contact_id"]').val(Id);
    $tr.find('.organisation-text').text(__Account_Data.Name);
  };
  var resetSlotItem = function resetSlotItem($tr) {
    var without = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
    var organisationIdDefault = $tr.find('input[name^="organisation"]').data('default-value');
    var organisationTextDefault = $tr.find('.organisation-text').data('default-text');
    $tr.find('input[name^="firstname"]').val('').prop('readonly', false);
    $tr.find('input[name^="lastname"]').val('').prop('readonly', false);
    $tr.find('input[name^="organisation"]').val(organisationIdDefault);
    $tr.find('input[name^="contact_id"]').val('');
    $tr.find('input[name^="relation_id"]').val('');
    $tr.find('input[name^="relation_id_child"]').val('');
    $tr.find('.organisation-text').text(organisationTextDefault);
  };
  var setStatus = function setStatus($tr, status) {
    $tr.find('.__status-icon svg path').css('fill', status ? '#8BC34A' : '#9e9e9e');
  };
  var checkDuplicateEmail = function checkDuplicateEmail(email, $container) {
    var loop = 0;
    $container.find('input[name^="email"]').each(function () {
      var value = this.value.toLowerCase().trim();
      loop = value == email.toLowerCase().trim() ? loop += 1 : loop;
    });
    return loop >= 2 ? true : false;
  };
  var errorMessageUI = function errorMessageUI($td, message, status) {
    if (status == true) {
      $td.addClass('show-error-message');
      $td.find('.error-message').text(message);
    } else {
      $td.removeClass('show-error-message');
      $td.find('.error-message').text(message);
    }
  };
  var onEmailUpdate = function onEmailUpdate() {
    // setTimeout(() => {
    //   console.log($(`${ FORM_ID } input[name^="email"]`).length);
    // }, 1000)

    $('body').on('change', "".concat(FORM_ID, " input[name^=\"email\"]"), /*#__PURE__*/function () {
      var _ref2 = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee2(e) {
        var email, sfEventID, isEmail, $table, $tr, $td, dup, _yield$findEmailSales, contact, joined;
        return _regeneratorRuntime().wrap(function _callee2$(_context2) {
          while (1) switch (_context2.prev = _context2.next) {
            case 0:
              email = e.target.value;
              sfEventID = $(this).data('event-parent-id');
              isEmail = validateEmail(email);
              $table = $(this).closest('table');
              $tr = $(this).closest('tr');
              $td = $(this).closest('td');
              $tr.removeClass('__invalid__');
              if (!(isEmail != true)) {
                _context2.next = 11;
                break;
              }
              resetSlotItem($tr);
              setStatus($tr, false);
              return _context2.abrupt("return");
            case 11:
              dup = checkDuplicateEmail(email, $table);
              if (!(dup == true)) {
                _context2.next = 17;
                break;
              }
              // Email attendees duplicate!
              errorMessageUI($td, '⚠️ Email attendees duplicate!', true);
              resetSlotItem($tr);
              setStatus($tr, false);
              return _context2.abrupt("return");
            case 17:
              errorMessageUI($td, '', false);
              $tr.addClass('__loading');
              _context2.next = 21;
              return findEmailSalesforceContacts(email, sfEventID);
            case 21:
              _yield$findEmailSales = _context2.sent;
              contact = _yield$findEmailSales.contact;
              joined = _yield$findEmailSales.joined;
              $tr.removeClass('__loading');
              if (!(joined == true)) {
                _context2.next = 30;
                break;
              }
              errorMessageUI($td, '⚠️ Email has already been registered for this event!', true);
              resetSlotItem($tr);
              setStatus($tr, false);
              return _context2.abrupt("return");
            case 30:
              if (contact) {
                updateSlotItem($tr, contact);
                setStatus($tr, true);
              } else {
                FormAddNewContact.updateFields({
                  c_email: $tr.find('input[name^="email"]').val(),
                  f_name: $tr.find('input[name^="firstname"]').val(),
                  l_name: $tr.find('input[name^="lastname"]').val()
                });
                FormAddNewContact.show();
                $trInprogress = $tr;
                resetSlotItem($tr);
                setStatus($tr, false);
              }
            case 31:
            case "end":
              return _context2.stop();
          }
        }, _callee2, this);
      }));
      return function (_x3) {
        return _ref2.apply(this, arguments);
      };
    }());
  };
  var popupAddNewContact = function popupAddNewContact(_ref3) {
    var onSubmit = _ref3.onSubmit,
      onClose = _ref3.onClose,
      onOpen = _ref3.onOpen;
    var self = _this;
    var $popup = $('.pp-popup-add-new-contact');
    var $form = $popup.find('form.add-new-contact-form');
    $form.on('submit', function (e) {
      onSubmit ? onSubmit($form, e) : '';
    });
    $popup.on('click', '.btn-close', function (e) {
      e.preventDefault();
      $('body').removeClass('__show-pp-popup-add-new-contact');
      onClose ? onClose($popup) : '';
    });
    return {
      show: function show() {
        $('body').addClass('__show-pp-popup-add-new-contact');
        onOpen ? onOpen($popup) : '';
      },
      hide: function hide() {
        $('body').removeClass('__show-pp-popup-add-new-contact');
        onClose ? onClose($popup) : '';
      },
      updateFields: function updateFields(fields) {
        Object.keys(fields).forEach(function (field) {
          $form.find("input[name=".concat(field, "]")).val(fields[field]);
        });
      }
    };
  };
  var addAttendeesFormValidates = function addAttendeesFormValidates() {
    var pass = true;
    return pass;
    $(FORM_ID).find('input[name^=contact_id]').each(function () {
      var input = $(this);
      var $tr = input.closest('tr');
      var value = input.val();
      if (value == '') {
        pass = false;
        $tr.addClass('__invalid__');
      }
    });
    return pass;
  };
  var addAttendeesFormSubmit = function addAttendeesFormSubmit() {
    $('body').on('submit', FORM_ID, /*#__PURE__*/function () {
      var _ref4 = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee3(e) {
        var $form, pass, data, _yield$$$ajax, success;
        return _regeneratorRuntime().wrap(function _callee3$(_context3) {
          while (1) switch (_context3.prev = _context3.next) {
            case 0:
              e.preventDefault();
              $form = $(this);
              pass = addAttendeesFormValidates();
              if (!(pass != true)) {
                _context3.next = 5;
                break;
              }
              return _context3.abrupt("return");
            case 5:
              console.log(pass, $form.serialize());
              $form.find('button[type="submit"]').css({
                opacity: .1,
                PointerEvent: 'none'
              });
              // let data = $form.serialize() + '&action=pp_ajax_save_attendees_in_cart';
              data = $form.serialize() + '&action=pp_ajax_save_attendees_to_order'; //pp_ajax_save_attendees_in_cart
              _context3.next = 10;
              return $.ajax({
                type: 'POST',
                url: ajax_url,
                data: data,
                error: function error(err) {
                  console.log(err);
                }
              });
            case 10:
              _yield$$$ajax = _context3.sent;
              success = _yield$$$ajax.success;
              if (success == true) {
                // stepUiController(2);
                // alert('Successfully.'); 
                w.location.reload();
              } else {
                alert('External Error: Please try again!');
              }
            case 13:
            case "end":
              return _context3.stop();
          }
        }, _callee3, this);
      }));
      return function (_x4) {
        return _ref4.apply(this, arguments);
      };
    }());
  };

  /**
   * 
   * @param {*} activeStep 1 || 2
   */
  var stepUiController = function stepUiController(activeStep) {
    switch (activeStep) {
      case 1:
        $('.step-checkout-bar .__step-add_attendees').addClass('__active').siblings().removeClass('__active');
        $('.add-attendees-container').show();
        $('form.checkout.woocommerce-checkout').hide();
        break;
      case 2:
        $('.step-checkout-bar .__step-checkout').addClass('__active').siblings().removeClass('__active');
        $('.add-attendees-container').hide();
        $('form.checkout.woocommerce-checkout').css('display', 'inline-flex');
        break;
    }
  };
  var removeSlot = function removeSlot() {
    $(document.body).on('attendees:remove_slot', /*#__PURE__*/function () {
      var _ref5 = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee4(e, order_id, EventRelation_Id, EventRelation_Id_Child, cb) {
        var res;
        return _regeneratorRuntime().wrap(function _callee4$(_context4) {
          while (1) switch (_context4.prev = _context4.next) {
            case 0:
              _context4.next = 2;
              return $.ajax({
                type: 'POST',
                url: ajax_url,
                data: {
                  action: 'pp_ajax_remove_slot_attendees',
                  oid: order_id,
                  rid: EventRelation_Id,
                  rid_child: EventRelation_Id_Child
                },
                error: function error(e) {
                  console.log(e);
                  alert('Internal Error: Please reload page and try again!');
                }
              });
            case 2:
              res = _context4.sent;
              cb(res);
            case 4:
            case "end":
              return _context4.stop();
          }
        }, _callee4);
      }));
      return function (_x5, _x6, _x7, _x8, _x9) {
        return _ref5.apply(this, arguments);
      };
    }());
    $('body').on('click', 'form#ADD_ATTENDEES_FORM .__remove-item', function (e) {
      e.preventDefault();
      var r = confirm('Are you sure you wish to remove this attendee?');
      if (!r) return;
      var std = $(this).find('.__std').text();
      var _this$dataset = this.dataset,
        rid = _this$dataset.rid,
        ridChild = _this$dataset.ridChild,
        orderId = _this$dataset.orderId;
      var $tr = $(this).closest('tr.__slot-item');
      console.log(rid, ridChild, orderId);
      $tr.addClass('__loading');
      $(document.body).trigger('attendees:remove_slot', [orderId, rid, ridChild, function (_ref6) {
        var success = _ref6.success;
        if (success == true) {
          resetSlotItem($tr);
          setStatus($tr, false);
          $tr.find('input[name^="email"]').val('');
          $tr.find('input[name^="email"]').removeAttr('readonly');
          $tr.find('.__slot-number').html(std);
          $tr.removeClass('__loading');
        }
      }]);
    });
  };
  var init = function init() {
    onEmailUpdate();
    FormAddNewContact = new popupAddNewContact({
      onSubmit: function () {
        var _onSubmit = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee5($form, event) {
          var fields, _yield$$$ajax2, success, responses, contact;
          return _regeneratorRuntime().wrap(function _callee5$(_context5) {
            while (1) switch (_context5.prev = _context5.next) {
              case 0:
                event.preventDefault();
                fields = {
                  "LastName": $form.find('input[name=l_name]').val(),
                  "FirstName": $form.find('input[name=f_name]').val(),
                  "Email": $form.find('input[name=c_email]').val()
                  // "AccountId": "0019q0000045pqRAAQ"
                };
                $form.addClass('pp-form-loading');
                _context5.next = 5;
                return $.ajax({
                  type: 'POST',
                  url: ajax_url,
                  data: {
                    action: 'pp_ajax_ppsf_add_new_contact',
                    fields: fields
                  },
                  error: function error(err) {
                    console.log(err);
                  }
                });
              case 5:
                _yield$$$ajax2 = _context5.sent;
                success = _yield$$$ajax2.success;
                responses = _yield$$$ajax2.responses;
                contact = _yield$$$ajax2.contact;
                $form.removeClass('pp-form-loading');
                if (!(success != true)) {
                  _context5.next = 14;
                  break;
                }
                alert('Internal Error: Please try again! \n' + JSON.stringify(responses));
                FormAddNewContact.hide();
                return _context5.abrupt("return");
              case 14:
                updateSlotItem($trInprogress, contact);
                setStatus($trInprogress, true);
                $trInprogress = null;
                FormAddNewContact.hide();
              case 18:
              case "end":
                return _context5.stop();
            }
          }, _callee5);
        }));
        function onSubmit(_x10, _x11) {
          return _onSubmit.apply(this, arguments);
        }
        return onSubmit;
      }(),
      onClose: function onClose() {
        if ($trInprogress == null) return;
        $trInprogress.find('input[name^=email]').val('');
        setStatus($trInprogress, false);
      }
    });
    removeSlot();
    addAttendeesFormSubmit();
    // stepUiController(2);
  };
  $(init);
  // $(w).on('load', init)
})(window, jQuery);

/***/ }),

/***/ "./src/js/salesforce/admin/pull-users-data.js":
/*!****************************************************!*\
  !*** ./src/js/salesforce/admin/pull-users-data.js ***!
  \****************************************************/
/***/ (() => {

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return exports; }; var exports = {}, Op = Object.prototype, hasOwn = Op.hasOwnProperty, defineProperty = Object.defineProperty || function (obj, key, desc) { obj[key] = desc.value; }, $Symbol = "function" == typeof Symbol ? Symbol : {}, iteratorSymbol = $Symbol.iterator || "@@iterator", asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator", toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag"; function define(obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: !0, configurable: !0, writable: !0 }), obj[key]; } try { define({}, ""); } catch (err) { define = function define(obj, key, value) { return obj[key] = value; }; } function wrap(innerFn, outerFn, self, tryLocsList) { var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator, generator = Object.create(protoGenerator.prototype), context = new Context(tryLocsList || []); return defineProperty(generator, "_invoke", { value: makeInvokeMethod(innerFn, self, context) }), generator; } function tryCatch(fn, obj, arg) { try { return { type: "normal", arg: fn.call(obj, arg) }; } catch (err) { return { type: "throw", arg: err }; } } exports.wrap = wrap; var ContinueSentinel = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var IteratorPrototype = {}; define(IteratorPrototype, iteratorSymbol, function () { return this; }); var getProto = Object.getPrototypeOf, NativeIteratorPrototype = getProto && getProto(getProto(values([]))); NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype); var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype); function defineIteratorMethods(prototype) { ["next", "throw", "return"].forEach(function (method) { define(prototype, method, function (arg) { return this._invoke(method, arg); }); }); } function AsyncIterator(generator, PromiseImpl) { function invoke(method, arg, resolve, reject) { var record = tryCatch(generator[method], generator, arg); if ("throw" !== record.type) { var result = record.arg, value = result.value; return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) { invoke("next", value, resolve, reject); }, function (err) { invoke("throw", err, resolve, reject); }) : PromiseImpl.resolve(value).then(function (unwrapped) { result.value = unwrapped, resolve(result); }, function (error) { return invoke("throw", error, resolve, reject); }); } reject(record.arg); } var previousPromise; defineProperty(this, "_invoke", { value: function value(method, arg) { function callInvokeWithMethodAndArg() { return new PromiseImpl(function (resolve, reject) { invoke(method, arg, resolve, reject); }); } return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(innerFn, self, context) { var state = "suspendedStart"; return function (method, arg) { if ("executing" === state) throw new Error("Generator is already running"); if ("completed" === state) { if ("throw" === method) throw arg; return doneResult(); } for (context.method = method, context.arg = arg;;) { var delegate = context.delegate; if (delegate) { var delegateResult = maybeInvokeDelegate(delegate, context); if (delegateResult) { if (delegateResult === ContinueSentinel) continue; return delegateResult; } } if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) { if ("suspendedStart" === state) throw state = "completed", context.arg; context.dispatchException(context.arg); } else "return" === context.method && context.abrupt("return", context.arg); state = "executing"; var record = tryCatch(innerFn, self, context); if ("normal" === record.type) { if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue; return { value: record.arg, done: context.done }; } "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg); } }; } function maybeInvokeDelegate(delegate, context) { var methodName = context.method, method = delegate.iterator[methodName]; if (undefined === method) return context.delegate = null, "throw" === methodName && delegate.iterator["return"] && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method) || "return" !== methodName && (context.method = "throw", context.arg = new TypeError("The iterator does not provide a '" + methodName + "' method")), ContinueSentinel; var record = tryCatch(method, delegate.iterator, context.arg); if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel; var info = record.arg; return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel); } function pushTryEntry(locs) { var entry = { tryLoc: locs[0] }; 1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry); } function resetTryEntry(entry) { var record = entry.completion || {}; record.type = "normal", delete record.arg, entry.completion = record; } function Context(tryLocsList) { this.tryEntries = [{ tryLoc: "root" }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0); } function values(iterable) { if (iterable) { var iteratorMethod = iterable[iteratorSymbol]; if (iteratorMethod) return iteratorMethod.call(iterable); if ("function" == typeof iterable.next) return iterable; if (!isNaN(iterable.length)) { var i = -1, next = function next() { for (; ++i < iterable.length;) if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next; return next.value = undefined, next.done = !0, next; }; return next.next = next; } } return { next: doneResult }; } function doneResult() { return { value: undefined, done: !0 }; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, defineProperty(Gp, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), defineProperty(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) { var ctor = "function" == typeof genFun && genFun.constructor; return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name)); }, exports.mark = function (genFun) { return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun; }, exports.awrap = function (arg) { return { __await: arg }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () { return this; }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) { void 0 === PromiseImpl && (PromiseImpl = Promise); var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl); return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) { return result.done ? result.value : iter.next(); }); }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () { return this; }), define(Gp, "toString", function () { return "[object Generator]"; }), exports.keys = function (val) { var object = Object(val), keys = []; for (var key in object) keys.push(key); return keys.reverse(), function next() { for (; keys.length;) { var key = keys.pop(); if (key in object) return next.value = key, next.done = !1, next; } return next.done = !0, next; }; }, exports.values = values, Context.prototype = { constructor: Context, reset: function reset(skipTempReset) { if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined); }, stop: function stop() { this.done = !0; var rootRecord = this.tryEntries[0].completion; if ("throw" === rootRecord.type) throw rootRecord.arg; return this.rval; }, dispatchException: function dispatchException(exception) { if (this.done) throw exception; var context = this; function handle(loc, caught) { return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught; } for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i], record = entry.completion; if ("root" === entry.tryLoc) return handle("end"); if (entry.tryLoc <= this.prev) { var hasCatch = hasOwn.call(entry, "catchLoc"), hasFinally = hasOwn.call(entry, "finallyLoc"); if (hasCatch && hasFinally) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } else if (hasCatch) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); } else { if (!hasFinally) throw new Error("try statement without catch or finally"); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } } } }, abrupt: function abrupt(type, arg) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) { var finallyEntry = entry; break; } } finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null); var record = finallyEntry ? finallyEntry.completion : {}; return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record); }, complete: function complete(record, afterLoc) { if ("throw" === record.type) throw record.arg; return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel; }, finish: function finish(finallyLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel; } }, "catch": function _catch(tryLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc === tryLoc) { var record = entry.completion; if ("throw" === record.type) { var thrown = record.arg; resetTryEntry(entry); } return thrown; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(iterable, resultName, nextLoc) { return this.delegate = { iterator: values(iterable), resultName: resultName, nextLoc: nextLoc }, "next" === this.method && (this.arg = undefined), ContinueSentinel; } }, exports; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
/**
 * Pull users data 
 */

(function (w, $) {
  'use strict';

  var AJAX_URL = w.ajaxurl;
  var pullSFUserData = /*#__PURE__*/function () {
    var _ref2 = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee(_ref) {
      var wpuid, sfuid;
      return _regeneratorRuntime().wrap(function _callee$(_context) {
        while (1) switch (_context.prev = _context.next) {
          case 0:
            wpuid = _ref.wpuid, sfuid = _ref.sfuid;
            _context.next = 3;
            return $.ajax({
              type: 'POST',
              url: AJAX_URL,
              data: {
                action: 'pp_ajax_request_sf_user_data',
                wpuid: wpuid,
                sfuid: sfuid
              },
              error: function error(e) {
                console.log(e);
              }
            });
          case 3:
            return _context.abrupt("return", _context.sent);
          case 4:
          case "end":
            return _context.stop();
        }
      }, _callee);
    }));
    return function pullSFUserData(_x) {
      return _ref2.apply(this, arguments);
    };
  }();
  var clickBtnPull = function clickBtnPull() {
    $(document.body).on('click', '.pp-pull-sf-user-data', /*#__PURE__*/function () {
      var _ref3 = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee2(e) {
        var $btn, initBtnText, _this$dataset, wpuid, sfuid, _yield$pullSFUserData, success, response, updated_columns;
        return _regeneratorRuntime().wrap(function _callee2$(_context2) {
          while (1) switch (_context2.prev = _context2.next) {
            case 0:
              e.preventDefault();
              $btn = $(this);
              initBtnText = $btn.text();
              _this$dataset = this.dataset, wpuid = _this$dataset.wpuid, sfuid = _this$dataset.sfuid;
              $btn.css({
                'pointerEvents': 'none',
                'opacity': .7
              }).text("Pulling...");
              _context2.next = 7;
              return pullSFUserData({
                wpuid: wpuid,
                sfuid: sfuid
              });
            case 7:
              _yield$pullSFUserData = _context2.sent;
              success = _yield$pullSFUserData.success;
              response = _yield$pullSFUserData.response;
              updated_columns = _yield$pullSFUserData.updated_columns;
              $btn.css({
                'pointerEvents': '',
                'opacity': 1
              }).text(initBtnText);

              /**
               * Error
               */
              if (!(success != true)) {
                _context2.next = 15;
                break;
              }
              alert("".concat(response.errorCode, ": ").concat(response.message));
              return _context2.abrupt("return");
            case 15:
              $.each(updated_columns, function (selector, value) {
                $(selector).html(value);
              });

              /**
               * Success
               */
              alert('Successfully!');
            case 17:
            case "end":
              return _context2.stop();
          }
        }, _callee2, this);
      }));
      return function (_x2) {
        return _ref3.apply(this, arguments);
      };
    }());
  };
  $(function () {
    clickBtnPull();
  });
})(window, jQuery);

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!**********************!*\
  !*** ./src/admin.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _js_salesforce_admin_pull_users_data__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./js/salesforce/admin/pull-users-data */ "./src/js/salesforce/admin/pull-users-data.js");
/* harmony import */ var _js_salesforce_admin_pull_users_data__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_js_salesforce_admin_pull_users_data__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _js_checkout_add_attendees_form__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/checkout-add-attendees-form */ "./src/js/checkout-add-attendees-form.js");
/* harmony import */ var _js_checkout_add_attendees_form__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_js_checkout_add_attendees_form__WEBPACK_IMPORTED_MODULE_1__);
/**
 * Admin scripts
 */



})();

/******/ })()
;