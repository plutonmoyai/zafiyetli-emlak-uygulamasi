<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İlan Ekle</title>
    <script>
        function showForm() {
            var type = document.getElementById('type').value;
            if (type === 'konut') {
                document.getElementById('konutForm').style.display = 'block';
                document.getElementById('arsaForm').style.display = 'none';
            } else if (type === 'arsa') {
                document.getElementById('konutForm').style.display = 'none';
                document.getElementById('arsaForm').style.display = 'block';
            } else {
                document.getElementById('konutForm').style.display = 'none';
                document.getElementById('arsaForm').style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <h2>İlan Ekle</h2>
    <form method="POST" action="ilan_ekle.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="type">İlan Tipi:</label>
            <select class="form-control" id="type" name="type" onchange="showForm()" required>
                <option value="">Seçiniz</option>
                <option value="konut">Konut</option>
                <option value="arsa">Arsa</option>
            </select>
        </div>

        <!-- Konut Formu -->
        <div id="konutForm" style="display:none;">
            <h3>Konut İlanı</h3>
            <div class="form-group">
                <label for="title">Başlık:</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="description">Açıklama:</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="price">Fiyat:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01">
            </div>
            <div class="form-group">
                <label for="area">Alan (m²):</label>
                <input type="number" class="form-control" id="area" name="area" step="0.01">
            </div>
            <div class="form-group">
                <label for="rooms">Oda Sayısı:</label>
                <input type="number" class="form-control" id="rooms" name="rooms">
            </div>
            <div class="form-group">
                <label for="floors">Kat Sayısı:</label>
                <input type="number" class="form-control" id="floors" name="floors">
            </div>
            <div class="form-group">
                <label for="building_age">Bina Yaşı:</label>
                <input type="number" class="form-control" id="building_age" name="building_age">
            </div>
            <div class="form-group">
                <label for="address">Adres:</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="form-group">
                <label for="city">Şehir:</label>
                <input type="text" class="form-control" id="city" name="city">
            </div>
            <div class="form-group">
                <label for="district">İlçe:</label>
                <input type="text" class="form-control" id="district" name="district">
            </div>
            <div class="form-group">
                <label for="image">Resim:</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>

            <!-- Özellikler -->
            <h4>Özellikler</h4>
            <div class="form-group">
                <label>İç Özellikler:</label><br>
                <label><input type="checkbox" name="features[]" value="ADSL"> ADSL</label>
                <label><input type="checkbox" name="features[]" value="Ahşap Doğrama"> Ahşap Doğrama</label>
                <!-- Diğer özellikler için aynı yapıyı kullanabilirsiniz -->
            </div>
            <div class="form-group">
                <label>Dış Özellikler:</label><br>
                <label><input type="checkbox" name="features[]" value="Araç Şarj İstasyonu"> Araç Şarj İstasyonu</label>
                <label><input type="checkbox" name="features[]" value="24 Saat Güvenlik"> 24 Saat Güvenlik</label>
                <!-- Diğer özellikler için aynı yapıyı kullanabilirsiniz -->
            </div>
        </div>

        <!-- Arsa Formu -->
        <div id="arsaForm" style="display:none;">
            <h3>Arsa İlanı</h3>
            <div class="form-group">
                <label for="title">Başlık:</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="description">Açıklama:</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="price">Fiyat:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01">
            </div>
            <div class="form-group">
                <label for="area">Alan (m²):</label>
                <input type="number" class="form-control" id="area" name="area" step="0.01">
            </div>
            <div class="form-group">
                <label for="zoning_status">İmar Durumu:</label>
                <input type="text" class="form-control" id="zoning_status" name="zoning_status">
            </div>
            <div class="form-group">
                <label for="land_type">Arsa Tipi:</label>
                <input type="text" class="form-control" id="land_type" name="land_type">
            </div>
            <div class="form-group">
                <label for="address">Adres:</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="form-group">
                <label for="city">Şehir:</label>
                <input type="text" class="form-control" id="city" name="city">
            </div>
            <div class="form-group">
                <label for="district">İlçe:</label>
                <input type="text" class="form-control" id="district" name="district">
            </div>
            <div class="form-group">
                <label for="image">Resim:</label>
                <input type="file" class="form-control-file" id="image" name="image">
            </div>

            <!-- Özellikler -->
            <h4>Özellikler</h4>
            <div class="form-group">
                <label>İç Özellikler:</label><br>
                <label><input type="checkbox" name="features[]" value="ADSL"> ADSL</label>
                <label><input type="checkbox" name="features[]" value="Ahşap Doğrama"> Ahşap Doğrama</label>
                <!-- Diğer özellikler için aynı yapıyı kullanabilirsiniz -->
            </div>
            <div class="form-group">
                <label>Dış Özellikler:</label><br>
                <label><input type="checkbox" name="features[]" value="Araç Şarj İstasyonu"> Araç Şarj İstasyonu</label>
                <label><input type="checkbox" name="features[]" value="24 Saat Güvenlik"> 24 Saat Güvenlik</label>
                <!-- Diğer özellikler için aynı yapıyı kullanabilirsiniz -->
            </div>
        </div>

        <button type="submit" class="btn btn-primary">İlan Ekle</button>
    </form>
</body>
</html>
