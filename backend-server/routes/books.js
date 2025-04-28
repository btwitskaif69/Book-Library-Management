const express = require('express');
const Book = require('../models/Book');

const router = express.Router();

// CREATE
router.post('/', async (req, res) => {
    try {
        const newBook = new Book(req.body);
        await newBook.save();
        res.status(201).json(newBook);
    } catch (err) {
        res.status(500).json(err.message);
    }
});

// READ all
router.get('/', async (req, res) => {
    try {
        const books = await Book.find();
        res.json(books);
    } catch (err) {
        res.status(500).json(err.message);
    }
});

// UPDATE
router.put('/:id', async (req, res) => {
    try {
        const updatedBook = await Book.findByIdAndUpdate(req.params.id, req.body, { new: true });
        res.json(updatedBook);
    } catch (err) {
        res.status(500).json(err.message);
    }
});

// DELETE
router.delete('/:id', async (req, res) => {
    try {
        await Book.findByIdAndDelete(req.params.id);
        res.json("Book deleted");
    } catch (err) {
        res.status(500).json(err.message);
    }
});

module.exports = router;
