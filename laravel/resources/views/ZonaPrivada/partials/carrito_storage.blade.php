<script>
    window.moldpackCartStorageKey = 'moldpack_cart_cliente_{{ Auth::guard('cliente')->id() }}';
    window.moldpackCartSyncUrl = @json(route('carrito.abandonado.guardar'));
    window.moldpackCartLoadUrl = @json(route('carrito.abandonado.obtener'));
    window.moldpackCartCsrf = @json(csrf_token());
    window.moldpackCartSyncTimer = null;
    window.moldpackCartRestoredItems = null;

    window.moldpackCartGet = function () {
        var saved = sessionStorage.getItem('obj_fila') || localStorage.getItem(window.moldpackCartStorageKey);
        if (!saved) {
            return [];
        }

        try {
            return $.makeArray(JSON.parse(saved));
        } catch (error) {
            sessionStorage.removeItem('obj_fila');
            localStorage.removeItem(window.moldpackCartStorageKey);
            return [];
        }
    };

    window.moldpackCartSync = function (cart, immediate) {
        var normalizedCart = $.makeArray(cart || []);

        if (!window.moldpackCartSyncUrl) {
            return;
        }

        if (window.moldpackCartSyncTimer) {
            clearTimeout(window.moldpackCartSyncTimer);
        }

        var send = function () {
            if (!window.fetch) {
                return;
            }

            fetch(window.moldpackCartSyncUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.moldpackCartCsrf
                },
                body: JSON.stringify({
                    items: normalizedCart
                })
            }).catch(function () {});
        };

        if (immediate) {
            send();
            return;
        }

        window.moldpackCartSyncTimer = setTimeout(send, 700);
    };

    window.moldpackCartSyncOnExit = function () {
        var normalizedCart = $.makeArray(window.moldpackCartGet() || []);

        if (!window.moldpackCartSyncUrl) {
            return;
        }

        if (window.moldpackCartSyncTimer) {
            clearTimeout(window.moldpackCartSyncTimer);
            window.moldpackCartSyncTimer = null;
        }

        if (navigator.sendBeacon) {
            var data = new FormData();
            data.append('_token', window.moldpackCartCsrf);
            data.append('items', JSON.stringify(normalizedCart));
            navigator.sendBeacon(window.moldpackCartSyncUrl, data);
            return;
        }

        if (window.fetch) {
            fetch(window.moldpackCartSyncUrl, {
                method: 'POST',
                credentials: 'same-origin',
                keepalive: true,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.moldpackCartCsrf
                },
                body: JSON.stringify({
                    items: normalizedCart
                })
            }).catch(function () {});
        }
    };

    window.moldpackCartSet = function (cart) {
        var normalizedCart = $.makeArray(cart || []);
        var payload = JSON.stringify(normalizedCart);
        sessionStorage.setItem('obj_fila', payload);
        localStorage.setItem(window.moldpackCartStorageKey, payload);
        localStorage.setItem(window.moldpackCartStorageKey + '_updated_at', new Date().toISOString());
        window.moldpackCartSync(normalizedCart);
    };

    window.moldpackCartClear = function () {
        sessionStorage.removeItem('obj_fila');
        localStorage.removeItem(window.moldpackCartStorageKey);
        localStorage.removeItem(window.moldpackCartStorageKey + '_updated_at');
        window.moldpackCartSync([], true);
    };

    if (!sessionStorage.getItem('obj_fila') && localStorage.getItem(window.moldpackCartStorageKey)) {
        sessionStorage.setItem('obj_fila', localStorage.getItem(window.moldpackCartStorageKey));
    }

    window.moldpackCartRestoreFromServer = function () {
        if (!window.fetch || !window.moldpackCartLoadUrl) {
            return;
        }

        if (sessionStorage.getItem('obj_fila') || localStorage.getItem(window.moldpackCartStorageKey)) {
            return;
        }

        fetch(window.moldpackCartLoadUrl, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json'
            }
        }).then(function (response) {
            if (!response.ok) {
                return null;
            }

            return response.json();
        }).then(function (data) {
            if (!data || !Array.isArray(data.items) || data.items.length === 0) {
                return;
            }

            var payload = JSON.stringify(data.items);
            sessionStorage.setItem('obj_fila', payload);
            localStorage.setItem(window.moldpackCartStorageKey, payload);
            localStorage.setItem(window.moldpackCartStorageKey + '_updated_at', new Date().toISOString());
            window.moldpackCartRestoredItems = data.items;
            document.dispatchEvent(new CustomEvent('moldpack-cart-restored', {
                detail: {
                    items: data.items
                }
            }));
        }).catch(function () {});
    };

    window.moldpackCartRestoreFromServer();
    window.addEventListener('pagehide', window.moldpackCartSyncOnExit);
</script>
