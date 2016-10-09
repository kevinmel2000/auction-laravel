var request = require('request');

var client = {
    $: null,
    app: null,
    io: null,
    model: null,

    listen: function (app, $, model) {
        console.log('start web client');
        var that = this;
        that.$ = $;
        that.app = app;
        that.io = app.io;
        that.model = model;

        that.io.sockets.on('connection', function(socket){
            console.log('Connect user');
            socket.emit('userData');

            socket.on('userData', function(data){
                that.addUser(data, socket);
            }).on('productViewer', function(data){
                that.productViewer(data, socket);
            }).on('auctionAutomatic', function(data){
                that.auctionAutomatic(data);
            }).on('makeBid', function(data){
                that.makeBid(data);
            }).on('disconnect', function(){
                that.deleteUser(socket.id);
            });
        });
    },

    addUser: function(data, socket) {
        var that = this;
        console.log(data);

        if (data.unauthorized) {
            that.app.unauthorized.push({
                id: socket.id,
                socket: socket
            });
        } else {
            that.app.users = that.$._.chain(that.app.users)
                .map(function (v) {
                    if (v.token == data.token) {
                        var connection = that.$._.chain(v.connections)
                            .find(function (c) {
                                return (c.id == data.device);
                            })
                            .value();

                        if (connection != undefined) {
                            v.connections = that.$._.chain(v.connections)
                                .map(function (c) {
                                    if (c.id == data.device) {
                                        c.socket = socket;
                                    }

                                    return c;
                                })
                                .value();
                        } else {
                            v.connections.push({
                                id: data.device,
                                socket: socket
                            });
                        }
                    }

                    return v;
                })
                .value();

            console.log(that.app.users);
        }
    },

    deleteUser: function (id) {
        var that = this;

        that.app.unauthorized = that.$._.chain(that.app.unauthorized)
            .filter(function (v) {
                if (v.id != id) {
                    return v;
                }
            })
            .value();

        for (var i in that.app.products) {
            that.app.products[i].viewers = that.$._.chain(that.app.products[i].viewers)
                                                .filter(function (viewer) {
                                                    return viewer.id != id;
                                                })
                                                .value();
        }
    },

    getStartedAuction: function () {
        var that  = this;

        console.log('gggg');
        var d = that.$.getTimeStamp();
        console.log(d)
        that.model.product.find({status: 1, date: d}).exec(function (error, products) {
            console.log('tesdt');
            console.log(error);
            if (!error) {
                for (var key in products) {
                    var product = that.app.products[products[key].id];
                    console.log('product');
                    console.log(product);

                    for (var i in product.viewers) {
                        product.viewers[i].emit('auctionStart', {
                            id: product.id
                        });
                    }

                    product.time = 14;
                    product.price = 100;
                    product.interval = setInterval(function () {
                        if (product.queue.users.length + product.used.users.length > 1) {
                            if (product.time > 1) {
                                for (var i in product.viewers) {
                                    product.viewers[i].emit('auctionTimer', {
                                        id: product.id,
                                        time: product.time
                                    });
                                }

                                product.time--;
                            } else {
                                var user = product.queue.users.shift();

                                //TODO rekursiv stugel user bideri qanak@

                                product.user = user;
                                product.used.users.push(user);

                                if (product.queue.users.length == 0) {
                                    product.queue.users = that.$._.shuffle(product.used.users);
                                    product.used.users = [];
                                }

                                var bid = new that.model.bid({
                                    product_id: product.id,
                                    type: 'user',
                                    name: user.name,
                                    price: product.price != 100 ? Math.round(product.price * 10) / 10 : 100,
                                    user_id: user.id !== undefined ? user.id : null,
                                    date: that.$.getTimeStamp('full')
                                });

                                product.time = 14;
                                product.price += 0.1;

                                bid.save(function (err, bid) {
                                    if (!err) {
                                        for (var i in product.viewers) {
                                            product.viewers[i].emit('auctionBid', {
                                                id: bid.product_id,
                                                price: bid.price,
                                                date: bid.date,
                                                name: bid.name
                                            });
                                        }
                                    }
                                });
                            }
                        } else if (product.queue.users.length + product.used.users.length == 1) {
                            if (product.queue.users.length == 1) {
                                if (product.time > 1) {
                                    for (var i in product.viewers) {
                                        product.viewers[i].emit('auctionTimer', {
                                            id: product.id,
                                            time: product.time
                                        });
                                    }

                                    product.time--;
                                } else {
                                    var user = product.queue.users.shift();

                                    //TODO rekursiv stugel user bideri qanak@

                                    product.user = user;
                                    product.used.users.push(user);

                                    var bid = new that.model.bid({
                                        product_id: product.id,
                                        type: 'user',
                                        name: user.name,
                                        price: product.price != 100 ? Math.round(product.price * 10) / 10 : 100,
                                        user_id: user.id !== undefined ? user.id : null,
                                        date: that.$.getTimeStamp('full')
                                    });

                                    product.time = 14;
                                    product.price += 0.1;

                                    bid.save(function (err, bid) {
                                        if (!err) {
                                            for (var i in product.viewers) {
                                                product.viewers[i].emit('auctionBid', {
                                                    id: bid.product_id,
                                                    price: bid.price,
                                                    date: bid.date,
                                                    name: bid.name
                                                });
                                            }
                                        }
                                    });
                                }
                            } else {
                                console.log('bot start');
                                if (product.time > 0) {
                                    for (var i in product.viewers) {
                                        product.viewers[i].emit('auctionTimer', {
                                            id: product.id,
                                            time: product.time
                                        });
                                    }

                                    product.time--;
                                } else {
                                    that.model.bid.find(
                                        {
                                            "product_id": that.$.ObjectId(product.id)
                                        },
                                        function (err, bids) {
                                            if (!err) {
                                                var bidGroupCount = that.$._.chain(bids)
                                                    .countBy(function (bid) {
                                                        return bid.type
                                                    })
                                                    .value();

                                                if (product.realPrice + 2000 < bidGroupCount.user * 10) {
                                                    product.queue.bots = [];
                                                    product.used.bots = [];
                                                }

                                                if ((bidGroupCount.user + bidGroupCount.bot) * 10 > product.realPrice * product.coefficient) {
                                                    clearInterval(product.interval);

                                                    for (var i in product.viewers) {
                                                        product.viewers[i].emit('auctionFinish', {
                                                            id: product.id,
                                                            price: product.price,
                                                            name: product.user.name
                                                        });
                                                    }
                                                }

                                                if (product.queue.bots.length + product.used.bots.length > 0) {
                                                    var user = product.queue.bots.shift();

                                                    //TODO rekursiv stugel user bideri qanak@

                                                    product.user = user;
                                                    product.used.bots.push(user);

                                                    if (product.queue.bots.length == 0) {
                                                        product.queue.bots = that.$._.shuffle(product.used.bots);
                                                        product.used.bots = [];
                                                        product.queue.users.push(product.used.users.shift());
                                                    }

                                                    var bid = new that.model.bid({
                                                        product_id: product.id,
                                                        type: 'bot',
                                                        name: user.name,
                                                        price: product.price != 100 ? Math.round(product.price * 10) / 10 : 100,
                                                        user_id: user.id !== undefined ? user.id : null,
                                                        date: that.$.getTimeStamp('full')
                                                    });

                                                    product.time = 14;
                                                    product.price += 0.1;

                                                    bid.save(function (err, bid) {
                                                        if (!err) {
                                                            for (var i in product.viewers) {
                                                                product.viewers[i].emit('auctionBid', {
                                                                    id: bid.product_id,
                                                                    price: bid.price,
                                                                    date: bid.date,
                                                                    name: bid.name
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        }
                                    );
                                }
                            }
                        } else {
                            if (product.time > 0) {
                                for (var i in product.viewers) {
                                    product.viewers[i].emit('auctionTimer', {
                                        id: product.id,
                                        time: product.time
                                    });
                                }

                                product.time--;
                            } else {
                                that.model.bid.find(
                                    {
                                        "product_id": that.$.ObjectId(product.id)
                                    },
                                    function (err, bids) {
                                        if (!err) {
                                            var bidGroupCount = that.$._.chain(bids)
                                                .countBy(function (bid) {
                                                    return bid.type
                                                })
                                                .value();

                                            if (product.realPrice + 2000 < bidGroupCount.user * 10) {
                                                product.queue.bots = [];
                                                product.used.bots = [];
                                            }

                                            if ((bidGroupCount.user + bidGroupCount.bot) * 10 > product.realPrice * product.coefficient) {
                                                clearInterval(product.interval);

                                                for (var i in product.viewers) {
                                                    product.viewers[i].emit('auctionFinish', {
                                                        id: product.id,
                                                        price: product.price,
                                                        name: product.user.name
                                                    });
                                                }
                                            }

                                            if (product.queue.bots.length > 0) {
                                                var user = product.queue.bots.shift();

                                                //TODO rekursiv stugel user bideri qanak@

                                                product.user = user;
                                                product.used.bots.push(user);

                                                if (product.queue.bots.length == 0) {
                                                    product.queue.bots = that.$._.shuffle(product.used.bots);
                                                    product.used.bots = [];
                                                }

                                                var bid = new that.model.bid({
                                                    product_id: product.id,
                                                    type: 'bot',
                                                    name: user.name,
                                                    price: product.price != 100 ? Math.round(product.price * 10) / 10 : 100,
                                                    user_id: user.id !== undefined ? user.id : null,
                                                    date: that.$.getTimeStamp('full')
                                                });

                                                product.time = 14;
                                                product.price += 0.1;

                                                bid.save(function (err, bid) {
                                                    if (!err) {
                                                        for (var i in product.viewers) {
                                                            product.viewers[i].emit('auctionBid', {
                                                                id: bid.product_id,
                                                                price: bid.price,
                                                                date: bid.date,
                                                                name: bid.name
                                                            });
                                                        }
                                                    }
                                                });
                                            } else {
                                                //todo haxtox
                                            }
                                        }
                                    }
                                );
                            }
                        }

                        that.app.products[product.id] = product;
                    }, 950);

                    that.app.products[products[key].id] = product;
                    that.updateProductStatus(products[key].id, 2);
                }
            }
        })
    },

    productViewer: function (data, socket) {
        var that = this;
        console.time('product viewer');

        for (var i in data.products) {
            that.app.products[data.products[i]].viewers.push(socket);
        }

        if (data.token !== undefined) {
            console.log('token');
            console.log(data.token);
            var user = that.$._.chain(that.app.users)
                            .find(function (user) {
                                return user.token == data.token;
                            })
                            .value();

            console.log('$$$$$$$$$$$$$$');
            console.log(user);
            var automatic = !!(that.$._.chain(that.app.products[data.products[0]].queue.users)
                                    .find(function (item) {
                                        return item.user_id == user.id;
                                    })
                                    .value() ||
                               that.$._.chain(that.app.products[data.products[0]].used.users)
                                    .find(function (item) {
                                        return item.user_id == user.id;
                                    })
                                    .value());

            socket.emit('auctionDetail', {
                automatic: automatic,
                price: that.app.products[data.products[0]].price === undefined ? 100 : that.app.products[data.products[0]].price,
                id: data.products[0]
            });

            console.log('+++++++++++++++++++');
            console.log(automatic);
            console.log('+++++++++++++++++++');
            console.timeEnd('product viewer')
        }
    },

    updateProductStatus: function (id, status) {
        var that = this;

        request.get({
            url: that.$.conf.host + 'products/status?id=' + id + '&status=' + status,
            headers: {
                'User-Agent': that.$.getTimeToken()
            },
        }, function(error, response, body) {
            if (!error) {
                that.model.product.findOneAndUpdate({'_id': id}, {$set: {status: status}}, function (err, doc) {

                });
            }
        });
    },

    auctionAutomatic: function (data) {
        var that  = this;
        console.time('auction automatic')
        console.log(data);

        var user = that.$._.chain(that.app.users)
                            .find(function (user) {
                                return user.token == data.token;
                            })
                            .value();

        if (data.type) {
            that.app.products[data.product_id].queue.users.push({
                name: user.name,
                user_id: user.id
            });
        } else {
            that.app.products[data.product_id].queue.users = that.$._.chain(that.app.products[data.product_id].queue.users)
                                                            .reject(function (item) {
                                                                return item.user_id == user.id;
                                                            })
                                                            .value();
            
            that.app.products[data.product_id].used.users = that.$._.chain(that.app.products[data.product_id].used.users)
                                                                .reject(function (item) {
                                                                    return item.user_id == user.id;
                                                                })
                                                                .value();
        }
        console.timeEnd('auction automatic')
    },

    makeBid: function (data) {
        var that = this;
        console.log(data);

        var user = that.$._.chain(that.app.users)
                        .find(function (user) {
                            return user.token == data.token;
                        })
                        .value();

        var product = that.app.products[data.id];

        var bid = new that.model.bid({
            product_id: data.id,
            type: 'user',
            name: user.name,
            price: product.price != 100 ? Math.round(product.price * 10) / 10 : 100,
            user_id: user.id,
            date: that.$.getTimeStamp('full')
        });

        bid.save(function (err, bid) {
            if (!err) {
                for (var i in product.viewers) {
                    product.viewers[i].emit('auctionBid', {
                        id: bid.product_id,
                        price: bid.price,
                        date: bid.date,
                        name: bid.name
                    });
                }
            }
        });

        product.price += 0.1;
        product.time = 14;
        that.app.products[data.id] = product;
    }
};

module.exports = client;