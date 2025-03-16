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

<body>
    <h1 class="font-bold text-3xl flex justify-center">Prueba items</h1>
    <div class="titulo mb-10 text-black font-bold">
        <h3>Bienvenido aqui podras subir el archivo Excel que necesitas, recuerda tenerlo en un formato separado por comas (.csv).</h3>
    </div>

    <div class="form ml-12">
        <form action="upload.php" method="post" enctype="multipart/form-data" class="flex gap-5 margin-2 border-2 border-gray-200 p-5">
            <div class="upload">
                <input class="rounded-full bg-gray-200 text-gray-400 font-bold py-2 px-4" type="file" name="upload_file">
            </div>
            <div class="submit bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <input type="submit" value="subir archivo" name="submit">
            </div>
        </form>
    </div>
</body>

</html>