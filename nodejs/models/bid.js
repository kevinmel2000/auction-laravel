module.exports = function (mongoose) {
    this.collectionName = function () {
        return 'bids';
    };

    this.rules = function () {
        return new mongoose.Schema({
            product_id: {
                type: mongoose.Schema.Types.ObjectId,
                required: true
            },
            type: {
                type: String,
                required: true
            },
            name: {type: String},
            price: {
                type: Number,
                required: true
            },
            user_id: {type: Number},
            date: {
                type: Number,
                required: true
            }
        });
    };

    this.model = mongoose.model('ModelBids', this.rules(), this.collectionName());
};