/*jshint eqnull:true */
/*!
 * jQuery Cookie Plugin v1.2
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function ($, document, undefined) {

    var pluses = /\+/g;

    function raw(s) {
        return s;
    }

    function decoded(s) {
        return decodeURIComponent(s.replace(pluses, ' '));
    }

    $.cookie = function (key, value, options) {

        // key and at least value given, set cookie...
        if (value !== undefined && !/Object/.test(Object.prototype.toString.call(value))) {
            options = $.extend({}, $.cookie.defaults, options);

            if (value === null) {
                options.expires = -1;
            }

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            value = String(value);

            return (document.cookie = [
                encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path    ? '; path=' + options.path : '',
                options.domain  ? '; domain=' + options.domain : '',
                options.secure  ? '; secure' : ''
            ].join(''));
        }

        // key and possibly options given, get cookie...
        options = value || $.cookie.defaults || {};
        var decode = options.raw ? raw : decoded;
        var cookies = document.cookie.split('; ');
        for (var i = 0, parts; (parts = cookies[i] && cookies[i].split('=')); i++) {
            if (decode(parts.shift()) === key) {
                return decode(parts.join('='));
            }
        }

        return null;
    };

    $.cookie.defaults = {};

    $.removeCookie = function (key, options) {
        if ($.cookie(key, options) !== null) {
            $.cookie(key, null, options);
            return true;
        }
        return false;
    };

})(jQuery, document);

/* Modernizr 2.6.1 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-mq-teststyles
 */
;



window.Modernizr = (function( window, document, undefined ) {

    var version = '2.6.1',

        Modernizr = {},


        docElement = document.documentElement,

        mod = 'modernizr',
        modElem = document.createElement(mod),
        mStyle = modElem.style,

        inputElem  ,


        toString = {}.toString,    tests = {},
        inputs = {},
        attrs = {},

        classes = [],

        slice = classes.slice,

        featureName,


        injectElementWithStyles = function( rule, callback, nodes, testnames ) {

            var style, ret, node,
                div = document.createElement('div'),
                body = document.body,
                fakeBody = body ? body : document.createElement('body');

            if ( parseInt(nodes, 10) ) {
                while ( nodes-- ) {
                    node = document.createElement('div');
                    node.id = testnames ? testnames[nodes] : mod + (nodes + 1);
                    div.appendChild(node);
                }
            }

            style = ['&#173;','<style id="s', mod, '">', rule, '</style>'].join('');
            div.id = mod;
            (body ? div : fakeBody).innerHTML += style;
            fakeBody.appendChild(div);
            if ( !body ) {
                fakeBody.style.background = "";
                docElement.appendChild(fakeBody);
            }

            ret = callback(div, rule);
            !body ? fakeBody.parentNode.removeChild(fakeBody) : div.parentNode.removeChild(div);

            return !!ret;

        },

        testMediaQuery = function( mq ) {

            var matchMedia = window.matchMedia || window.msMatchMedia;
            if ( matchMedia ) {
                return matchMedia(mq).matches;
            }

            var bool;

            injectElementWithStyles('@media ' + mq + ' { #' + mod + ' { position: absolute; } }', function( node ) {
                bool = (window.getComputedStyle ?
                    getComputedStyle(node, null) :
                    node.currentStyle)['position'] == 'absolute';
            });

            return bool;

        },
        _hasOwnProperty = ({}).hasOwnProperty, hasOwnProp;

    if ( !is(_hasOwnProperty, 'undefined') && !is(_hasOwnProperty.call, 'undefined') ) {
        hasOwnProp = function (object, property) {
            return _hasOwnProperty.call(object, property);
        };
    }
    else {
        hasOwnProp = function (object, property) {
            return ((property in object) && is(object.constructor.prototype[property], 'undefined'));
        };
    }


    if (!Function.prototype.bind) {
        Function.prototype.bind = function bind(that) {

            var target = this;

            if (typeof target != "function") {
                throw new TypeError();
            }

            var args = slice.call(arguments, 1),
                bound = function () {

                    if (this instanceof bound) {

                        var F = function(){};
                        F.prototype = target.prototype;
                        var self = new F();

                        var result = target.apply(
                            self,
                            args.concat(slice.call(arguments))
                        );
                        if (Object(result) === result) {
                            return result;
                        }
                        return self;

                    } else {

                        return target.apply(
                            that,
                            args.concat(slice.call(arguments))
                        );

                    }

                };

            return bound;
        };
    }

    function setCss( str ) {
        mStyle.cssText = str;
    }

    function setCssAll( str1, str2 ) {
        return setCss(prefixes.join(str1 + ';') + ( str2 || '' ));
    }

    function is( obj, type ) {
        return typeof obj === type;
    }

    function contains( str, substr ) {
        return !!~('' + str).indexOf(substr);
    }


    function testDOMProps( props, obj, elem ) {
        for ( var i in props ) {
            var item = obj[props[i]];
            if ( item !== undefined) {

                if (elem === false) return props[i];

                if (is(item, 'function')){
                    return item.bind(elem || obj);
                }

                return item;
            }
        }
        return false;
    }
    for ( var feature in tests ) {
        if ( hasOwnProp(tests, feature) ) {
            featureName  = feature.toLowerCase();
            Modernizr[featureName] = tests[feature]();

            classes.push((Modernizr[featureName] ? '' : 'no-') + featureName);
        }
    }



    Modernizr.addTest = function ( feature, test ) {
        if ( typeof feature == 'object' ) {
            for ( var key in feature ) {
                if ( hasOwnProp( feature, key ) ) {
                    Modernizr.addTest( key, feature[ key ] );
                }
            }
        } else {

            feature = feature.toLowerCase();

            if ( Modernizr[feature] !== undefined ) {
                return Modernizr;
            }

            test = typeof test == 'function' ? test() : test;

            if (enableClasses) {
                docElement.className += ' ' + (test ? '' : 'no-') + feature;
            }
            Modernizr[feature] = test;

        }

        return Modernizr;
    };


    setCss('');
    modElem = inputElem = null;


    Modernizr._version      = version;


    Modernizr.mq            = testMediaQuery;
    Modernizr.testStyles    = injectElementWithStyles;
    return Modernizr;

})(this, this.document);
;


