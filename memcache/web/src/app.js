(function() {
    'use strict';

    var app = angular.module('webmemcachetool', ['angular-websocket', 'smart-table']);

    app.controller('mainController', ['$scope', 'socket', '$interval',
        function($scope, socket, $interval) {

        $scope.memcacheData = [];
        $scope.displayedMemcacheData = [];
        $scope.keyViewMode = false;
        $scope.viewKey = null;
        $scope.viewValue = null;

        $scope.numItemsPerPage = [5, 10, 20, 50, 100];
        $scope.itemsPerPage = $scope.numItemsPerPage[2];

        $scope.init = function() {
            socket.emit('get_memcache_data');
        };

        $scope.viewInfo = function(key) {
            $scope.keyViewMode = true;
            socket.emit('get_memcache_key_info', {key: key});
        };

        $scope.viewBack = function() {
            $scope.keyViewMode = false;
            $scope.viewKey = null;
            $scope.viewValue = null;
        }

        $scope.flush = function(key) {
            if(!confirm('Do you really want to flush?')) {
                return;
            }
            socket.emit('flush_key', {key: key});
            var index = $scope.memcacheData.map(function(val) {
                return val.key;
            }).indexOf(key);

            if (index !== -1) {
                $scope.memcacheData.splice(index, 1);
            }
            if($scope.keyViewMode) {
                $scope.viewBack();
            }
        };

        $scope.flushAll = function() {
            if(!confirm('Do you really want to flush ALL keys?')) {
                return;
            }
            socket.emit('flush_all');
            $scope.memcacheData = null;
        };

        var refreshTime = OPTIONS.refresh_time || 2;
        $interval(function() {
            socket.emit('get_memcache_data');
        }, refreshTime * 1000);


        socket.on('get_memcache_data', function(data) {
            $scope.memcacheData = data.map(function(val, index) {
                return {key: val[0], value: val[1]};
            });
            $scope.displayedMemcacheData = angular.copy($scope.memcacheData);
        });

        socket.on('get_memcache_key_info', function(data) {
            $scope.viewKey = data[0][0];
            $scope.viewValue = data[0][1];
        });
    }]);

    app.factory('socket', ['$websocket', function($websocket) {
        var wsServer = OPTIONS.server_url || 'ws://localhost';
        var wsPort = OPTIONS.server_port || '8003';
        var socket = $websocket(wsServer + ':' + wsPort);
        var callbacks = [];

        socket.onMessage(function(msg) {
            var response = JSON.parse(msg.data);
            var cbName = response['action'];
            if(callbacks[cbName]) {
                callbacks[cbName](response.data);
            }
        });

        return {
            on: function(eventName, callback) {
                if(!callbacks[eventName]) {
                    callbacks[eventName] = callback;
                }
            },
            emit: function(eventName, data) {
                data = data || {};
                data['action'] = eventName;
                socket.send(angular.toJson(data));
            }
        };
    }]);

    app.filter('strLimit', ['$filter', function($filter) {
        return function(input, limit) {
            if(!input) return;
            if(input.length <= limit) {
                return input;
            }

            return $filter('limitTo')(input, limit) + '...';
        };
    }]);

})();
