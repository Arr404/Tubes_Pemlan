from flask import Flask, request, jsonify, make_response
import pymysql

app = Flask(__name__)

@app.route('/')
@app.route('/index')
def index():
    return "Hello World!"

mydb = pymysql.connect(
    host="localhost",
    user="root",
    passwd="",
    database="db_iot"
)

@app.route('/post_data', methods=['POST'])
def web_command():
    hasil = {"status": "gagal"}
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

@app.route('/get_data', methods=['GET'])
def web_sensor():
    query = "SELECT * FROM tb_iot"

    mycursor = mydb.cursor()
    mycursor.execute(query)
    row_headers = [x[0] for x in mycursor.description]
    data = mycursor.fetchall()
    json_data = []
    for result in data:
        json_data.append(dict(zip(row_headers, result)))
    mydb.commit()
    return make_response(jsonify(json_data),200)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5010)