var cluster = require('cluster');
const numCPUs = require('os').cpus().length;
// var schedule = require('node-schedule');

console.log(__dirname + '/bin/www');
cluster.setupMaster({exec: __dirname + '/bin/www'});

// for (var i = 0; i < numCPUs; i++) {
    cluster.fork();
// }

cluster.on('exit', function(worker, code, signal) {
    console.log('worker ' + worker.process.pid + ' died');
    cluster.fork();
});

// var cron = schedule.scheduleJob('* * * * *', function(){
//     console.log('The answer to life, the universe, and everything!');
// });