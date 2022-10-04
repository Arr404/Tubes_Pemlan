const { addHeart,getAllHearts } = require('./handler');
const { getHeartsDetail } = require('./handler');
const { editHeart } = require('./handler');
const { deleteHeartById } = require('./handler');

const routes = [
    {
        method : 'POST',
        path : '/books',
        handler: addHeart
    },
    {
        method : 'GET',
        path : '/users',
        handler: getAllHearts
    },
    {
        method : 'GET',
        path : '/users/{userId}',
        handler: getHeartsDetail
    },
    {
        method : 'PUT',
        path : '/users/{userId}',
        handler: editHeart
    },
    {
        method : 'DELETE',
        path : '/users/{userId}',
        handler: deleteHeartById
    },
]

module.exports = routes
