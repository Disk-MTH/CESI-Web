<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Exemple de Page Web avec Tailwind</title>
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-8">
    <h1 class="text-4xl font-bold text-blue-600 mb-8">Bienvenue sur ma page web</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Section 1</h2>
            <p class="text-gray-600">Contenu de la section 1...</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Section 2</h2>
            <p class="text-gray-600">Contenu de la section 2...</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Section 3</h2>
            <p class="text-gray-600">Contenu de la section 3...</p>
        </div>
    </div>

    <footer class="mt-8 text-center text-gray-500">
        <p>&copy; 2024 Ma Page Web. Tous droits réservés.</p>
    </footer>
</div>

</body>
</html>
