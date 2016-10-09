var conf = require('./config');

var lib = {
    getTimeToken: function () {
        var date = new Date();
        var ms = date.getTime();
        var time = Math.round(ms / 1000);
        var second = date.getSeconds();
        var code = [];

        second = (Math.round((time - second) / 60) + conf.time_token).toString();

        for(var i = 0; i < second.length; i++) {
            var tmp = second.charCodeAt(i).toString();

            tmp = parseInt(tmp.charCodeAt(0).toString() + tmp.charCodeAt(1).toString());
            code.push((tmp + tmp * 100).toString(16));
        }

        return code.join('a');
    },
    
    getTimeStamp: function (type) {
        type = type || 'normal';
        var now = new Date();
        var date = null;

        if (type == 'normal') {
            date = new Date(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours(), now.getMinutes());
        } else if(type == 'full') {
            date = new Date(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours(), now.getMinutes(), now.getSeconds());
        }

        return parseInt(date.getTime() / 1000) + (date.getTimezoneOffset() * 60);
    }
};

module.exports = lib;