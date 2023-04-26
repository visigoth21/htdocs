<?php 
//------------------------------------------------V1---------------------------------------------//
session_start();        
//-----------------------------------------------------------------------------------------------//
if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    if (isset($_POST['btnClear'])) {
        unset ($_SESSION['cart']);
        $json = "Enter Parts";
        $_SESSION['cart'] = [];
    } 
    else 
    {
        $ItemNum = $_POST["item"];
        if(!isset($_SESSION['cart']))
        { 
            $dataSet = [];
            $_SESSION['cart'] = [];
        }
            $dataSet = $_SESSION['cart'];         
            $testSet = AddItemToCart($dataSet, $ItemNum);

            if ($testSet) {
                $dataSet = $testSet;
            }
            else
            {
                $result = GetItem($ItemNum);
                if ($result)
                {
                    $dataSet[] = ["count" => 1, 
                        "part_number" => $result["data"][0]["part_number"], 
                        "upc" => $result["data"][0]["upc"], 
                        "description" => $result["data"][0]["description"], 
                        "list" => $result["data"][0]["list"]];           
                }
            }
        
        $_SESSION['cart'] = $dataSet; 
        $json = json_encode($dataSet);
    }
//-----------------------------------------------------------------------------------------------//
}
function AddItemToCart(array $dataLine, string $ItemVal): array | false
{
    foreach($dataLine as &$value) 
    {
        if ($value["part_number"] === $ItemVal || $value["upc"] === $ItemVal) {
            $set = $value["count"];
            $value["count"] = ++$set;
            return $dataLine;
        }
    }
    return false;
}
//-----------------------------------------------------------------------------------------------//
function GetItem(string $upcOrPartNum): array | false
{
    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, "http://localhost/api/v1/search/". $upcOrPartNum);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Small Shop app');
    $query = curl_exec($curl_handle);
    curl_close($curl_handle);

    $responseData  = json_decode($query, true);
    
    if (array_key_exists("data", $responseData) && !$upcOrPartNum == "") {
        return $responseData;
    }
    else
    {
        return false;
    }
}
//-----------------------------------------------------------------------------------------------//
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Search</title>
        <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
    </head>
    <body>
    
    <main class="container">
    <h1>Search</h1>

    <form method="post">

        <label for="name">
            Item
            <input name="item" id="item" autofocus />
        </label>
        <button>Search</button>
        <button name="btnClear">Clear</button>
    </form>
    <br><?php print_r($json); ?>
    <br><br>
    <a href="/index.php">home</a>
    </main>
    </body>
</html>

