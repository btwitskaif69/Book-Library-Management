const mongoose = require('mongoose');

const BookSchema = new mongoose.Schema({
    title:  { type: String, required: true, unique: true },
    author: { type: String, required: true },
    year:   { type: Number },
}, { timestamps: true });

module.exports = mongoose.model('Book', BookSchema);
