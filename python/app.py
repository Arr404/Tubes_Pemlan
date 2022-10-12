from operator import ge
from flask import Flask, request, jsonify, make_response,Response
import pymysql
from datetime import datetime
from nanoid import generate

app = Flask(__name__)


mydb = pymysql.connect(
    host="localhost",
    user="root",
    passwd="",
    database="db_iot"
)
@app.route('/user/{{userId}}/hearth/', methods=['POST'])
def addHeart():
    response = {"statusCode": 404,"error":"Not Found","message":"Not Found"}
    userId = request.params["userId"]
    name = request.payload["name"]
    data = request.payload["data"]
    id = generate()
    insertedAt = datetime.today()
    updatedAt = insertedAt
    if name == "" or name is None:
        response = {
            "status": 'fail',
            "message": 'Gagal menambahkan hearth rate. Mohon isi nama device',
        }
        response.code(400)
        return response

    elif data == "" or data is None:
        response = {
            "status": 'fail',
            "message": 'Gagal menambahkan hearth rate. Mohon isi data hearth',
        }
        return response, 400

    newHeart = {
        id, name,data, insertedAt, updatedAt
    }
    pushDataDb(newHeart)
    hearth = getAllDb()
    #hearth.push(newHeart)
    isSuccess = hearth.filter(lambda note: note.id == id)
    #filter((note) => note.id === id).length > 0
    if (isSuccess):
        response = {"status": 'success', "message": 'Hearth berhasil ditambahkan',"data": { "hearthId": id,},}
        return response, 201

    response = {"status": 'fail',"message": 'hearth gagal ditambahkan'}
    
    return response, 500

@app.route('/user/{{userId}}/hearth/', methods=['GET'])
def getAllHearts():
    hearth = getAllDb
    response = {
        "status": 'success',
        "data": {
            "hearths": hearth,

        },
    }
    return response,200


@app.route('/user/{{userId}}/hearth/{{hearthId}}', methods=['GET'])
def ambil_data():

    hearthId = request.params["hearthId"]
    hearth = getAllDb
    heartha = hearth.filter(lambda note: note.id == hearthId)[0]
    if heartha != None:
        return {
            "status": 'success',
            "data": {
                heartha,
            },
        },200

    response = {
        "status": 'fail',
        "message": 'Gagal menambahkan hearth rate. Mohon isi data hearth',
    }
    return response, 400

@app.route('/user/{{userId}}/hearth/{{hearthId}}', methods=['PUT'])
def kirim_data():
    hearthId = request.params["hearthId"]
    name = request.payload["name"]
    data = request.payload["data"]
    hearth = getAllDb
    index = hearth.keys().index(hearthId)
    if(name == "" or name is None):
        response = {
            "status": 'fail',
            "message": 'Gagal memperbarui hearth. Mohon isi data hearth',
        }
        return response,400

    if (index != -1):
        hearth[index] = {
            hearth[::index],
            name,
            data
        }

        response = {
            "status": 'success',
            "message": 'Heart berhasil diperbarui',
        }
        return response,200

    response = {
        "status": 'fail',
        "message": 'Gagal memperbarui Heart. Id tidak ditemukan',
    }
    return response, 404
    

@app.route('/user/{{userId}}/hearth/{{hearthId}}', methods=['DELETE'])
def deleteHeartById():
    hearthId  = request.params["hearthId"]
    hearth = getAllDb
    index = hearth.keys().index(hearthId)

    if (index != -1):
        hearth.splice(index, 1)
        response = {
            "status": 'success',
            "message": 'Heart berhasil dihapus',
        }
        return response, 200

    response = {
        "status": 'fail',
        "message": 'Heart gagal dihapus. Id tidak ditemukan',
    }
    return response, 404



@app.route('/post_data', methods=['POST'])
def web_command():
    response = {
            "status": 'fail',
            "message": 'Gagal menambahkan hearth rate. Mohon isi data hearth',
    }
    query = "insert into tb_iot(a, b, c, d) values(%s,%s,%s,%s)"
    try:
        data = request.args.get('data', default = 1, type = int)
        print(data)
        # a = data["a"]
        # b = data["b"]   
        # c = data["c"]
        # d = data["d"]
        # value = (a, b, c, d)
        # mycursor = mydb.cursor()
        # mycursor.execute(query, value)
        # mydb.commit()
        hasil = {"status": "berhasil"}
    except Exception as e:
        print("Error: " + str(e))
    
    return jsonify(hasil)

#@app.route('/get_data', methods=['GET'])
def pushDataDb(data):
    query = "insert into tb_iot(id, name, data, insertedAt, updatedAt) values(%s,%s,%s,%s,%s)"
    try:
        #data = request.args.get('data', default = 1, type = int)
        #print(data)
        #id = data["id"]
        #name = data["name"]   
        #data = data["data"]
        #insertedAt = data["insertedAt"]
        #updatedAt = data["updatedAt"]
        value = (data.id, data.name,data.data, data.insertedAt, data.updatedAt)
        mycursor = mydb.cursor()
        mycursor.execute(query, value)
        mydb.commit()
        print("berhasil")
    except Exception as e:
        print("Error: " + str(e))

def getAllDb():
    query = "SELECT * FROM tb_iot"
    mycursor = mydb.cursor()
    mycursor.execute(query)
    row_headers = [x[0] for x in mycursor.description]
    data = mycursor.fetchall()
    json_data = []
    for result in data:
        json_data.append(dict(zip(row_headers, result)))
    mydb.commit()
    return make_response(jsonify(json_data))

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5010)