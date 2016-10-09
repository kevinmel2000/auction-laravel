var util = require('util');
var lib  = require('./lib');
var mongoose = require ('mongoose');

var includes = new function () {
    this._ = require('underscore');
    this.util = util;
    this.md5 = require('MD5');
    this.conf = require('./config');
    this.os = require('os');
    this.date_util = require('date-util');
    this.ObjectId = mongoose.Types.ObjectId;
};

module.exports = util._extend(includes, lib);