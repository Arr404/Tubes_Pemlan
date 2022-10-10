<!DOCTYPE html>
<html>

<head>
    <title>
        REST API
    </title>
</head>
<style>
th, td {
        height: 50px;
        width: 150px;
}
table, th, td {
    border: 1px solid white;
    border-collapse: collapse;
}
h1, form, h4, table {
    color:white;
}
body {
    background-color: #ff00ff;
}
</style>

<body>

    <h1>
        Input Data
    </h1>
    
    <?php
        $a = $b = $c = $d = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $a = input_data($_POST["a"]);
            $b = input_data($_POST["b"]);
            $c = input_data($_POST["c"]);
            $d = input_data($_POST["d"]);
        }

        function input_data($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        function post_data($url, $a, $b, $c, $d){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\n\t\"a\":\"$a\",\n\t\"b\":\"$b\",\n\t\"c\":\"$c\",\n\t\"d\":\"$d\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }

        function get_data($url){
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
            ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }

    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

        <pre>Data 1   : <input type="text" name="a"
                value=""><br></pre>
        <pre>Data 2   : <input type="text" name="b"
                value=""><br></pre>
        <pre>Data 3   : <input type="text" name="c"
                value=""><br></pre>
        <pre>Data 4   : <input type="text" name="d"
                value=""><br></pre>

        <input type="submit" name="button"
                value="Submit"/><br>
    </form>
    <?php
        if(isset($_POST['button'])) {
            $data = post_data("http://10.5.99.30:5010/post_data", $a, $b, $c, $d);
            echo "<h4>Data Berhasil Dikirim<h4>";
        }
    ?>

    <h1>
        List Data
    </h1>
    <?php
        $dataiot = get_data("http://139.59.239.81:5000/user/asudasjd/hearth/JXFASPcYJqVjdNHC");
        $obj = json_decode($dataiot, true);
        echo '  <table>
                    <tr>
                        <th>ID</th><th>Name</th><th>Data</th><th>Inserted At</th>
                    </tr>
                </table>';
        if($obj !== null){
            foreach(($obj["data"]) as $item) {
                $a = $item["id"];
                $b2 = $item["name"];
                $c2 = $item["data"];
                $d = $item["insertedAt"];
                echo '  <table>
                            <tr>
                                <td>'.$a.'</td><td>'.$b2.'</td><td>'.$c2.'</td><td>'.$d.'</td>
                            </tr>
                        </table>';
            }
        }
    ?>
</body>
</html>