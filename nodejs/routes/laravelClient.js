var Redis = require('ioredis');
var request = require('request');
var fs = require('fs');

var client = {
    redis: null,
    $: null,
    app: null,
    model: null,

    listen: function (app, $, model, cb) {
        console.log('start laravel client');
        var that = this;
        that.$ = $;
        that.app = app;
        that.model = model;
        that.redis = new Redis($.conf.redis);

        that.redis.subscribe('laravel', function (err, count) {
            console.log(count);
        });

        that.getUser(function(data) {
            data = JSON.parse(data);

            if(data.status == 'success') {
                that.app.users = data.users;
            }

            console.log(app.users);
            cb();
        });

        that.redis.on('message', function (channel, data) {
            console.log('Receive message %s from channel %s', data, channel);
            data = JSON.parse(data);

            if(channel == 'laravel') {
                switch (data.action) {
                    case 'addUser':
                        that.addUser(data.data);
                        break;
                    case 'deleteUser':
                        that.deleteUser(data.data);
                        break;
                }
            }
        });
    },

    getUser: function(callback) {
        var that = this;

        request.get({
            url: that.$.conf.host + 'getUser',
            headers: {
                'User-Agent': that.$.getTimeToken()
            }
        }, function(error, response, body) {
            if (error == null && callback) {
                callback(body);
            }
        });
    },

    addUser: function(data) {
        var that = this;
        var user = that.$._.chain(that.app.users)
            .find(function(v){
                return (v.id == data.id);
            })
            .value();

        if(user != undefined) {
            that.app.users = that.$._.chain(that.app.users)
                .map(function(v){
                    if(v.id == data.id){
                        v.token = data.token;
                    }

                    return v;
                })
                .value();
        } else {
            that.app.users.push({
                id          : data.id,
                token       : data.token,
                connections : []
            });
        }

        console.log(that.app.users);
    },

    deleteUser: function(data){
        var that  = this;
        that.app.users = that.$._.chain(that.app.users)
            .filter(function (v) {
                if(v.id == data.id && v.token == data.token && v.connections.length > 1){
                    v.connections = that.$._.chain(v.connections)
                        .filter(function(c){
                            if(c.id != data.device){
                                return c;
                            }
                        })
                        .value();

                    console.log(v);
                    return v;
                } else if (v.id != data.id) {
                    return v;
                }
            })
            .value();

        console.log(that.app.users);
    }
};

module.exports = client;