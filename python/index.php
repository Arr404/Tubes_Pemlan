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
    border: 1px solid black;
    border-collapse: collapse;
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

        <pre>a    : <input type="text" required="required" name="a"
                value=""><br></pre>
        <pre>b   : <input type="text" required="required" name="b"
                value=""><br></pre>
        <pre>c   : <input type="text" name="c"
                value=""><br></pre>
        <pre>d : <input type="text" name="d"
                value=""><br><br></pre>

        <input type="submit" name="button"
                value="Submit"/><br>
    </form>
    <?php
        if(isset($_POST['button'])) {
            $data = post_data("http://0.0.0.0:5010/post_data", $a, $b, $c, $d);
            echo "<br>Data Berhasil Dikirim<br>";
        }
    ?>

    <h1>
        Daftar
    </h1>
    <?php
        $dataiot = get_data("http://0.0.0.0:5010/get_data");
        $obj = json_decode($dataiot, true);
        echo($obj);
        echo '  <table>
                    <tr>
                        <th>a</th><th>b</th><th>c</th><th>d</th>
                    </tr>
                </table>';
        if($obj !== null){
            foreach($obj as $item) {
                $a = $item["a"];
                $b2 = $item["b"];
                $c2 = $item["c"];
                $d = $item["d"];
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