const mongoose = require('mongoose');

const BookSchema = new mongoose.Schema({
    title:       { type: String, required: true },
    author:      { type: String, required: true },
    year:        { type: Number },
    description: { type: String }, // Added description field
}, { timestamps: true });

module.exports = mongoose.model('Book', BookSchema);
