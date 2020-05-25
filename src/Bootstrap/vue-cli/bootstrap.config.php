<?php

use Akceli\AkceliFileModifier;
use Akceli\Bootstrap\Bootstrap;
use Illuminate\Support\Str;

return [
    Bootstrap::terminalCommand('yarn add laravel-realtime-database-vuex pusher-js vuex axios buefy'),
    Bootstrap::globalStringReplace('app.example.local', Str::replaceFirst('http://', '', env('APP_URL'))),
    Bootstrap::globalStringReplace('api.example.local', Str::replaceFirst('http://app', 'api', env('APP_URL'))),
    Bootstrap::fileModifiers(fn() => [
        AkceliFileModifier::file('src/main.js')
            ->addLineBelow('import Vue from \'vue\'', 'import Buefy from \'buefy\'')
            ->addLineBelow('import Vue from \'vue\'', 'import \'buefy/dist/buefy.css\'')
            ->addLineBelow('import Vue from \'vue\'', 'Vue.use(Buefy)')
            ->addLineBelow('import Vue from \'vue\'', 'import RealtimeStore from "laravel-realtime-database-vuex";')
            ->addLineBelow('import Vue from \'vue\'', 'import Vuex from \'vuex\';')
            ->addLineBelow('import Vue from \'vue\'', 'import Pusher from "pusher-js";')
            ->addLineAbove('new Vue({', '// RealtimeStore.init(Vue, store, new Pusher(process.env.MIX_PUSHER_APP_KEY, {cluster: \'us2\', forceTLS: true}));' . PHP_EOL),

        AkceliFileModifier::file('src/store/index.js')
            ->addLineBelow('import Vue from \'vue\'', 'import RealtimeStore from "laravel-realtime-database-vuex";')
            ->addLineBelow('mutations', '    ...RealtimeStore.channelMutations,')
    ]),
];