/*
 * jQuery Foundation Joyride Plugin 2.1
 * http://foundation.zurb.com
 * Copyright 2013, ZURB
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

/*jslint unparam: true, browser: true, indent: 2 */

;(function ($, window, undefined) {
    'use strict';

    var defaults = {
            'version'              : '2.1',
            'tipLocation'          : 'bottom',  // 'top' or 'bottom' in relation to parent
            'nubPosition'          : 'auto',    // override on a per tooltip bases
            'scroll'               : true,      // whether to scroll to tips
            'scrollSpeed'          : 300,       // Page scrolling speed in milliseconds
            'timer'                : 0,         // 0 = no timer , all other numbers = timer in milliseconds
            'autoStart'            : false,     // true or false - false tour starts when restart called
            'startTimerOnClick'    : true,      // true or false - true requires clicking the first button start the timer
            'startOffset'          : 0,         // the index of the tooltip you want to start on (index of the li)
            'nextButton'           : true,      // true or false to control whether a next button is used
            'tipAnimation'         : 'pop',    // 'pop' or 'fade' in each tip
            'pauseAfter'           : [],        // array of indexes where to pause the tour after
            'tipAnimationFadeSpeed': 300,       // when tipAnimation = 'fade' this is speed in milliseconds for the transition
            'cookieMonster'        : false,     // true or false to control whether cookies are used
            'cookieName'           : 'joyride', // Name the cookie you'll use
            'cookieDomain'         : false,     // Will this cookie be attached to a domain, ie. '.notableapp.com'
            'cookiePath'           : false,     // Set to '/' if you want the cookie for the whole website
            'localStorage'         : false,     // true or false to control whether localstorage is used
            'localStorageKey'      : 'joyride', // Keyname in localstorage
            'tipContainer'         : 'body',    // Where will the tip be attached
            'modal'                : false,     // Whether to cover page with modal during the tour
            'expose'               : false,     // Whether to expose the elements at each step in the tour (requires modal:true)
            'postExposeCallback'   : $.noop,    // A method to call after an element has been exposed
            'preRideCallback'      : $.noop,    // A method to call before the tour starts (passed index, tip, and cloned exposed element)
            'postRideCallback'     : $.noop,    // A method to call once the tour closes (canceled or complete)
            'preStepCallback'      : $.noop,    // A method to call before each step
            'postStepCallback'     : $.noop,    // A method to call after each step
            'template' : { // HTML segments for tip layout
                'link'    : '<a href="#close" class="joyride-close-tip">X</a>',
                'timer'   : '<div class="joyride-timer-indicator-wrap"><span class="joyride-timer-indicator"></span></div>',
                'tip'     : '<div class="joyride-tip-guide"><span class="joyride-nub"></span></div>',
                'wrapper' : '<div class="joyride-content-wrapper" role="dialog"></div>',
                'button'  : '<a href="#" class="joyride-next-tip"></a>',
                'modal'   : '<div class="joyride-modal-bg"></div>',
                'expose'  : '<div class="joyride-expose-wrapper"></div>',
                'exposeCover': '<div class="joyride-expose-cover"></div>'
            }
        },

        Modernizr = Modernizr || false,

        settings = {},

        methods = {

            init : function (opts) {
                return this.each(function () {

                    if ($.isEmptyObject(settings)) {
                        settings = $.extend(true, defaults, opts);

                        // non configurable settings
                        settings.document = window.document;
                        settings.$document = $(settings.document);
                        settings.$window = $(window);
                        settings.$content_el = $(this);
                        settings.$body = $(settings.tipContainer);
                        settings.body_offset = $(settings.tipContainer).position();
                        settings.$tip_content = $('> li', settings.$content_el);
                        settings.paused = false;
                        settings.attempts = 0;

                        settings.tipLocationPatterns = {
                            top: ['bottom'],
                            bottom: [], // bottom should not need to be repositioned
                            left: ['right', 'top', 'bottom'],
                            right: ['left', 'top', 'bottom']
                        };

                        // are we using jQuery 1.7+
                        methods.jquery_check();

                        // can we create cookies?
                        if (!$.isFunction($.cookie)) {
                            settings.cookieMonster = false;
                        }

                        // generate the tips and insert into dom.
                        if ( (!settings.cookieMonster || !$.cookie(settings.cookieName) ) &&
                            (!settings.localStorage || !methods.support_localstorage() || !localStorage.getItem(settings.localStorageKey) ) ) {

                            settings.$tip_content.each(function (index) {
                                methods.create({$li : $(this), index : index});
                            });

                            // show first tip
                            if(settings.autoStart)
                            {
                                if (!settings.startTimerOnClick && settings.timer > 0) {
                                    methods.show('init');
                                    methods.startTimer();
                                } else {
                                    methods.show('init');
                                }
                            }

                        }

                        settings.$document.on('click.joyride', '.joyride-next-tip, .joyride-modal-bg', function (e) {
                            e.preventDefault();

                            if (settings.$li.next().length < 1) {
                                methods.end();
                            } else if (settings.timer > 0) {
                                clearTimeout(settings.automate);
                                methods.hide();
                                methods.show();
                                methods.startTimer();
                            } else {
                                methods.hide();
                                methods.show();
                            }

                        });

                        settings.$document.on('click.joyride', '.joyride-close-tip', function (e) {
                            e.preventDefault();
                            methods.end(true /* isAborted */);
                        });

                        settings.$window.bind('resize.joyride', function (e) {
                            if(settings.$li){
                                if(settings.exposed && settings.exposed.length>0){
                                    var $els = $(settings.exposed);
                                    $els.each(function(){
                                        var $this = $(this);
                                        methods.un_expose($this);
                                        methods.expose($this);
                                    });
                                }
                                if (methods.is_phone()) {
                                    methods.pos_phone();
                                } else {
                                    methods.pos_default();
                                }
                            }
                        });
                    } else {
                        methods.restart();
                    }

                });
            },

            // call this method when you want to resume the tour
            resume : function () {
                methods.set_li();
                methods.show();
            },

            nextTip: function(){
                if (settings.$li.next().length < 1) {
                    methods.end();
                } else if (settings.timer > 0) {
                    clearTimeout(settings.automate);
                    methods.hide();
                    methods.show();
                    methods.startTimer();
                } else {
                    methods.hide();
                    methods.show();
                }
            },

            tip_template : function (opts) {
                var $blank, content, $wrapper;

                opts.tip_class = opts.tip_class || '';

                $blank = $(settings.template.tip).addClass(opts.tip_class);
                content = $.trim($(opts.li).html()) +
                    methods.button_text(opts.button_text) +
                    settings.template.link +
                    methods.timer_instance(opts.index);

                $wrapper = $(settings.template.wrapper);
                if (opts.li.attr('data-aria-labelledby')) {
                    $wrapper.attr('aria-labelledby', opts.li.attr('data-aria-labelledby'))
                }
                if (opts.li.attr('data-aria-describedby')) {
                    $wrapper.attr('aria-describedby', opts.li.attr('data-aria-describedby'))
                }
                $blank.append($wrapper);
                $blank.first().attr('data-index', opts.index);
                $('.joyride-content-wrapper', $blank).append(content);

                return $blank[0];
            },

            timer_instance : function (index) {
                var txt;

                if ((index === 0 && settings.startTimerOnClick && settings.timer > 0) || settings.timer === 0) {
                    txt = '';
                } else {
                    txt = methods.outerHTML($(settings.template.timer)[0]);
                }
                return txt;
            },

            button_text : function (txt) {
                if (settings.nextButton) {
                    txt = $.trim(txt) || 'Next';
                    txt = methods.outerHTML($(settings.template.button).append(txt)[0]);
                } else {
                    txt = '';
                }
                return txt;
            },

            create : function (opts) {
                // backwards compatibility with data-text attribute
                var buttonText = opts.$li.attr('data-button') || opts.$li.attr('data-text'),
                    tipClass = opts.$li.attr('class'),
                    $tip_content = $(methods.tip_template({
                        tip_class : tipClass,
                        index : opts.index,
                        button_text : buttonText,
                        li : opts.$li
                    }));

                $(settings.tipContainer).append($tip_content);
            },

            show : function (init) {
                var opts = {}, ii, opts_arr = [], opts_len = 0, p,
                    $timer = null;

                // are we paused?
                if (settings.$li === undefined || ($.inArray(settings.$li.index(), settings.pauseAfter) === -1)) {

                    // don't go to the next li if the tour was paused
                    if (settings.paused) {
                        settings.paused = false;
                    } else {
                        methods.set_li(init);
                    }

                    settings.attempts = 0;

                    if (settings.$li.length && settings.$target.length > 0) {
                        if(init){ //run when we first start
                            settings.preRideCallback(settings.$li.index(), settings.$next_tip );
                            if(settings.modal){
                                methods.show_modal();
                            }
                        }
                        settings.preStepCallback(settings.$li.index(), settings.$next_tip );

                        // parse options
                        opts_arr = (settings.$li.data('options') || ':').split(';');
                        opts_len = opts_arr.length;
                        for (ii = opts_len - 1; ii >= 0; ii--) {
                            p = opts_arr[ii].split(':');

                            if (p.length === 2) {
                                opts[$.trim(p[0])] = $.trim(p[1]);
                            }
                        }
                        settings.tipSettings = $.extend({}, settings, opts);
                        settings.tipSettings.tipLocationPattern = settings.tipLocationPatterns[settings.tipSettings.tipLocation];

                        if(settings.modal && settings.expose){
                            methods.expose();
                        }

                        // scroll if not modal
                        if (!/body/i.test(settings.$target.selector) && settings.scroll) {
                            methods.scroll_to();
                        }

                        if (methods.is_phone()) {
                            methods.pos_phone(true);
                        } else {
                            methods.pos_default(true);
                        }

                        $timer = $('.joyride-timer-indicator', settings.$next_tip);

                        if (/pop/i.test(settings.tipAnimation)) {

                            $timer.outerWidth(0);

                            if (settings.timer > 0) {

                                settings.$next_tip.show();
                                $timer.animate({
                                    width: $('.joyride-timer-indicator-wrap', settings.$next_tip).outerWidth()
                                }, settings.timer);

                            } else {

                                settings.$next_tip.show();

                            }


                        } else if (/fade/i.test(settings.tipAnimation)) {

                            $timer.outerWidth(0);

                            if (settings.timer > 0) {

                                settings.$next_tip.fadeIn(settings.tipAnimationFadeSpeed);

                                settings.$next_tip.show();
                                $timer.animate({
                                    width: $('.joyride-timer-indicator-wrap', settings.$next_tip).outerWidth()
                                }, settings.timer);

                            } else {

                                settings.$next_tip.fadeIn(settings.tipAnimationFadeSpeed);

                            }
                        }

                        settings.$current_tip = settings.$next_tip;
                        // Focus next button for keyboard users.
                        $('.joyride-next-tip', settings.$current_tip).focus();
                        methods.tabbable(settings.$current_tip);
                        // skip non-existent targets
                    } else if (settings.$li && settings.$target.length < 1) {

                        methods.show();

                    } else {

                        methods.end();

                    }
                } else {

                    settings.paused = true;

                }

            },

            // detect phones with media queries if supported.
            is_phone : function () {
                if (Modernizr) {
                    return Modernizr.mq('only screen and (max-width: 767px)');
                }

                return (settings.$window.width() < 767) ? true : false;
            },

            support_localstorage : function () {
                if (Modernizr) {
                    return Modernizr.localstorage;
                } else {
                    return !!window.localStorage;
                }
            },

            hide : function () {
                if(settings.modal && settings.expose){
                    methods.un_expose();
                }
                if(!settings.modal){
                    $('.joyride-modal-bg').hide();
                }
                settings.$current_tip.hide();
                settings.postStepCallback(settings.$li.index(), settings.$current_tip);
            },

            set_li : function (init) {
                if (init) {
                    settings.$li = settings.$tip_content.eq(settings.startOffset);
                    methods.set_next_tip();
                    settings.$current_tip = settings.$next_tip;
                } else {
                    settings.$li = settings.$li.next();
                    methods.set_next_tip();
                }

                methods.set_target();
            },

            set_next_tip : function () {
                settings.$next_tip = $('.joyride-tip-guide[data-index=' + settings.$li.index() + ']');
            },

            set_target : function () {
                var cl = settings.$li.attr('data-class'),
                    id = settings.$li.attr('data-id'),
                    $sel = function () {
                        if (id) {
                            return $(settings.document.getElementById(id));
                        } else if (cl) {
                            return $('.' + cl).filter(":visible").first();
                        } else {
                            return $('body');
                        }
                    };

                settings.$target = $sel();
            },

            scroll_to : function () {
                var window_half, tipOffset;

                window_half = settings.$window.height() / 2;
                tipOffset = Math.ceil(settings.$target.offset().top - window_half + settings.$next_tip.outerHeight());

                $("html, body").stop().animate({
                    scrollTop: tipOffset
                }, settings.scrollSpeed);
            },

            paused : function () {
                if (($.inArray((settings.$li.index() + 1), settings.pauseAfter) === -1)) {
                    return true;
                }

                return false;
            },

            destroy : function () {
                if(!$.isEmptyObject(settings)){
                    settings.$document.off('.joyride');
                }

                $(window).off('.joyride');
                $('.joyride-close-tip, .joyride-next-tip, .joyride-modal-bg').off('.joyride');
                $('.joyride-tip-guide, .joyride-modal-bg').remove();
                clearTimeout(settings.automate);
                settings = {};
            },

            restart : function () {
                if(!settings.autoStart)
                {
                    if (!settings.startTimerOnClick && settings.timer > 0) {
                        methods.show('init');
                        methods.startTimer();
                    } else {
                        methods.show('init');
                    }
                    settings.autoStart = true;
                }
                else
                {
                    methods.hide();
                    settings.$li = undefined;
                    methods.show('init');
                }
            },

            pos_default : function (init) {
                var half_fold = Math.ceil(settings.$window.height() / 2),
                    tip_position = settings.$next_tip.offset(),
                    $nub = $('.joyride-nub', settings.$next_tip),
                    nub_width = Math.ceil($nub.outerWidth() / 2),
                    nub_height = Math.ceil($nub.outerHeight() / 2),
                    toggle = init || false;

                // tip must not be "display: none" to calculate position
                if (toggle) {
                    settings.$next_tip.css('visibility', 'hidden');
                    settings.$next_tip.show();
                }

                if (!/body/i.test(settings.$target.selector)) {
                    var
                        topAdjustment = settings.tipSettings.tipAdjustmentY ? parseInt(settings.tipSettings.tipAdjustmentY) : 0,
                        leftAdjustment = settings.tipSettings.tipAdjustmentX ? parseInt(settings.tipSettings.tipAdjustmentX) : 0;

                    if (methods.bottom()) {
                        settings.$next_tip.css({
                            top: (settings.$target.offset().top + nub_height + settings.$target.outerHeight() + topAdjustment),
                            left: settings.$target.offset().left + leftAdjustment});

                        if (/right/i.test(settings.tipSettings.nubPosition)) {
                            settings.$next_tip.css('left', settings.$target.offset().left - settings.$next_tip.outerWidth() + settings.$target.outerWidth());
                        }

                        methods.nub_position($nub, settings.tipSettings.nubPosition, 'top');

                    } else if (methods.top()) {

                        settings.$next_tip.css({
                            top: (settings.$target.offset().top - settings.$next_tip.outerHeight() - nub_height + topAdjustment),
                            left: settings.$target.offset().left + leftAdjustment});

                        methods.nub_position($nub, settings.tipSettings.nubPosition, 'bottom');

                    } else if (methods.right()) {

                        settings.$next_tip.css({
                            top: settings.$target.offset().top + topAdjustment,
                            left: (settings.$target.outerWidth() + settings.$target.offset().left + nub_width) + leftAdjustment});

                        methods.nub_position($nub, settings.tipSettings.nubPosition, 'left');

                    } else if (methods.left()) {

                        settings.$next_tip.css({
                            top: settings.$target.offset().top + topAdjustment,
                            left: (settings.$target.offset().left - settings.$next_tip.outerWidth() - nub_width) + leftAdjustment});

                        methods.nub_position($nub, settings.tipSettings.nubPosition, 'right');

                    }

                    if (!methods.visible(methods.corners(settings.$next_tip)) && settings.attempts < settings.tipSettings.tipLocationPattern.length) {

                        $nub.removeClass('bottom')
                            .removeClass('top')
                            .removeClass('right')
                            .removeClass('left');

                        settings.tipSettings.tipLocation = settings.tipSettings.tipLocationPattern[settings.attempts];

                        settings.attempts++;

                        methods.pos_default(true);

                    }

                } else if (settings.$li.length) {

                    methods.pos_modal($nub);

                }

                if (toggle) {
                    settings.$next_tip.hide();
                    settings.$next_tip.css('visibility', 'visible');
                }

            },

            pos_phone : function (init) {
                var tip_height = settings.$next_tip.outerHeight(),
                    tip_offset = settings.$next_tip.offset(),
                    target_height = settings.$target.outerHeight(),
                    $nub = $('.joyride-nub', settings.$next_tip),
                    nub_height = Math.ceil($nub.outerHeight() / 2),
                    toggle = init || false;

                $nub.removeClass('bottom')
                    .removeClass('top')
                    .removeClass('right')
                    .removeClass('left');

                if (toggle) {
                    settings.$next_tip.css('visibility', 'hidden');
                    settings.$next_tip.show();
                }

                if (!/body/i.test(settings.$target.selector)) {

                    if (methods.top()) {

                        settings.$next_tip.offset({top: settings.$target.offset().top - tip_height - nub_height});
                        $nub.addClass('bottom');

                    } else {

                        settings.$next_tip.offset({top: settings.$target.offset().top + target_height + nub_height});
                        $nub.addClass('top');

                    }

                } else if (settings.$li.length) {

                    methods.pos_modal($nub);

                }

                if (toggle) {
                    settings.$next_tip.hide();
                    settings.$next_tip.css('visibility', 'visible');
                }
            },

            pos_modal : function ($nub) {
                methods.center();
                $nub.hide();

                methods.show_modal();

            },

            show_modal : function() {
                if ($('.joyride-modal-bg').length < 1) {
                    $('body').append(settings.template.modal).show();
                }

                if (/pop/i.test(settings.tipAnimation)) {
                    $('.joyride-modal-bg').show();
                } else {
                    $('.joyride-modal-bg').fadeIn(settings.tipAnimationFadeSpeed);
                }
            },

            expose: function(){
                var expose,
                    exposeCover,
                    el,
                    origCSS,
                    randId = 'expose-'+Math.floor(Math.random()*10000);
                if (arguments.length>0 && arguments[0] instanceof $){
                    el = arguments[0];
                } else if(settings.$target && !/body/i.test(settings.$target.selector)){
                    el = settings.$target;
                }  else {
                    return false;
                }
                if(el.length < 1){
                    if(window.console){
                        console.error('element not valid', el);
                    }
                    return false;
                }
                expose = $(settings.template.expose);
                settings.$body.append(expose);
                expose.css({
                    top: el.offset().top,
                    left: el.offset().left,
                    width: el.outerWidth(true),
                    height: el.outerHeight(true)
                });
                exposeCover = $(settings.template.exposeCover);
                origCSS = {
                    zIndex: el.css('z-index'),
                    position: el.css('position')
                };
                el.css('z-index',expose.css('z-index')*1+1);
                if(origCSS.position == 'static'){
                    el.css('position','relative');
                }
                el.data('expose-css',origCSS);
                exposeCover.css({
                    top: el.offset().top,
                    left: el.offset().left,
                    width: el.outerWidth(true),
                    height: el.outerHeight(true)
                });
                settings.$body.append(exposeCover);
                expose.addClass(randId);
                exposeCover.addClass(randId);
                if(settings.tipSettings['exposeClass']){
                    expose.addClass(settings.tipSettings['exposeClass']);
                    exposeCover.addClass(settings.tipSettings['exposeClass']);
                }
                el.data('expose', randId);
                settings.postExposeCallback(settings.$li.index(), settings.$next_tip, el);
                methods.add_exposed(el);
            },

            un_expose: function(){
                var exposeId,
                    el,
                    expose ,
                    origCSS,
                    clearAll = false;
                if (arguments.length>0 && arguments[0] instanceof $){
                    el = arguments[0];
                } else if(settings.$target && !/body/i.test(settings.$target.selector)){
                    el = settings.$target;
                }  else {
                    return false;
                }
                if(el.length < 1){
                    if(window.console){
                        console.error('element not valid', el);
                    }
                    return false;
                }
                exposeId = el.data('expose');
                expose = $('.'+exposeId);
                if(arguments.length>1){
                    clearAll = arguments[1];
                }
                if(clearAll === true){
                    $('.joyride-expose-wrapper,.joyride-expose-cover').remove();
                } else {
                    expose.remove();
                }
                origCSS = el.data('expose-css');
                if(origCSS.zIndex == 'auto'){
                    el.css('z-index', '');
                } else {
                    el.css('z-index',origCSS.zIndex);
                }
                if(origCSS.position != el.css('position')){
                    if(origCSS.position == 'static'){// this is default, no need to set it.
                        el.css('position', '');
                    } else {
                        el.css('position',origCSS.position);
                    }
                }
                el.removeData('expose');
                el.removeData('expose-z-index');
                methods.remove_exposed(el);
            },

            add_exposed: function(el){
                settings.exposed = settings.exposed || [];
                if(el instanceof $){
                    settings.exposed.push(el[0]);
                } else if(typeof el == 'string'){
                    settings.exposed.push(el);
                }
            },

            remove_exposed: function(el){
                var search;
                if(el instanceof $){
                    search = el[0]
                } else if (typeof el == 'string'){
                    search = el;
                }
                settings.exposed = settings.exposed || [];
                for(var i=0; i<settings.exposed.length; i++){
                    if(settings.exposed[i] == search){
                        settings.exposed.splice(i,1);
                        return;
                    }
                }
            },

            center : function () {
                var $w = settings.$window;

                settings.$next_tip.css({
                    top : ((($w.height() - settings.$next_tip.outerHeight()) / 2) + $w.scrollTop()),
                    left : ((($w.width() - settings.$next_tip.outerWidth()) / 2) + $w.scrollLeft())
                });

                return true;
            },

            bottom : function () {
                return /bottom/i.test(settings.tipSettings.tipLocation);
            },

            top : function () {
                return /top/i.test(settings.tipSettings.tipLocation);
            },

            right : function () {
                return /right/i.test(settings.tipSettings.tipLocation);
            },

            left : function () {
                return /left/i.test(settings.tipSettings.tipLocation);
            },

            corners : function (el) {
                var w = settings.$window,
                    window_half = w.height() / 2,
                    tipOffset = Math.ceil(settings.$target.offset().top - window_half + settings.$next_tip.outerHeight()),//using this to calculate since scroll may not have finished yet.
                    right = w.width() + w.scrollLeft(),
                    offsetBottom =  w.height() + tipOffset,
                    bottom = w.height() + w.scrollTop(),
                    top = w.scrollTop();

                if(tipOffset < top){
                    if (tipOffset <0 ){
                        top = 0;
                    } else {
                        top = tipOffset;
                    }
                }

                if(offsetBottom > bottom){
                    bottom = offsetBottom;
                }

                return [
                    el.offset().top < top,
                    right < el.offset().left + el.outerWidth(),
                    bottom < el.offset().top + el.outerHeight(),
                    w.scrollLeft() > el.offset().left
                ];
            },

            visible : function (hidden_corners) {
                var i = hidden_corners.length;

                while (i--) {
                    if (hidden_corners[i]) return false;
                }

                return true;
            },

            nub_position : function (nub, pos, def) {
                if (pos === 'auto') {
                    nub.addClass(def);
                } else {
                    nub.addClass(pos);
                }
            },

            startTimer : function () {
                if (settings.$li.length) {
                    settings.automate = setTimeout(function () {
                        methods.hide();
                        methods.show();
                        methods.startTimer();
                    }, settings.timer);
                } else {
                    clearTimeout(settings.automate);
                }
            },

            end : function (isAborted) {
                isAborted = isAborted || false;

                // Unbind resize events.
                if (isAborted) {
                    settings.$window.unbind('resize.joyride');
                }

                if (settings.cookieMonster) {
                    $.cookie(settings.cookieName, 'ridden', { expires: 365, domain: settings.cookieDomain, path: settings.cookiePath });
                }

                if (settings.localStorage) {
                    localStorage.setItem(settings.localStorageKey, true);
                }

                if (settings.timer > 0) {
                    clearTimeout(settings.automate);
                }
                if(settings.modal && settings.expose){
                    methods.un_expose();
                }
                if (settings.$current_tip) {
                    settings.$current_tip.hide();
                }
                if (settings.$li) {
                    settings.postStepCallback(settings.$li.index(), settings.$current_tip, isAborted);
                    settings.postRideCallback(settings.$li.index(), settings.$current_tip, isAborted);
                }
                $('.joyride-modal-bg').hide();
            },

            jquery_check : function () {
                // define on() and off() for older jQuery
                if (!$.isFunction($.fn.on)) {

                    $.fn.on = function (types, sel, fn) {

                        return this.delegate(sel, types, fn);

                    };

                    $.fn.off = function (types, sel, fn) {

                        return this.undelegate(sel, types, fn);

                    };

                    return false;
                }

                return true;
            },

            outerHTML : function (el) {
                // support FireFox < 11
                return el.outerHTML || new XMLSerializer().serializeToString(el);
            },

            version : function () {
                return settings.version;
            },

            tabbable : function (el) {
                $(el).on('keydown', function( event ) {
                    if (!event.isDefaultPrevented() && event.keyCode &&
                        // Escape key.
                        event.keyCode === 27 ) {
                        event.preventDefault();
                        methods.end(true /* isAborted */);
                        return;
                    }

                    // Prevent tabbing out of tour items.
                    if ( event.keyCode !== 9 ) {
                        return;
                    }
                    var tabbables = $(el).find(":tabbable"),
                        first = tabbables.filter(":first"),
                        last  = tabbables.filter(":last");
                    if ( event.target === last[0] && !event.shiftKey ) {
                        first.focus( 1 );
                        event.preventDefault();
                    } else if ( event.target === first[0] && event.shiftKey ) {
                        last.focus( 1 );
                        event.preventDefault();
                    }
                });
            }

        };

    $.fn.joyride = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' +  method + ' does not exist on jQuery.joyride');
        }
    };

}(jQuery, this));


