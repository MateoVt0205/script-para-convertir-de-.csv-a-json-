<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Cdn de tailwind -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Fin Cdn de tailwind -->
</head>

<?php
if (isset($_POST["submit"])) {

    if (isset($_FILES["upload_file"]) && $_FILES["upload_file"]["error"] === UPLOAD_ERR_OK) {

        $fileTempPath = $_FILES["upload_file"]["tmp_name"];
        $fileName = $_FILES["upload_file"]["name"];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileExtension === 'csv') {
            $uploadFileDir = "./uploads/";
            $NewFullPath = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTempPath, $NewFullPath)) {
                echo "<p class='text-green-600 font-bold'>Archivo subido correctamente a $NewFullPath.</p>";

                if (($handle = fopen($NewFullPath, "r")) !== FALSE) {
                    $dataArray = [];
                    $csvHeaders = array_map('trim', fgetcsv($handle, 1000, ","));

                    // Mapeo de cabecera: esto con el fin de que la información que el usuario ingrese el se asimile con los datos que necesita el woocomerce
                    // y así no tener que cambiar el código cada vez que se cambie el nombre de la cabecera en el CSV
                    $csvToBackendMap = [
                        "SKU" => "sku",
                        "Nombre Producto" => "name",
                        "Descripción Producto" => "description",
                        "Precio" => "price",
                        "Precio Promoción" => "discountPrice",
                        "Cantidad" => "quantity",
                        "Categoría Principal" => "category",
                        "Sub-Categoría" => "subcategory"
                    ];

                    $requiredHeaders = array_keys($csvToBackendMap);

                    // Validamos los campos requeridos
                    $missingHeaders = array_diff($requiredHeaders, $csvHeaders);
                    if (!empty($missingHeaders)) {
                        echo "<script>alert('Faltan columnas necesarias: " . implode(", ", $missingHeaders) . "');</script>";
                        fclose($handle);
                        exit();
                    }

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $rowAssoc = array_combine($csvHeaders, array_map('trim', $data));
                        $backendFormattedRow = [];

                        // Mapeamos cada campo del CSV al formato del wooccommerce
                        foreach ($csvToBackendMap as $csvKey => $backendKey) {
                            $backendFormattedRow[$backendKey] = $rowAssoc[$csvKey] ?? "";
                        }

                        $dataArray[] = $backendFormattedRow;
                    }
                    fclose($handle);

                    // Guardamos en el JsonData el objeto que se va a enviar al woocommerce
                    $JsonData = json_encode($dataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    echo "<pre class='bg-gray-900 text-green-400 p-4 rounded-md shadow-md'>$JsonData</pre>";
                }
            } else {
                echo "<p class='text-red-600 font-bold'>Error al mover el archivo de ubicación.</p>";
            }
        } else {
            echo "<p class='text-red-600 font-bold'>El archivo debe tener extensión .csv</p>";
        }
    } else {
        echo "<p class='text-red-600 font-bold'>Error al subir el archivo. Código: " . $_FILES["upload_file"]["error"] . "</p>";
    }
}
