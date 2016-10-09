module.exports = function (mongoose) {
    this.collectionName = function () {
        return 'products';
    };

    this.rules = function () {
        return new mongoose.Schema({
            bot_count: {type: Number},
            bot_names: {type: Array},
            price: {type: Number},
            coefficient: {type: Number},
            date: {type: Number},
            status: {type: Number}
        });
    };

    this.model = mongoose.model('ModelProducts', this.rules(), this.collectionName());
};