window.onload = function () {
    var TIMEOUT=15;//minutes
    refreshTiny();
    $('#modalform').submit(function(e){
        $('#modalform').find('button[type="submit"]').val("<span class=\"glyphicon glyphicon-cog\"></span> Uploading logo");
        var fd = new FormData($(this)[0]);
        $.ajax({
            url     : "includes/ajax.php",
            type    : "POST",
            data    : fd,
            dataType: "html",
            cache: false,
            contentType: false,
            processData: false,
            async:false,
            success: function(data){
                $('#modalform').find('input[name="logo"]').val(data);
                $('#modalform').find('button[type="submit"]').val("<span class=\"glyphicon glyphicon-ok\"></span>");
                ajax_right($('#modalform').find('button[type="submit"]'));
          }
        });
        e.preventDefault();
    });
    $('[data-action="ajax-right"]').click(function(e){
        ajax_right($(this));
        e.preventDefault();
    });
    function ajax_right($this){
        emptyView();
        loading();
        $('#view_site_template').modal('hide');
        $('#create_new').modal('hide');
        if($this.data('names')) var params=$this.data('names').split(',');
        var obj=new Object();
        if(params) for(var i=0;i<params.length;++i)
        {
            if($this.data('where')=='this')
                $lookin=$this;
            else
                $lookin=$($this.data('where'));
            if($lookin.find('[name="'+params[i]+'"]').val())
                obj[params[i]]=$lookin.find('[name="'+params[i]+'"]').val();
            else
                obj[params[i]]=$lookin.data(params[i]);
        }
        obj['fnc']=$this.data('target');
        $.ajax({
            url     : "includes/ajax.php",
            type    : "POST",
            data    : obj,
            dataType: "html",
            success : function (data) {
                $('.main-content').html(data);
            }
        });
        return false;
    }
    $('.main-content').on('click', '.plusemail', function(){
        console.log($(this).parent());
        $(this).parent().append('<div class="input-group">'+
                '<span class="input-group-addon">Email</span>'+
                '<input type="text" class="form-control" name="email[]" value="">'+
                '<span class="input-group-addon deleteemail btn-danger">-</span>'+
            '</div>');
    });
    $('.main-content').on('click', '.deleteemail', function(){
        console.log($(this).parent());
        $(this).parent().remove();
    });
    $('.view-site-template').click(function (){
        $('#view_site_template').find('input[name="code"]').val($(this).data("code"));
     });
    $('.create-new').click(function (){
        $('.new').find('input[name="codenew"]').val($(this).data("code"));
        $('#modalform').find('button[type="submit"]').val('Submit');
     });
    $('.main-content').on('click', '.pluspage', function(e) {
        console.log('whats up.');
        $('<textarea name="content[' + $('#nb_textarea').val() + ']" id="content' + $(' #nb_textarea').val() + '" style="width:100%;height:400px;"></textarea>').insertAfter($('#content' + ($('#nb_textarea').val() - 1)));
        $('#nb_textarea').val(parseInt($('#nb_textarea').val()) + 1);
        refreshTiny();
    });
    $('.main-content').on('click', '.deletepage', function(e) {
        var id=$(this).parent().find('iframe').attr('id');
        if(id.length>0) id=id.substr(0,8);
        console.log(id);
        tinymce.remove('#'+id);
        $('#'+id).remove();
        e.preventDefault();
        return false;
    });
    $('#filter').focus(function () {
        $(this).val('');
    });
    $('#filter').keyup(function () {
        var v = $(this).val();
        if (v != '') {
            $('.list-sites li').each(function (index) {
                var t = $(this).text();
                if (t.indexOf(v) == -1) {
                    $(this).css('display', 'none');
                }
                else {
                    $(this).css('display', 'block');
                }
            });
        }
        else {
            $('.list-sites li').each(function (index) {
                $(this).css('display', 'block');
            });
        }
    });
    var timerout=setTimeout(user_is_afk,TIMEOUT*60*1000);
    $(window).mousemove(function(){
        clearTimeout(timerout);
        timerout=setTimeout(user_is_afk,TIMEOUT*60*1000);
    });
    function user_is_afk(){
        $('#afk').modal('show');
        $.ajax({
            url     : "includes/ajax.php",
            type    : "GET",
            data    : {'action':'revoke'}
        });
    }
    $('[data-toggle="tooltip"]').tooltip();
};
function emptyView() {
    $('.main-content').html('');
    $('.toolbar .right').remove();
}
function refreshTiny() {
    tinymce.init({
        selector     : "textarea",
        theme        : "modern",
        plugins      : [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor wfc"
        ],
        toolbar1     : "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2     : "print preview media | forecolor backcolor emoticons | wfc",
        image_advtab : true,
        content_css  : "css/style.css",
        relative_urls: false
    });
    setTimeout(function() {
        $('.mce-tinymce').prepend('<button class="btn-danger deletepage" style="position:absolute; right:0;, top:0;">X</button>');
    },2000);
}
function loading() {
    $('.main-content').html('<div style="width:124px;margin:0 auto;margin-top:100px;"><span class="glyphicon glyphicon-cog"></span></div>');
}

$(function(){
    $('.wfc-tooltip').tooltip();

    $('.nav-wfc-templates,.nav-wfc-properties').click(function(){
        var x = $("span.glyphicon", $(this));
        if($(this).hasClass('collapsed',x)){
            toggleAccordioIcon('minus',x);
        }else{
            toggleAccordioIcon('plus',x);
        }

    });
    function toggleAccordioIcon(action,el){
        if(action == 'minus'){
            $(el).removeClass('glyphicon-plus').addClass('glyphicon-minus');
        }else{
            $(el).removeClass('glyphicon-minus').addClass('glyphicon-plus');
        }
    }

    $('.wfc-send-report').click(function(){
        e.preventDefault();
        if(confirm('Are You Sure?')){
            alert('@SCFTODO: UPDATE THIS WITH URL TO EMAIL REPORT');
        }
    });

    $('.sidebar-menu-toggle').click(function(){
        $('#sidebar').css('width','0px');
        $('.nav-wfc-properties-container,.nav-wfc-templates-container').css('display','none');
        $(this).css('left','25px');
        $(this).addClass('wfc-sidebar-collapsed');
    });
});