# Bookstore API

## Notes

* This is a Laravel application
* Tests have only been written to cover code I have written, not to cover Laravel's boilerplate code
* This has been tested locally using Laravel Homestead as a development environment, and Postman to make API calls

## Installation

Add to Homestead or any other local virtual environment, then run this from the root directory to install dependancies, seed the database with initial data, etc:

```sh ./install-local.sh```

## Running unit tests

Just run:

```phpunit```

From the root directory.

## Methods

| Protocol  | Path | Parameters | Description
| ------------- | ------------- | ------------- | ------------- |
| GET | / | | Root - just displays API version number
| GET | /books/ | category<br>author | List of books. Filterable by category or author
| GET | /categories/ | | List of categories
| POST | /books/\_new | | Create a new book. Accepts a JSON payload of data, an example of which can be seen below
| PUT | /books/{id} | | Update an existing book. Accepts a JSON payload, the same as the create endpoint

### Example JSON payload

```json
{
    "title": "Book Title",
    "isbn": "000-0000000000",
    "author": "Joe Bloggs",
    "price": 9.99,
    "categories": [
        "PHP",
        "Linux"
    ]
}
```

## Possible improvements

* Pagination of results
* Allow filtering by any field - e.g. by isbn
* Convert the output to follow the [JSON API Specification](https://jsonapi.org)
* Use the Repository Design Pattern for database interaction
* Add a DELETE endpoint
* Add an authors endpoint, to list out all authors, number of books in stock and other useful stats
