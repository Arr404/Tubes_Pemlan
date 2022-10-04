const { nanoid } = require('nanoid');
const hearth = require('./data.js');

const addHeart = (request,h) => {
    const { name,  data} = request.payload;
    const id = nanoid(16);
    const insertedAt = new Date().toISOString();
    const updatedAt = insertedAt;

    if(name === "" || name == null){
        const response = h.response({
            status: 'fail',
            message: 'Gagal menambahkan hearth rate. Mohon isi nama device',
        });
        response.code(400);
        return response;

    }else if(data === "" || data == null){
        const response = h.response({
            status: 'fail',
            message: 'Gagal menambahkan hearth rate. Mohon isi data hearth',
        });
        response.code(400);
        return response;

    }
    const newHeart = {
        id, name,data, insertedAt, updatedAt
    };
    hearth.push(newHeart)
    const isSuccess = hearth.filter((note) => note.id === id).length > 0;
    if (isSuccess) {
        const response = h.response({
            status: 'success',
            message: 'Hearth berhasil ditambahkan',
            data: {
                hearthId: id,
            },
        });
        response.code(201);
        return response;
    }

    const response = h.response({
        status: 'fail',
        message: 'hearth gagal ditambahkan',
    });
    response.code(500);
    return response;
}
function checkTrueFalseOnData(data,param){
    if(param === "0"){
        return data === false;
    }else if(param === "1"){
        return data === true;
    }
}
function checkWordInSpace(data,param){
    let dataToCheck = data.split(" ");

    for(let i=0;i<dataToCheck.length;i++){
        console.log(dataToCheck[i],param.toLowerCase())
        if(dataToCheck[i].toLowerCase() === param.toLowerCase()){
            return true;
        }
    }
    return false;
}
const getAllHearts = (request,h) => {
    const showIt = [];
    const { name, reading, finished } = request.query;

    let booksFiltered = books;
    if(reading !== undefined ){
        booksFiltered = booksFiltered.filter((n) => checkTrueFalseOnData(n.reading,reading));
    }
    if(finished !== undefined ){
        booksFiltered = booksFiltered.filter((n) => checkTrueFalseOnData(n.finished,finished));
    }
    if(name !== undefined ){
        booksFiltered = booksFiltered.filter((n) => checkWordInSpace(n.name,name));
    }

    if(booksFiltered != null){
        for(let i=0; i<booksFiltered.length; i++){
             const book = {
                id:(booksFiltered[i].id), name:(booksFiltered[i].id), publisher:(booksFiltered[i].publisher)
            };

            showIt.push(book)
        }
    }
    const response = h.response({
        status: 'success',
        data: {
            books: showIt,

        },
    });
    response.code(200);
    return response;
}

const getHeartsDetail = (request,h)=>{
    const { bookId } = request.params;
    const book = books.filter((n) => n.id === bookId)[0];
    if (book !== undefined) {
        return {
            status: 'success',
            data: {
                book,
            },
        };
    }

    const response = h.response({
        status: 'fail',
        message: 'Buku tidak ditemukan',
    });
    response.code(404);
    return response;
}

const editHeart = (request,h) =>{
    const { bookId } = request.params;
    const { name, year, author, summary, publisher, pageCount, readPage, reading } = request.payload;
    const index = books.findIndex((note) => note.id === bookId);
    if( readPage > pageCount ){
        const response = h.response({
            status: 'fail',
            message: 'Gagal memperbarui buku. readPage tidak boleh lebih besar dari pageCount',
        });
        response.code(400);
        return response;
    } else if(name === "" || name == null){
        const response = h.response({
            status: 'fail',
            message: 'Gagal memperbarui buku. Mohon isi nama buku',
        });
        response.code(400);
        return response;

    }

    if (index !== -1) {
        books[index] = {
            ...books[index],
            name,
            year,
            author,
            summary,
            publisher,
            pageCount,
            readPage,
            reading
        };

        const response = h.response({
            status: 'success',
            message: 'Buku berhasil diperbarui',
        });
        response.code(200);
        return response;
    }

    const response = h.response({
        status: 'fail',
        message: 'Gagal memperbarui buku. Id tidak ditemukan',
    });
    response.code(404);
    return response;

}
const deleteHeartById = (request, h) => {
    const { bookId } = request.params;

    const index = books.findIndex((note) => note.id === bookId);

    if (index !== -1) {
        books.splice(index, 1);
        const response = h.response({
            status: 'success',
            message: 'Buku berhasil dihapus',
        });
        response.code(200);
        return response;
    }

    const response = h.response({
        status: 'fail',
        message: 'Buku gagal dihapus. Id tidak ditemukan',
    });
    response.code(404);
    return response;
};

module.exports = {
    addHeart,
    getAllHearts,
    getHeartsDetail,
    editHeart,
    deleteHeartById
};
