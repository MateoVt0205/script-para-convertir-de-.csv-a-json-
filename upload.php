<?php
if (isset($_POST["submit"])) {
  
    if (isset($_FILES["upload_file"]) && $_FILES["upload_file"]["error"] === UPLOAD_ERR_OK){ //Usamos el nombre del input como variable que guardara la información del archivo

        //Agregamos la ruta temporal del archivo
        $fileTempPath = $_FILES["upload_file"]["tmp_name"];
        // echo $fileTempPath;
        $fileName = $_FILES["upload_file"]["name"];
        $fileType = $_FILES["upload_file"]["type"];
        $fileNameCmps = explode(".", $fileName); 
        $fileExtension = strtolower(end($fileNameCmps)); //Esta función convertira las palabras en mayusculas que ingrese el usuario en minusculas 
        
        //podemos hacer una validación para solo recibir archoivos csv o xlsx (Formato separado por comas o un archivo excel)
        // if($fileExtension === 'csv' || $fileExtension === 'xlsx'){
        if($fileExtension === 'csv'){   

            $uploadFileDir = "./uploads/"; 
            $NewFullPath = $uploadFileDir . $fileName; //Creamos una variable que guardara la ruta de la carpeta Uploads y el nombre del archivo

        if(move_uploaded_file($fileTempPath, $NewFullPath)){ //Usamos una función de php para mover el archivo de temporal a nuestra carpeta Uploads 
            echo "El archivo se subio correctamente a la ruta $NewFullPath.<br>";

            //Abrimso el archivo csv

            if(($handle = fopen($NewFullPath, "r")) !== FALSE) {
                $dataArray = [];
                $header = fgetcsv($handle, 1000, ","); //obtenemos los encabezados de la primera linea

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $dataArray[] = array_combine($header, $data);
                }
                fclose($handle);
                //Hacemos la conversion del archivo .csv A json

                $JsonData = json_encode($dataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);  
                //Hacemos un console.log para mirar la información del arreglo
                echo "<pre>$JsonData</pre>";
            }
            
        }
        else {
            echo "Ocurrio un error al mover el archivo de ubicación";
        }
    }
    else {
        echo "El archivo subido no cumple con el formato .csv o  .xlsx";
    }
    }
    else {
        echo "Hubo un error al subir el archivo . Código de error " . $_FILES["upload_file"]["error"];
    }
}