var express = require('express');
var logger = require('morgan');
var schedule = require('node-schedule');

var framework = require('./framework/include');
var models = require('./framework/models');

var laravel = require('./routes/laravelClient');
var web = require('./routes/webClient');

var app = express();

app.use(logger('dev'));

app.users = [];
app.unauthorized = [];
app.products = {};

console.time('start model');
models.run(framework, function (models) {
    console.timeEnd('start model');
    console.time('find product');
    models.product.find({status: {$ne: 3}}).exec(function (error, products) {
        console.timeEnd('find product');
        if (!error) {
            for (var product in products) {
                var queue = {
                    users: [],
                    bots: framework._.chain(JSON.parse(products[product].bot_names))
                                     .map(function (name) {
                                         return {
                                             name: name
                                         };
                                     })
                                     .value()
                };
                
                app.products[products[product].id] = {
                    id: products[product].id,
                    realPrice: products[product].price,
                    coefficient: products[product].coefficient,
                    viewers: [],
                    queue: queue,
                    used: {
                        users: [],
                        bots: []
                    }
                }
            }

            console.log('products');
            console.log(app.products);
            console.time('get users');
            laravel.listen(app, framework, models, function () {
                console.timeEnd('get users');
                web.listen(app, framework, models);
            });
        }
    });
});

schedule.scheduleJob('* * * * *', function() {
console.log('cron');
    web.getStartedAuction();
});

module.exports = app;
