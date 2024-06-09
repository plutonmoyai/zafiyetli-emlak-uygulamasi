<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İlan Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function showForm() {
            var type = document.getElementById('type').value;
            document.getElementById('konutForm').style.display = (type === 'konut') ? 'block' : 'none';
            document.getElementById('arsaForm').style.display = (type === 'arsa') ? 'block' : 'none';
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="max-w-2xl mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-3xl font-bold text-center mb-6">İlan Ekle</h2>
            <form method="POST" action="ilan_ekle.php" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="type" class="block text-gray-700">İlan Tipi:</label>
                    <select class="w-full p-2 border border-gray-300 rounded mt-1" id="type" name="type" onchange="showForm()" required>
                        <option value="">Seçiniz</option>
                        <option value="konut">Konut</option>
                        <option value="arsa">Arsa</option>
                    </select>
                </div>

                <!-- Konut Formu -->
                <div id="konutForm" style="display:none;">
                    <h3 class="text-xl font-semibold mb-4">Konut İlanı</h3>
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700">Başlık:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="title" name="title">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">Açıklama:</label>
                        <textarea class="w-full p-2 border border-gray-300 rounded mt-1" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700">Fiyat:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="price" name="price" step="0.01">
                    </div>
                    <div class="mb-4">
                        <label for="area" class="block text-gray-700">Alan (m²):</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="area" name="area" step="0.01">
                    </div>
                    <div class="mb-4">
                        <label for="rooms" class="block text-gray-700">Oda Sayısı:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="rooms" name="rooms">
                    </div>
                    <div class="mb-4">
                        <label for="floors" class="block text-gray-700">Kat Sayısı:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="floors" name="floors">
                    </div>
                    <div class="mb-4">
                        <label for="building_age" class="block text-gray-700">Bina Yaşı:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="building_age" name="building_age">
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700">Adres:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="address" name="address">
                    </div>
                    <div class="mb-4">
                        <label for="city" class="block text-gray-700">Şehir:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="city" name="city">
                    </div>
                    <div class="mb-4">
                        <label for="district" class="block text-gray-700">İlçe:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="district" name="district">
                    </div>
                    <div class="mb-4">
                        <label for="image" class="block text-gray-700">Resim:</label>
                        <input type="file" class="w-full p-2 border border-gray-300 rounded mt-1" id="image" name="image">
                    </div>

                    <!-- Özellikler -->
                    <h4 class="text-lg font-semibold mb-2">Özellikler</h4>
                    <div class="mb-4">
                        <label class="block text-gray-700">İç Özellikler:</label>
                        <div class="flex flex-wrap">
                            <label class="mr-4"><input type="checkbox" name="features[]" value="ADSL"> ADSL</label>
                            <label class="mr-4"><input type="checkbox" name="features[]" value="Ahşap Doğrama"> Ahşap Doğrama</label>
                            <!-- Diğer özellikler için aynı yapıyı kullanabilirsiniz -->
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Dış Özellikler:</label>
                        <div class="flex flex-wrap">
                            <label class="mr-4"><input type="checkbox" name="features[]" value="Araç Şarj İstasyonu"> Araç Şarj İstasyonu</label>
                            <label class="mr-4"><input type="checkbox" name="features[]" value="24 Saat Güvenlik"> 24 Saat Güvenlik</label>
                            <!-- Diğer özellikler için aynı yapıyı kullanabilirsiniz -->
                        </div>
                    </div>
                </div>

                <!-- Arsa Formu -->
                <div id="arsaForm" style="display:none;">
                    <h3 class="text-xl font-semibold mb-4">Arsa İlanı</h3>
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700">Başlık:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="title" name="title">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">Açıklama:</label>
                        <textarea class="w-full p-2 border border-gray-300 rounded mt-1" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700">Fiyat:</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="price" name="price" step="0.01">
                    </div>
                    <div class="mb-4">
                        <label for="area" class="block text-gray-700">Alan (m²):</label>
                        <input type="number" class="w-full p-2 border border-gray-300 rounded mt-1" id="area" name="area" step="0.01">
                    </div>
                    <div class="mb-4">
                        <label for="zoning_status" class="block text-gray-700">İmar Durumu:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="zoning_status" name="zoning_status">
                    </div>
                    <div class="mb-4">
                        <label for="land_type" class="block text-gray-700">Arsa Tipi:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="land_type" name="land_type">
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700">Adres:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="address" name="address">
                    </div>
                    <div class="mb-4">
                        <label for="city" class="block text-gray-700">Şehir:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="city" name="city">
                    </div>
                    <div class="mb-4">
                        <label for="district" class="block text-gray-700">İlçe:</label>
                        <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="district" name="district">
                    </div>
                    <div class="mb-4">
                        <label for="image" class="block text-gray-700">Resim:</label>
                        <input type="file" class="w-full p-2 border border-gray-300 rounded mt-1" id="image" name="image">
                    </div>

                    <!-- Özellikler -->
                    <h4 class="text-lg font-semibold mb-2">Özellikler</h4>
                    <div class="mb-4">
                        <label class="block text-gray-700">İç Özellikler:</label>
                        <div class="flex flex-wrap">
                            <label class="mr-4"><input type="checkbox" name="features[]" value="ADSL"> ADSL</label>
                            <label class="mr-4"><input type="checkbox" name="features[]" value="Ahşap Doğrama"> Ahşap Doğrama</label>
                            <!-- Diğer özellikler için aynı yapıyı kullanabilirsiniz -->
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Dış Özellikler:</label>
                        <div class="flex flex-wrap">
                            <label class="mr-4"><input type="checkbox" name="features[]" value="Araç Şarj İstasyonu"> Araç Şarj İstasyonu</label>
                            <label class="mr-4"><input type="checkbox" name="features[]" value="24 Saat Güvenlik"> 24 Saat Güvenlik</label>
                            <!-- Diğer özellikler için aynı yapıyı kullanabilirsiniz -->
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">İlan Ekle</button>
            </form>
        </div>
    </div>
</body>
</html>