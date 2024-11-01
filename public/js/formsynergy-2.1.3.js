/**
 * @package     SynergyPress
 * @version     1.6.0
 * @description Form Synergy javaScript API version: 2.1.3
 * 
 */
class FormSynergy {
    constructor() {
        let siteId = this.siteId();
        this._fs = {
            heartbeat: false,
            hb: 7500,
            siteid: siteId,
            title: document.title,
            bodyAddon: [],
            hooks: {},
            activeMode: 'defaultMode',
            defaultMode: {
                status: "enabled",
                options: {
                    endPoint: "https://formsynergy.com/api/"
                }
            },
            localMode: {
                status: "disabled",
                options: {
                    endPoint: '',   /* Local endpoint.*/
                    request: {},    /* Additions to the request, eg: if localizing with WordPress, nonce.*/
                }
            },
            offsetPositions: {
                up: 0.01,
                right: 0.01,
                down: 0.01,
                left: 0.01
            },
            prepare: {},
            e: {
                interact: new Event('interact')
            }
        };
        this._pub = {
            counters: {},
            options: {},
            cb: {},
            methods: {},
            addToMethod: {},
            customEvents: {},
            fire: {},
            timers: {},
            exit: {}
        };
        this._node = {};
        this._data = {};
        sessionStorage.removeItem('fs-interacting');
        return this;
    }

    prepare(formSynergy) {
        this._fs.prepare = () => {
            return formSynergy;
        };
        this.plugData();
        window.formSynergy = false;
        return this;
    }

    prepared(n, i = 'count') {
        if ('count' == i && this._fs.prepare()[n]) {
            return this._fs.prepare()[n].length;
        }
        else if ('all' == i && this._fs.prepare()[n]) {
            return this._fs.prepare()[n];
        }
        else if (i && this._fs.prepare()[n] && this._fs.prepare()[n][i]) {
            return this._fs.prepare()[n][i];
        }
        else {
            return false;
        }
    }

    fsl() {
        if (this._fs.prepare().formsynergy_api) {
            sessionStorage.setItem('fsl', this._fs.prepare().formsynergy_api);
        }
    }

    plugData() {
        this.swapModes();
        this.heartBeat(true);
        this.setLocalMode(true);
        this.offsetPositions(true);
        this.fsl();
    }

    /**
     * Will retrieve the site Id
     */
    siteId() {
        let tags = document.getElementsByTagName("meta");
        for (let meta of tags) {
            if ("fs:siteid" == meta.getAttribute("name")) {
                return meta.getAttribute("content");
            }
        }
    }

    /**
     * Set local mode options.
     */
    setLocalMode(options) {
        if (options && 'object' != typeof options && this._fs.prepare().localmode) {
            options = this._fs.prepare().localmode;
        }
        this._fs.localMode = {
            options: options,
            status: "enabled"
        };
        return this;
    }

    /**
     * Will automatically swap modes when connection problems are encountered.
     * NOTE: heartBeat must be enabled.
     */
    swapModes(defaultMode = true) {
        if ('function' == typeof this._fs.prepare && this._fs.prepare().debug) {
            this._fs.activeMode = 'localMode';
            return;
        }
        this._fs.activeMode = !defaultMode && 'enabled' == this._fs.localMode.status && this._fs.localMode.options ?
            'localMode' :
            'defaultMode';
        return this;
    }

    /**
     * Will return active mode.
     */
    getMode() {
        return this._fs[this._fs.activeMode].options;
    }

    /**
     * Provides the ability to optimize interactions with positions and elements.
     */
    offsetPositions(positions) {
        if (positions && 'object' != typeof positions && this._fs.prepare().offsetpositions) {
            positions = this._fs.prepare().offsetpositions;
        }
        for (let [k, v] of Object.entries(positions)) {
            this._fs.offsetPositions[k] = v;
        }
        return this;
    }

    capitalize(word) {
        return word.charAt(0).toUpperCase() + word.slice(1);
    }

    /**
     * When connecting modules, a connection can be set to another
     * module by specifying the module id, or to a callback.
     *
     * This method can be used to create a callback.
     * For more details: https://formsynergy.com/documentation/javascript-client/#fs-callback
     */
    createCallback(n, f) {
        this._pub.cb[n] = f;
        return this;
    }

    /**
     * This method is used to return a callback
     */
    callback(n, data, element) {
        if ('function' == typeof this._pub.cb[n] && 'undefined' != typeof this._pub.cb[n]) {
            var message = element.getElementsByClassName('fs-message');
            message[0].createElement = (json) => {
                this.createElement('object' == typeof json ? json : JSON.parse(json)).render().appendTo(message[0], true);
            }
            message[0].contents = (text) => {
                message[0].innerText = this.escapeHtml(text);
            }
            this._pub.cb[n](data, message[0]);
        }
    }

