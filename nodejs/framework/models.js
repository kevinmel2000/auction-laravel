var mongoose = require('mongoose');
var product = require('./../models/product');
var bid = require('./../models/bid');

exports.run = function ($, callback) {
    var connect = mongoose.connect($.conf.mongo_conf, function (error) {
        if (error) {
            console.log(error);
        }
    });
    
    process.nextTick(function () {
        callback({
            product: (new product(connect)).model,
            bid: (new bid(connect)).model
        })
    });
}