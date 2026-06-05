/**
 * Bloqueia injeção do Live Server (reload.js / ws:5500) fora da porta 5500.
 * Deve ser carregado no <head> antes de outros scripts.
 */
(function () {
    'use strict';

    if (window.location.port === '5500') {
        return;
    }

    const NativeWebSocket = window.WebSocket;

    if (NativeWebSocket) {
        function LiveServerWebSocket(url, protocols) {
            if (String(url).includes(':5500')) {
                return {
                    readyState: 3,
                    bufferedAmount: 0,
                    extensions: '',
                    protocol: '',
                    binaryType: 'blob',
                    onopen: null,
                    onmessage: null,
                    onerror: null,
                    onclose: null,
                    close() {},
                    send() {},
                    addEventListener() {},
                    removeEventListener() {},
                    dispatchEvent() {
                        return true;
                    },
                };
            }

            if (protocols !== undefined) {
                return new NativeWebSocket(url, protocols);
            }

            return new NativeWebSocket(url);
        }

        LiveServerWebSocket.CONNECTING = NativeWebSocket.CONNECTING;
        LiveServerWebSocket.OPEN = NativeWebSocket.OPEN;
        LiveServerWebSocket.CLOSING = NativeWebSocket.CLOSING;
        LiveServerWebSocket.CLOSED = NativeWebSocket.CLOSED;
        LiveServerWebSocket.prototype = NativeWebSocket.prototype;
        window.WebSocket = LiveServerWebSocket;
    }

    function removeLiveReloadScripts() {
        document
            .querySelectorAll('script[src*="reload.js"], script#liveReloadScript')
            .forEach((script) => script.remove());
    }

    function watchLiveReloadInjection() {
        if (typeof MutationObserver === 'undefined' || !document.documentElement) {
            return;
        }

        new MutationObserver((mutations) => {
            for (const mutation of mutations) {
                for (const node of mutation.addedNodes) {
                    if (node.nodeName !== 'SCRIPT') {
                        continue;
                    }

                    const src = node.src || '';

                    if (node.id === 'liveReloadScript' || src.includes('reload.js')) {
                        node.remove();
                    }
                }
            }
        }).observe(document.documentElement, { childList: true, subtree: true });
    }

    removeLiveReloadScripts();
    watchLiveReloadInjection();
})();