    /**
     * Used to add contents
     */
    escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerText;
    }

    /**
     * Render HTML using virtual DOM.
     *
     */
    createElement(data) {
        this._data = this._createElement(data);
        return this;
    }

    /**
     * Create an element
     */
    _createElement({
        tagName,
        attrs = {},
        content = '',
        data = {},
        children = []
    } = {}) {
        return {
            tagName,
            attrs,
            content,
            data,
            children
        };
    }

    /**
     * Create a child element.
     */
    createChild(child) {
        return 'content' == child.tagName || 'string' === typeof child ?
            document.createTextNode('content' == child.tagName ? child.content : child) :
            this._render(child);
    }

    /**
     * Render.
     */
    _render({
        tagName,
        attrs,
        content,        
        data,
        children
    }) {
        let $el = document.createElement(tagName);
        if (attrs) {
            for (let [k, v] of Object.entries(attrs)) {
                $el.setAttribute(k, v);
            }
        }
        if (data) {
            for (let [k, v] of Object.entries(data)) {
                $el.setAttribute('data-' + k, 'string' == typeof v ? v : JSON.stringify(v));
            }
        }
        if (children) {
            for (var i = 0; i < children.length; ++i) {
                $el.appendChild(this.createChild(children[i]));
            }
        }
        return $el;
    }

    /**
     * For chaining.
     */
    render() {
        this._node = this._render(this._data);
        return this;
    }

    /**
     * Clear a container.
     */
    clearContainer(el) {
        let element = document.querySelector(el);
        if( element ) {
            element.innerText = '';
        }
        
    }

    /**
     * Append or replace.
     */
    appendTo($target, $clear = false) {
        if ($clear) {
            $target.innerText = '';
        }
        $target.appendChild(this._node);
        return this._node;
    }

    /**
     * Find similar etags on a page.
     */
    getSimilar(name) {
        let _getSimilar = document.querySelectorAll(
            '[data-fs-etag*="' + name + '"]'
        ),
            el = [],
            i = 0;
        if (_getSimilar.length > 1) {
            for (i = 0; i < _getSimilar.length; ++i) {
                el[i] = _getSimilar[i].dataset.fsEtag;
            }
        }
        return el;
    }

    /**
     * Gather URL query string.
     */
    searchParams(applySearchParam = false) {
        var _searchParams = {},
            urlParams = new URLSearchParams(window.location.search);
        for (var each of urlParams) {
            if (applySearchParam) this.applySearchParam(each[0], each[1]);
            _searchParams[each[0]] = each[1];
        }
        return JSON.stringify(_searchParams);
    }

    /**
     * Add value from URL query string, to input fields if apply.
     */
    applySearchParam(name, value) {
        var paramInput = document.querySelector('[name="' + name + '"]');
        if (paramInput) {
            paramInput.value = value;
        }
    }

    /**
     * dateTime
     */
    dateTime() {
        let now = new Date();
        return (
            now.getFullYear() +
            "-" +
            (now.getMonth() + 1) +
            "-" +
            now.getDate() +
            " " +
            now.getHours() +
            ":" +
            now.getMinutes() +
            ":" +
            now.getSeconds()
        );
    }

    toKebab(string) {
        return string.replace(/([a-z])([A-Z])/g, '$1-$2').replace(/\s+/g, '-').toLowerCase();
    }

    /**
     * HearBeat
     */
    heartBeat(timeout) {
        if (timeout && this._fs.prepare().heartbeat && isNaN(parseFloat(timeout)) && !isFinite(timeout)) {
            timeout = this._fs.prepare().heartbeat;
        }
        else if (!this._fs.prepare().heartbeat) {
            return;
        }

        this._fs.heartbeat = timeout >= 7500 ? true : false;
        this._fs.hb = timeout >= 7500 ? timeout : 7500;
        this.hb('*', 'heartbeat');
        return this;
    }

    hb(el, event) {
        var that = this;
        this.delay('heartbeat' == event ? this._fs.hb : 100, () => {
            fetch("https://formsynergy.com/api/?o=" + window.btoa(encodeURIComponent(JSON.stringify({
                siteid: this.siteId(),
                api: "event",
                event: "set",
                set: {
                    type: event,
                    element: el,
                    page: that.searchParams(),
                    pip: window.btoa(window.innerWidth + ":" + window.innerHight),
                    url: document.URL,
                    title: document.title,
                    fsl: sessionStorage.getItem('fsl'),
                    interacting: sessionStorage.getItem('fs-interacting'),
                    stags: this.kTags('s'),
                    ktags: this.kTags('k'),
                    pTags: this.kTags('p')
                }
            }))), {
                method: "GET",
                headers: {
                    Accept: "application/json"
                }
            }).then(a => {
                this.swapModes(a.ok);
                if (a.ok) {
                    try {
                        return a.json();
                    }
                    catch ($x) {
                        return this.hb(el, event);
                    }
                }
            })
                .then(json => {
                    if (json && json.engagement) {
                        if (!sessionStorage.getItem('fs-interacting')) {
                            if( 'new' == json.engagement.render ) {
                                this.autoEngage(json);
                            }
                            var el = document.querySelector('[data-fs-etag="onclick' + json.etag + '"]');
                            this.bind(
                                el,
                                el.dataset.fsEtag,
                                'auto',
                                false
                            );
                            if (el) {
                                if (json.delay) {
                                    this.delay(json.delay, () => {
                                        if (!sessionStorage.getItem('fs-interacting')) {
                                            that.canDispatch(el);
                                        }
                                    });
                                }
                                else if (!sessionStorage.getItem('fs-interacting')) {
                                    that.canDispatch(el);
                                }
                            }
                        }
                    }
                    if ('heartbeat' == event) return this.hb(el, event);
                });
        });
    }

    autoEngage(data) {
        var trigger = document.createElement('div');
        trigger.dataset.fsEtag = 'onclick'+data.etag;
        trigger.dataset.fsClick = data.etag.replace(':', '');
        trigger.dataset.fsParams = JSON.stringify({trigger: data.trigger});
        trigger.classList = "display-none";
        document.getElementsByTagName("body")[0].appendChild(trigger);
        var opt = document.createElement('div'),
        options = {
            display: 'fixed',
            placement: 'centered',
            theme: 'white',
            size: 'lg'
        };
        opt.dataset.fsEl = data.el;
        opt.dataset.fsOpt = JSON.stringify(options)
        opt.classList = "display-none";
        document.getElementsByTagName("body")[0].appendChild(opt);
    }
    /**
     * Async delay
     */
    asyncDelay(timeout, f) {
        return new Promise(() => setTimeout(() => {
            f();
        }, timeout));
    }

    async delay(timeout, f) {
        return await this.asyncDelay(timeout, f);
    }

    validate_item(item, t = 400) {
        if (item.value.length > 2) {
            setTimeout(() => {
                this._validate_item(item);
            }, t);
        }
    }

    _validate_item(item) {
        var a = this;
        if (!['checkbox', 'radio'].includes(item.type)) {
            a.enable_form_submission(item.form);
        }
        if (!['checkbox', 'radio'].includes(item.type) && item.value.length < 2) {
            item.classList.add("is-invalid");
            item.classList.remove("is-valid");
            var validInput = false;
            validInput = document.getElementById(
                "validate-" + item.dataset.validationType
            );
            if (validInput) validInput.remove();
            return;
        }
        if ('localMode' == a._fs.activeMode) {
            a.enable_form_submission(item.form);
        }
        else if (!item.dataset.validationType) {
            return true;
        } else {
            fetch(a.getMode().endPoint, {
                method: "POST",
                headers: {
                    Accept: "application/json"
                },
                body: JSON.stringify({
                    api: "item",
                    item: "validate",
                    validate: {
                        type: item.dataset.validationType,
                        data: item.value
                    }
                })
            })
                .then(a => {
                    if (a.ok) {
                        return a.json();
                    }
                })
                .then(r => {
                    if ("success" == r.response) {
                        item.classList.remove("is-invalid");
                        item.classList.add("is-valid");
                        item.value = r.data;
                        var validInput = document.getElementById(
                            "validate-" + item.dataset.validationType
                        ), invalidFeedback;
                        if (!validInput) {
                            validInput = document.createElement("input");
                            validInput.type = "hidden";
                            validInput.name =
                                "validated[" + item.dataset.validationType + "]";
                            validInput.id = "validate-" + item.dataset.validationType;
                            validInput.value = "yes";
                            item.parentNode.appendChild(validInput);
                        }
                        invalidFeedback = item.parentNode.querySelector(
                            ".invalid-feedback"
                        );
                        if (invalidFeedback) invalidFeedback.remove();
                    } else {
                        item.classList.add("is-invalid");
                        var isInvalid = item.parentNode.querySelector(
                            ".invalid-feedback"
                        );
                        if (isInvalid) isInvalid.remove();
                        invalidFeedback = document.createElement("div");
                        invalidFeedback.classList = "invalid-feedback";
                        invalidFeedback.innerText = r.message;
                        item.parentNode.appendChild(invalidFeedback);
                    }
                    a.enable_form_submission(item.form);
                });
        }
    }

    enable_form_submission(form) {
        if (!form) {
            return;
        }
        let required = form.querySelectorAll(".validation-required");
        let validItems = form.querySelectorAll(".validation-required.is-valid"),
            submitButton = form.querySelector("[data-onsubmit]");
        if (required.length == validItems.length) {
            submitButton.disabled = false;
            return false;
        } else {
            submitButton.disabled = true;
            return true;
        }
    }


    inputChanges() {
        for (var a = this, b = document.querySelectorAll("input.validation-required"), c = 0; c < b.length; ++c) {
            b[c].addEventListener("change", function (d) {
                a.validate_item(d.target);
            });
        }
    }
    mesurText() {
        var a = this, 
            b = document.getElementById("fs-body"),
            d = b.querySelectorAll('input'),
            reSize = (e) => {
                var ss = parseInt( e.target.dataset.ftsize ),    
                    w = e.target.getBoundingClientRect().width,
                    size = ( 13 - ( (e.target.value.length * ss ) - w) /40 );
                return ( size >= 11 ? size : false);
            };

        for ( var t of d ) {
            t.addEventListener("keyup", function (e) {
            if( ! e.target.dataset.ftsize ) {
                e.target.dataset.ftsize = 13;
            }
            var newSize = reSize(e),
                label = b.querySelector('[for="'+e.target.id+'"]');
            if( newSize ) {
                e.target.dataset.ftsize;
                if( e.target.value.length > 13 ) {
                    label.classList.add('fade');
                }
                else {
                    label.classList.remove('fade');
                }
                e.target.setAttribute( 'style', 'font-size: ' + newSize + 'px' + ' !important');
            }
            });
        }
    }
    /**
     * Handles closing interaction.
     */
    close(obj) {
        var close = obj.interaction.querySelector(".close"),
            bcd = document.getElementById('fs-backdrop');
        if (close) {
            close.addEventListener("click", () => {
                obj.interaction.remove();
                if (bcd) bcd.remove();
                if (this._fs.bodyAddon) {
                    for (var className of this._fs.bodyAddon) {
                        document.getElementsByTagName("body")[0].classList.remove(className);
                    }
                }
                sessionStorage.removeItem('fs-interacting');
            });
        }
    }

    /**
     * Will retrieve display options,
     * such as position of an element,
     * placement of interaction,
     * the size and theme.
     */
    ops(element) {
        var _position = document.querySelector(
            '[data-fs-el="@' +
            JSON.parse(element.dataset.fsParams).trigger.moduleid +
            '"]'
        );

        if (!_position) {
            var div = document.createElement('div'),
                options = {
                    display: 'fixed',
                    placement: 'centered',
                    theme: 'white',
                    size: 'xl'
                };
            div.dataset.fsEl = '[data-fs-el="@' + JSON.parse(element.dataset.fsParams).trigger.moduleid + '"]';
            div.dataset.fsOpt = JSON.stringify(options)
            div.classList = "display-none";
            document.getElementsByTagName("body")[0].appendChild(div);
        }
        return _position ?
            JSON.parse(_position.dataset.fsOpt) : options;
    }

    /**
     * Find associated params.
     */
    findParams(etag, e) {
        let fsEtag = e.target.closest('[data-fs-etag="' + etag + '"]');
        if (fsEtag && fsEtag.dataset.fsParams) {
            return JSON.parse(fsEtag.dataset.fsParams);
        }
        return {};
    }

    _counter(name, value = 'check') {
        var count = sessionStorage.getItem('counter-' + name);
        if ('check' == value) {
            return count ? true : false;
        }
        if ('get' == value) {
            return count ? parseInt(count) : false;
        }
        if ('boolean' == typeof value) {
            count = sessionStorage.setItem('counter-' + name, 1);
        }
        else if ('number' == typeof value) {
            count = sessionStorage.setItem('counter-' + name, value);
        }
        else if ('delete' == value) {
            sessionStorage.removeItem('counter-' + name)
        }
        return count;
    }

    counter(name = false, action = false, value = 1) {
        if (!action || !name || 'string' != typeof name || 'string' != typeof action) {
            return false;
        }

        if (value && 'global' != value && 'number' != typeof value) {
            console.log('only numbers are permitted when setting counter values.');
            return false;
        }
        switch (action) {
            case 'create':
                this._pub.counters[name] = 0;
                if ('global' == value) {
                    this._counter(name, true);
                }
                break;
            case 'add':
                var c = this._counter(name, 'get'), n;
                if (c) {
                    n = (c + 1);
                    this._counter(name, n);
                    this._pub.counters[name] = n;
                    return n;
                }
                else {
                    n = (this._pub.counters[name] + 1);
                    this._pub.counters[name] = n;
                    return n;
                }
                break;
            case 'update':
                this._pub.counters[name] = value;
                if (this._counter(name, 'get')) {
                    this._counter(name, value);
                }
                return value;
                break;
            case 'subtract':
                var c = this._counter(name, 'get'), n;
                if (c) {
                    n = (c - 1);
                    this._counter(name, n);
                    this._pub.counters[name] = n;
                    return n;
                }
                else {
                    n = (this._pub.counters[name] - 1);
                    this._pub.counters[name] = n;
                    return n;
                }
                break;
            case 'check':
                if (this._counter(name, 'check')) {
                    return this._counter(name);
                }
                return this._pub.counters[name] ? true : false;
                break;
            case 'get':
                if (this._counter(name, 'get')) {
                    return this._counter(name, 'get');
                }
                else {
                    return this._pub.counters[name] ? parseInt(this._pub.counters[name]) : false;
                }
                break;
            case 'delete':
                if (this._counter(name, 'check')) {
                    return this._counter(name, 'delete');
                }
                delete this._pub.counters[name];
                break;
        }
        return this;
    }

    _option(name, value = 'no') {
        var option = sessionStorage.getItem('option-' + name);
        if (!option) {
            option = sessionStorage.setItem('option-' + name, value);
        }
        else if ('delete' == value) {
            sessionStorage.removeItem('option-' + name)
        }
        else if ('boolean' == typeof value) {
            option = sessionStorage.setItem('option-' + name, value ? 'yes' : 'no');
        }
        return 'no' != option ? true : false;
    }

    option(name = false, action = false, value = false) {
        if (!action || !name || 'string' != typeof name || 'string' != typeof action) {
            return false;
        }
        if (value && 'number' == typeof value || 'boolean' == typeof value) {
            switch (action) {
                case 'create':
                    this._pub.options[name] = false;
                    if (value) {
                        this._option(name);
                    }
                    break;
                case 'update':
                    this._pub.options[name] = value;
                    if (this._option(name)) {
                        this._option(name, value)
                    }
                    break;
                case 'get':
                    if (this._option(name)) {
                        return this._option(name);
                    }
                    return this._pub.options[name];
                    break;
                case 'delete':
                    if (this._option(name)) {
                        return this._option(name, 'delete');
                    }
                    delete this._pub.options[name];
                    break;
            }
        }
        else {
            console.log('Setting ' + typeof value + ',  only boolean and numbers are permitted when setting option values.');
            return false;
        }
        return this;
    }

    /**
     * Create method
     */
    createMethod(n, f) {
        this._pub.methods[n] = f;
        return this;
    }

    runMethods() {
        // eslint-disable-next-line no-unused-vars
        for (let [k, v] of Object.entries(this._pub.methods)) {
            if (typeof v == "function") {
                v(k);
            }
        }
    }

    /**
     * Handles load events
     */
    onLoad(etag) {
        var a = this;
        if (etag.dataset.fsEtag.includes('onload')) {
            a.canDispatch(etag);
            etag.dataset.fsEtag = etag.dataset.fsEtag.replace('onload', 'load');
        }
    }

    /**
     * Handles exit events
     */
    /*
    onExit(etag) {
        var fsEtag = etag.dataset.fsEtag,
            pathname = window.location.pathname.replace(/^\/|\/$/g, '');
            if (fsEtag.includes('onexit') && fsEtag == 'onexit:' + pathname && !sessionStorage.getItem('onexit:' + pathname)) {
                sessionStorage.setItem('onexit:' + pathname, 'active');
            }
    }

    whenExit() {
        var a = this,
            pathname = window.location.pathname.replace(/^\/|\/$/g, ''),
            trigger = document.querySelector('[data-fs-etag="onexit:' + pathname + '"]');
        document.addEventListener('click', (e) => {
            var sess = sessionStorage.getItem('onexit:' + pathname);
            if (trigger && sess && e.target.href && ! e.target.href.includes(window.location.hostname)) {
                e.preventDefault();
                if (trigger) {
                    a.canDispatch(trigger);
                }
            }
        });
        window.addEventListener('mousemove', (e) => {
            var interacting = sessionStorage.getItem('fs-interacting');
            if( !interacting && e.clientY < 10 ) {
                a.canDispatch(trigger);
            }
        });
        window.onbeforeunload = function(e) {
            trigger.click();
        }
    }
    */
    /**
     * Handles exit events
     */
	onExit(etag) {
		var fsEtag = etag.dataset.fsEtag,
			pathname = window.location.pathname.replace(/^\/|\/$/g, '');
		if (fsEtag.includes('onexit') && fsEtag == 'onexit:' + pathname && !sessionStorage.getItem('onexit:' + pathname)) {
			this._pub.exit['onexit:' + pathname] = 'active';
		}
		return this;
	}

	whenExit() {
		var a = this,
			pathname = window.location.pathname.replace(/^\/|\/$/g, ''),
			trigger = document.querySelector('[data-fs-etag="onexit:' + pathname + '"]'),
            exit = this._pub.exit['onexit:' + pathname],
            dispatched = false;
			if( ! exit && ! trigger ) {
				return;
			}
			document.addEventListener('mousemove', (e) => {
				switch( true ) {
					case (e.clientX <= 1 ) :
					case (e.clientX >= ( window.innerWidth - 1 ) ) :
					case (e.clientY <= 1 ) :
					case (e.clientY >= ( window.innerHeight - 1 ) ) :
                        if( ! dispatched ) a.canDispatch(trigger);
                        dispatched = true;
                        setTimeout( () => {
                            dispatched = false;
                        }, 1000);
					break;
				}
			});
	}

    /**
     * Handles scroll events
     */
    onScroll(etag) {
        let fsEtag = etag.dataset.fsEtag,
            tagName = fsEtag.split(':')[1];
        if (fsEtag.includes('onscroll')) {
            window.addEventListener('scroll', () => {
                let watch = document.querySelector('[data-fs-scroll="' + tagName + '"]'),
                    pos = watch.getBoundingClientRect();
                if (pos && pos.y < 0 && !etag.dataset.active) {
                    this.isMethodOrEvent(etag, 'ready');
                    etag.dataset.fsEtag = fsEtag.replace('onscroll', 'scroll');
                }
            });
        }
    }

    /**
     * Handles click events
     */
    onClick(etag) {
        this.isMethodOrEvent(etag, 'ready');
    }

    /**
     * Handles revisit events
     */
    onRevisit(etag) {
        let a = this,
            fsEtag = etag.dataset.fsEtag,
            count = parseInt(etag.dataset.fsCount),
            stop = etag.dataset.fsStop,
            counter = a.counter(fsEtag, 'check');
        if (!counter) {
            a.counter(fsEtag, 'create', 'global');
        }
        else if (counter && a.counter(fsEtag, 'add') >= count) {
            a.isMethodOrEvent(etag, 'ready');
            if (!stop) {
                a.counter(fsEtag, 'delete');
            }
        }
    }

    /**
     * Handles wait events
     */
    onWait(etag) {
        let a = this,
            count = parseInt(etag.dataset.fsCount);
        setTimeout(() => {
            a.isMethodOrEvent(etag, 'ready');
        }, 1000 * count);
    }

    /**
     * Handles change events
     */
    onChange(etag) {
        let a = this,
            fsEtag = etag.dataset.fsEtag,
            ev = fsEtag.split(':')[0].replace('on', ''),
            tagName = fsEtag.split(':')[1],
            el = document.querySelector('[data-fs-' + ev + '="' + tagName + '"]');
        if (el) {
            var observer = new MutationObserver(function (e) {
                a.isMethodOrEvent(etag, 'ready');
            });
            observer.observe(el, { attributes: true });
        }
    }

    /**
     * Will fire an event
     */
    fire(k) {
        this.canDispatch(document.querySelector(k));
    }

    /**
     * Create custom events handlers.
     */
    event(n, f) {
        this.addCustomEvent(n, f);
        return n;
    }

    isMethodOrEvent(etag, status) {
        var a = this,
            addTo = etag.dataset.fsAddto ? JSON.parse(etag.dataset.fsAddto) : false;
        if (addTo) {
            a.updateMethodEvents(etag, status);
            var type = a.fireWhenReady(a.toKebab(addTo.method));
            if ('method-is-ready' == type.status) {
                a.canDispatch(type.etag);
            }
            else if ('is-event' == type.status) {
                a.canDispatch(etag);
            }
            return;
        }
        a.canDispatch(etag);
    }

    canDispatch(el) {
        var a = this,
            isInteracting = sessionStorage.getItem('fs-interacting'),
            etag = el.dataset.fsEtag.split(':'),
            element = document.querySelector('[data-fs-'+etag[0].replace('on','')+'="'+etag[1]+'"]');
        if( element && element.dataset && element.dataset.fsOnsuccess ) {
            element.classList.add(element.dataset.fsOnsuccess);
        } 
        if( ! isInteracting ) {
            el.dispatchEvent(a._fs.e.interact);
        }
    }

    updateMethodEvents(etag, status) {
        var a = this,
            fsEtag = etag.dataset.fsEtag,
            addTo = JSON.parse(etag.dataset.fsAddto);
        if (a._pub.addToMethod[a.toKebab(addTo.method)]) {
            a._pub.addToMethod[a.toKebab(addTo.method)][fsEtag] = status;
        }
        return this;
    }

    assignToMethods() {
        var a = this,
            addToMethod = document.querySelectorAll('[data-fs-addto]');
        if (!addToMethod) {
            return;
        }
        for (var each of addToMethod) {
            var addTo = JSON.parse(each.dataset.fsAddto),
                fsEtag = each.dataset.fsEtag;
            if (!a._pub.addToMethod[a.toKebab(addTo.method)]) {
                a._pub.addToMethod[a.toKebab(addTo.method)] = {};
            }
            a._pub.addToMethod[a.toKebab(addTo.method)][fsEtag] = 'waiting';
        }
    }

    fireWhenReady(methodName) {
        var a = this,
            countEvents = 0,
            ready = 0;
        if (a._pub.addToMethod[a.toKebab(methodName)]) {
            for (let [k, v] of Object.entries(a._pub.addToMethod[a.toKebab(methodName)])) {
                countEvents++;
                if ('ready' == v) {
                    ready++;
                }
            }
            var etag = document.querySelector('[data-fs-etag="method:' + a.toKebab(methodName) + '"]');
            if (!etag) {
                return {
                    status: 'has-no-event-method'
                };
            }
            return {
                status: countEvents <= ready ? 'method-is-ready' : 'method-is-not-ready',
                logic: {
                    count: countEvents,
                    ready: ready
                },
                events: a._pub.addToMethod[a.toKebab(methodName)],
                etag: etag
            };
        }
        return {
            status: 'is-event'
        };
    }

    addCustomEvent(n, f) {
        this._pub.customEvents[n] = f;
        return this;
    }

    runCustomEvent(n, e, el, obj) {
        return obj[n] ? obj[n](e, el, obj) : false;
    }

    customEvent(n, etag, e, el, obj) {
        let methods = this._pub.methods, a = this;
        if (obj[n] && typeof obj[n] == "function") {
            el.interaction = function (action) {
                if ('fire' == action && etag.dataset.fsEtag.includes('on')) {
                    a.canDispatch(etag);
                    etag.dataset.fsEtag = etag.dataset.fsEtag.replace('on', '');
                }
            }
            el.fire = function (action = false) {
                if (action && etag.dataset.fsEtag.includes('on')) {
                    a.canDispatch(etag);
                }
            }
            el.fireMethod = function (method) {
                var etag = document.querySelector('[data-fs-etag="method:' + a.toKebab(method) + '"]');
                if (etag && methods[method] && 'function' == typeof methods[method] && methods[method]()) {
                    a.canDispatch(etag);
                }
            }
        }
        return a.runCustomEvent(n, e, el, obj);
    }

    /**
     * Create and handle custom events
     */
    onCustomEvent(etag) {
        let fsEtag = etag.dataset.fsEtag,
            obj = this._pub.customEvents,
            data = fsEtag.split(':'),
            eType = data[0].replace('on', ''),
            watch = data[1],
            el = document.querySelector('[data-fs-' + eType + '="' + watch + '"]');
        if (el) {
            el.addEventListener(eType, (e) => {
                if (this['on' + this.capitalize(e.type)]) {
                    return this['on' + this.capitalize(e.type)](etag);
                }
                else if (eType == e.type && e.isTrusted && etag.dataset.fsEtag == 'on' + e.type + ':' + data[1]) {
                    return this.customEvent('on' + this.capitalize(e.type) + ':' + data[1], etag, e, el, obj);
                }
            });
        }
    }

    disableSequence() {
        var r = {};
        for (let f of Object.getOwnPropertyNames(Object.getPrototypeOf(this))) {
            r[f] = () => { };
        }
        return r;
    }

    eventSequence(n) {
        return document.querySelector('[data-fs-sequence="' + n + '"]')
            ? this
            : this.disableSequence();
    }

    /**
     * Handles all other events using related tag.
     */
    duringEvent(etag) {
        let fsEtag = etag.dataset.fsEtag,
            data = fsEtag.split(':'),
            fn = data[0];
        if ('onsubmit' == fn || 'ondismiss' == fn) {
            return;
        }
        switch (fn) {
            case 'onscroll':
                return this.onScroll(etag);
                break;
            case 'onrevisit':
                return this.onRevisit(etag);
                break;
            case 'onexit':
                return this.onExit(etag);
                break;
            case 'onload':
                return this.onLoad(etag);
                break;
            case 'onwait':
                return this.onWait(etag);
                break;
            case 'onchange':
                return this.onChange(etag);
                break;
            default:
                this.onCustomEvent(etag);
                break;
        }
    }

    /**
     * Handles submissions.
     */
    onSubmit(_o) {
        this.bind(
            '[data-fs-etag="onsubmit' + _o.response.etag + '"]',
            "onsubmit" + _o.response.etag,
            _o,
            false
        );
        return this;
    }

    /**
     * Handles dismissals.
     */
    onDismiss(_o) {
        this.bind(
            '[data-fs-etag="ondismiss' + _o.response.etag + '"]',
            "ondismiss" + _o.response.etag,
            _o,
            false
        );
        return this;
    }

    count(etag, count = false) {
        if (!count) return true;
        var ss = sessionStorage.getItem(etag);
        if (!ss) ss = 1;
        if (count <= ss) {
            return true;
        }
        else {
            sessionStorage.setItem(etag, parseInt(ss) + 1);
            return false;
        }
    }

    kTags(k = 'k') {
        let tags = document.querySelectorAll('[data-fs-'+k+'tag]'),
            tags_ = [];
        if (tags) {
            for (var i = 0; i < tags.length; ++i) {
                if (tags[i].dataset['fs'+k.toUpperCase()+'tag'].includes(',')) {
                    var sp = tags[i].dataset['fs'+k.toUpperCase()+'tag'].split(',');
                    for (var each of sp) {
                        tags_.push(each.trim());
                    }
                }
                else {
                    tags_.push(tags[i].dataset['fs'+k.toUpperCase()+'tag'].trim());
                }
            }
        }
        return tags_;
    }

    eTags() {
        let etags = document.querySelectorAll("[data-fs-etag]"),
            i;
        for (i = 0; i < etags.length; ++i) {
            this.bind(etags[i], etags[i].dataset.fsEtag, false, false);
            this.duringEvent(etags[i]);
        }
        this.whenExit();
    }

    /**
     * Will engage events.
     */
    engage() {
        let fsContainer = document.querySelector("#fs-container");
        if (!fsContainer) {
            var div = document.createElement("div");
            div.id = "fs-container";
            div.classList = "display-none";
            document.getElementsByTagName("body")[0].appendChild(div);
        }
        this.runMethods();
        this.assignToMethods();
        this.eTags();
        this.delay(1500, () => {
            this.hb('*', 'load');
        });
        return this;
    }

    /**
     * Prevents forms from submitting on enter.
     */
    preventDirectSubmissions() {
        let interactiveForms = document.querySelectorAll('form.apip-submit-interaction');
        if (interactiveForms) {
            for (var i = 0; i < interactiveForms.length; ++i) {
                interactiveForms[i].addEventListener('keypress', (e) => {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        this.validate_item(e.target);
                        var btn = e.target.form.querySelector('[data-onsubmit]');
                        if (btn) {
                            this.delay(150, () => {
                                this.canDispatch(btn);
                            });
                        }
                    }
                });
            }
        }
    }

    /**
     * Binds everything together, at the exception of validation and heartBeat.
     */
    bind(el, etag, options, inline = true, obj) {
        let element = typeof el == "string" ? document.querySelector(el) : el,
            that = this,
            ev = etag.split(":")[0],
            dispatch = 'onsubmit' == ev || 'ondismiss' == ev
                ? 'click'
                : 'interact';
        if (!element) return;
        element.addEventListener(dispatch, (e) => {
            var params = this.findParams(etag, e),
                trigger = params.trigger;
            if (!trigger && options && options.trigger) {
                trigger = options.trigger;
            }
            if (!trigger) {
                return;
            }
            let data = {
                siteid: that.siteId(),
                api: "onsubmit" == ev || "ondismiss" == ev ? "interaction" : "event",
                event: "set",
                interaction: "set",
                set: {
                    action_type: ev,
                    rm: options && options.response ? options.response.id : false,
                    page: that.searchParams(),
                    pip: window.btoa(window.innerWidth + ":" + window.innerHight),
                    url: document.URL,
                    title: document.title,
                    trigger: options && options.trigger ? options.trigger : trigger,
                    tag: etag,
                    target: inline ? el : '[data-fs-el="@' + trigger.moduleid + '"]',
                    rel: params && params.rel ? params.rel : false,
                    className: params && params.className ? params.className : false,
                    delay: params && params.delay ? params.delay : false,
                    similar: that.getSimilar(etag.split(":")[1]),
                    opt: options && options.opt ? options.opt : that.ops(element),
                    fsl: sessionStorage.getItem('fsl')
                }
            };
            if ("onsubmit" == ev || "ondismiss" == ev) {
                let form = element.form;
                if ("onsubmit" == ev) {
                    var g = form.getElementsByClassName("validation-required")
                        , n = form.getElementsByClassName("is-valid");
                    if (0 < g.length && g != n)
                        for (n = 0; n < g.length; ++n)
                            g[n].classList.contains("is-valid") || g[n].value || g[n].classList.add("is-invalid");
                    if ((g = form.getElementsByClassName("is-invalid")) && 0 < g.length)
                        return !1
                }
                if (form) {
                    let formData = new FormData(form);
                    if ('localMode' == this._fs.activeMode) {
                        data.set.form = {};
                    }
                    for (let p of formData.entries()) {
                        if ('localMode' == this._fs.activeMode && p[0].includes('data.')) {
                            data.set.form[p[0].replace('data.', '')] = p[1];
                        } else {
                            data.set[p[0]] = p[1];
                        }
                    }
                    if (this.getMode().request) {
                        for (let [k, v] of Object.entries(this.getMode().request)) {
                            data.set[k] = v;
                        }
                    }
                    if (form.classList.contains('embed')) {
                        data.set.opt = {
                            display: 'embed'
                        }
                    }
                }
            }
            fetch(this.getMode().endPoint, {
                method: "POST",
                headers: {
                    Accept: "application/json"
                },
                body: JSON.stringify(data)
            })
                .then(a => {
                    if (a.ok) {
                        return a.json();
                    }
                })
                .then(r => {
                    if (!r) {
                        return;
                    }
                    if (r.callback) {
                        options.interaction.remove();
                        this.callback(r.callback.replace('callback'), r[r.callback], options.interaction);
                    } else if ("function" == typeof options) {
                        options(r);
                    } else {
                        this.handler({
                            response: r,
                            data: data,
                            event: e,
                            obj: options ? options : false,
                            o: obj ? obj : false
                        });
                    }
                });
            e.preventDefault();
        }, true);
    }

    /**
     * Will handle responses, requests and manage interactions.
     */
    handler(_o) {
        var that = this,
            position;
        if ("DomObject" == _o.response.dataType) {
            var container = _o.response.opt.display && 'embed' == _o.response.opt.display
                ? _o.response.target
                : '#fs-container';
            this.clearContainer(container);
            var createModule = document.createElement("div");
            createModule.id = _o.response.id;
            createModule.classList.add('fs-opw');
            if (_o.response.opt.placement) {
                createModule.classList.add('placement-' + _o.response.opt.placement);
            }
            if (_o.response.opt.addon) {
                createModule.classList.add("custom-class-added");
                createModule.classList.add(_o.response.opt.addon);
            }
            if (_o.response.opt.display) {
                createModule.classList.add("fs-display-" + _o.response.opt.display);
            }
            if (_o.response.opt.backgroundColor) {
                createModule.style.backgroundColor = _o.response.opt.backgroundColor;
            }
            if (_o.response.opt.top) {
                createModule.style.top = _o.response.opt.top;
            }
            if (_o.data.set.className) {
                createModule.classList.add(_o.data.set.className);
                createModule.classList.add("custom-class-added");
            }
            createModule.classList.add(
                _o.response.opt.size ?
                    "fs-opw-" + _o.response.opt.size :
                    "fs-opw-md"
            );
            createModule.classList.add(
                _o.response.opt.theme ?
                    "fs-" + _o.response.opt.theme :
                    "fs-white"
            );
            if ('embed' == _o.response.opt.display) {
                createModule.classList.add('active');
                var embed = document.querySelector(_o.response.target);
                if(! embed ) {
                    var embed = document.querySelector('[data-fs-el="'+_o.response.el+'"]');
                }
                embed.appendChild(createModule);
                that.createElement(_o.response.DomObject).render().appendTo(createModule);
                that.interact(_o, createModule, true);
                embed.querySelector('form').classList.add('embed');
            }
            else {
                position = sessionStorage.getItem("position");
                if (position) {
                    position = JSON.parse(position);
                    for (var i = 0; i < position.length; ++i) {
                        createModule.style[position[i][0]] = position[i][1];
                    }
                    createModule.style.zIndex = 1000;
                } else {
                    let el = document
                        .querySelector(_o.response.target),
                        position = el
                            ? el.getBoundingClientRect()
                            : false,
                        rect = createModule.getBoundingClientRect(),
                        p = that.getPosition(position, rect, _o.response.opt.placement ? _o.response.opt.placement : 'centered');
                    createModule.style.opacity = 0;
                    createModule.style.zIndex = 1000;
                    createModule.style.transform =
                        "translate3d(" + Math.round(p.x) + "px, " + Math.round(p.y) + "px, 0px)";
                    createModule.style.top = _o.response.opt.top ? _o.response.opt.top : "0px";
                    if (_o.response.opt.backgroundColor) {
                        createModule.style.backgroundColor = _o.response.opt.backgroundColor;
                    }
                    createModule.style.left = "0px";
                    createModule.style.willChange = "transform";
                }
                document.getElementById("fs-container").appendChild(createModule);
                that.createElement(_o.response.DomObject).render().appendTo(createModule);
                that.interact(_o, createModule);
                sessionStorage.setItem('fs-interacting', 'true');
            }
            if (_o.response.fsMessage) {
                this.complete(_o);
            }
        } else if (
            _o.obj &&
            _o.obj.interaction &&
            "fsMessage" == _o.response.dataType ||
            "elementId" == _o.response.dataType
        ) {
            this.complete(_o);
        }
        else if (_o.response.close) {
            this.complete(_o);
        }
    }

    setMargin(el, opt) {
        if( 'fixed' != opt.display) {
            return;
        }
        for (let [k, v] of Object.entries(opt)) {
            if( ! v ) {
                return;
            }
            switch(k) {
                case 'up' : el.style.marginBottom = v + 'px'; break;
                case 'down' : el.style.marginTop = v + 'px'; break;
                case 'left' : el.style.marginRight = v + 'px'; break;
                case 'right' : el.style.marginLeft = v + 'px'; break;
            }
        }
    }

    /**
     * Will display feedback, complete and destroy an interaction.
     */
    complete(_o) {
        var that = this,
            fsMessageElement = _o.obj.interaction.getElementsByClassName(
                "fs-message"
            );
        _o.obj.interaction.getElementsByClassName("fs-opw__arrow")[0].remove();
        var message;
        if ('default' == _o.response.fsMessage) {
            fsMessageElement[0].innerText = '';
            var response = document.createElement("div");
            fsMessageElement[0].appendChild(response);
            that.createElement(JSON.parse('{ "tagName": "div", "children": [{ "tagName": "span", "attrs": { "class": "lead text-secondary fs-animate peak peak-s" }, "children": [{ "tagName": "content", "content": " Thank You." }] }] }')).render().appendTo(fsMessageElement[0]);
        }
        else if( 'object' == typeof _o.response.fsMessage ) {
            fsMessageElement[0].innerText = '';
            var response = document.createElement("div");
            fsMessageElement[0].appendChild(response);
            that.createElement(_o.response.fsMessage).render().appendTo(fsMessageElement[0]);
        }
        else {
            if ("elementId" == _o.response.dataType) {
                var messageEl = document.getElementById(_o.response.elementId);
                message = messageEl.innerText;
            }
            else {
                message = _o.response.fsMessage;
            }
            fsMessageElement[0].innerText = message;
            var el = document
                .querySelector(_o.response.target),
                position = el ? el.getBoundingClientRect() : false,
                rect = _o.obj.interaction.getBoundingClientRect(),
                p = that.getPosition(position, rect, _o);
            _o.obj.interaction.style.transform = "translate3d(" + Math.round(p.x) + ", " + Math.round(p.y) + ", 0px)";
        }
        this.delay(2500, () => {
            sessionStorage.removeItem('fs-interacting');
            _o.obj.interaction.remove();
            _o.obj = {};
        });
    }

    /**
     * Will reposition interaction, in order to maintain the best position.
     */
    interact(_o, createdModule, embed = false) {
        var that = this;
        this.delay(150, () => {
            var interaction = embed ? createdModule : that.applyPosition(_o, createdModule);
            _o.interaction = interaction;
            that.preventDirectSubmissions();
            that.onSubmit(_o);
            that.onDismiss(_o);
            that.close(_o);
            that.inputChanges();
            that.mesurText();
            that.searchParams(true);
            that.runMethods();
            if (_o.response.opt.bodyAddon) {
                this._fs.bodyAddon.push(_o.response.opt.bodyAddon);
                document.getElementsByTagName("body")[0].classList.add(_o.response.opt.bodyAddon);
            }
        });
    }

    /**
     * Will apply the position.
     */
    applyPosition(obj, createdModule) {
        var that = this,
            p,
            el = document.querySelector(obj.data.set.target),
            rect = createdModule.getBoundingClientRect(),
            position = el
                ? el.getBoundingClientRect()
                : false;
        if (!position) {
            p = that.getPosition(position, rect, obj.response.opt.placement ? obj.response.opt.placement : 'centered');
            createdModule.style.position = "fixed";
        }
        else if (window.innerWidth < 1000) {
            p = that.getPosition(position, rect, obj.response.opt.placement ? obj.response.opt.placement : 'centered');
            createdModule.style.position = "fixed";
        } else {
            p = that.getPosition(position, rect, obj);
            createdModule.style.position = "absolute";
        }
        if (!p) {
            createdModule.classList.add("active");
            createdModule.style.opacity = 1;
            return createdModule;
        }
        createdModule.style.transform =
            "translate3d(" + Math.round(p.x) + "px, " + Math.round(p.y) + "px, 0px)";
        createdModule.style.top = obj.response.opt.top ? obj.response.opt.top : "0px";
        that.setMargin(createdModule, obj.response.opt);
        if (obj.response.opt.backgroundColor) {
            createdModule.style.backgroundColor = obj.response.opt.backgroundColor;
        }
        createdModule.style.left = "0px";
        createdModule.classList.add("active");
        createdModule.position = p;
        sessionStorage.setItem(
            "position",
            JSON.stringify([
                ["position", createdModule.style.position],
                ["transform", createdModule.style.transform + ""],
                ["boxShadow", '0px 0px 80px rgba(255,255,255,0.3)'],
                ["top", createdModule.style.top],
                ["left", createdModule.style.left],
            ])
        );
        return createdModule;
    }

    /**
     * Handle arrow class and remove offScreen
     */
    arrowClass(className) {
        var classes = '';
        for (var name of className) {
            if (!name.includes('placement')) classes += ' ' + name;
        }
        return classes;
    }

    /**
     * If an interaction will display off screen return true.
     */
    isOffScreenY(position, obj, rect) {
        if ('string' == typeof obj && 'centered' == obj) {
            position = (((window.innerHeight - rect.height) - position) / 2);
        }
        else if (window.scrollY > position) {
            position = (position + window.scrollY);
            if( 'object' == typeof obj ) {
                var el = document.getElementById(obj.response.id);
                el.classList = this.arrowClass(el.classList);
            }
        }
        return position;
    }

    isOffScreenX(position, obj, rect) {
        if (window.innerWidth < 500) {
            position = (((window.innerWidth - rect.width) - position) / 2);
        }
        else if (window.scrollX > position) {
            position = (position - window.scrollX);
        }
        return position;
    }

    /**
     * Will calculate the placement of element.
     */
    getPosition(pos, rect, obj) {
        var placement = 'object' == typeof obj ? obj.data.set.opt.placement : obj,
            x,
            y,
            opt = obj.data
                && obj.data.set.opt
                ? obj.data.set.opt
                : {},
            _opt = {
                up: opt.up ? parseInt(opt.up) : 0.01,
                down: opt.down ? parseInt(opt.down) : 0.01,
                left: opt.right ? parseInt(opt.right) : 0.01,
                right: opt.left ? parseInt(opt.left) : 0.01
            },
            _ofp = {
                up: parseInt(this._fs.offsetPositions.up),
                down: parseInt(this._fs.offsetPositions.down),
                left: parseInt(this._fs.offsetPositions.left),
                right: parseInt(this._fs.offsetPositions.right)
            };
        if (!placement) return;
        if (rect.height < 250) rect.height = 250;
        if (pos.height < 100) pos.height = 100;
        switch (true) {
            case placement.includes("left"):
                x = pos.x - rect.width + _opt.left - _opt.right + _ofp.left - _ofp.right;
                y = window.scrollY + pos.y - this.adjustments(placement, rect.height, pos.height) + _opt.down - _opt.up + _ofp.down - _ofp.up;
                break;
            case placement.includes("top"):
                x = pos.x - this.adjustments(placement, rect.width, pos.width) + _opt.left - _opt.right + _ofp.left - _ofp.right;
                y = window.scrollY + pos.y - rect.height * 0.6 - pos.height * 0.5 + _opt.down - _opt.up + _ofp.down - _ofp.up;
                break;
            case placement.includes("bottom"):
                x = pos.x - this.adjustments(placement, rect.width, pos.width) + _opt.left - _opt.right + _ofp.left - _ofp.right;
                y = window.scrollY + pos.y + pos.height + _opt.down - _opt.up + _ofp.down - _ofp.up;
                break;
            case placement.includes("right"):
                x = pos.x + pos.width + _opt.left - _opt.right + _ofp.left - _ofp.right;
                y = window.scrollY + pos.y - this.adjustments(placement, rect.height, pos.height) + _opt.down - _opt.up + _ofp.down - _ofp.up;
                break;

            case placement.includes("centered"):
                x = this.adjustments(placement, window.innerWidth, rect.width, true) + _opt.left - _opt.right + _ofp.left - _ofp.right;
                y = window.innerHeight * 0.5 - rect.height * 0.5 + _opt.down - _opt.up + _ofp.down - _ofp.up;
                break;

            case placement.includes("upper"):
                x = this.adjustments(placement, window.innerWidth, rect.width, true) + _opt.left - _opt.right + _ofp.left - _ofp.right;
                y = window.innerHeight * 0.25 - rect.height * 0.5 + _opt.down - _opt.up + _ofp.down - _ofp.up;
                break;

            case placement.includes("lower"):
                x = this.adjustments(placement, window.innerWidth, rect.width, true) + _opt.left - _opt.right + _ofp.left - _ofp.right;
                y = window.innerHeight * 0.75 - rect.height * 0.6 + _opt.down - _opt.up + _ofp.down - _ofp.up;
                break;
        }
        return {
            x: this.isOffScreenX(x, obj, rect),
            y: this.isOffScreenY(y, obj, rect)
        };
    }
    adjustments(placement, v, z, fixed = false) {
        switch (true) {
            case placement.includes("start"):
                return fixed ? (v * 0.17) - (z * 0.33333) : (v * 0.25) - (z * 0.33333);
                // eslint-disable-next-line no-unreachable
                break;
            case placement.includes("end"):
                return fixed ? (v * 0.70) - (z * 0.33333) : (v * 0.75) - (z * 0.33333);
                // eslint-disable-next-line no-unreachable
                break;
            default:
                return (v * 0.5) - (z * 0.5);
                // eslint-disable-next-line no-unreachable
                break;
        }
    }
}

const FS = new FormSynergy();