<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>World Heritage UNESCO Map</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
          integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
            crossorigin=""></script>
    <link href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" rel="stylesheet" crossorigin="">
    <link href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" rel="stylesheet" crossorigin="">
</head>
<body>
<div class="spinner-border text-primary position-absolute" role="status" id="spinner">
    <span class="visually-hidden">Loading...</span>
</div>
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container position-absolute top-0 end-0 p-3" style="z-index: 9999">
        <div id="primaryToast" class="toast align-items-center text-white bg-primary border-0 hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Loading map data...
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        <div id="successToast" class="toast align-items-center text-white bg-success border-0 hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Successfully loading map data.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        <div id="dangerToast" class="toast align-items-center text-white bg-danger border-0 hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Failed to load map data.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="col-12 w-100" id="map" style="height: 100vh;"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js" crossorigin="" referrerpolicy="no-referrer"></script>

<script>
    let dangerToast = new bootstrap.Toast(document.getElementById('dangerToast'));
    let successToast = new bootstrap.Toast(document.getElementById('successToast'));
    let primaryToast = new bootstrap.Toast(document.getElementById('primaryToast'));
    primaryToast.show();

    let map = L.map('map');
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1Ijoia2FjYW5nZ29yZW5nIiwiYSI6ImNrcHdsOGd6YzAxcWcycG1jNmVzZnN2NnMifQ.zr2OimomTHrE2n5tJrgfRA'
    }).addTo(map);

    $.ajax({
        url: '{{ route('api.world-heritage-list') }}',
    }).done(function (data) {
        L.geoJSON(data, {
            onEachFeature: onEachFeature,
        }).addTo(map);
        map.setView([-7.275607639638848, 112.79378788381416], 8);
        let markers = L.markerClusterGroup();
        map.addLayer(markers);
        successToast.show();
    }).fail(function (data) {
        dangerToast.show();
    }).always(function () {
        document.getElementById('spinner').remove();
        primaryToast.hide();
    });

    function onEachFeature(feature, layer) {
        if (feature.properties && feature.properties.short_description_en) {
            let template = `
                <div class="fw-bolder text-uppercase mt-2">Name</div>
                ${feature.properties.name_en}</br>
                <div class="fw-bolder text-uppercase mt-2">Short Description</div>
                ${feature.properties.short_description_en}</br>
                <div class="fw-bolder text-uppercase mt-2">Longitude</div>
                ${feature.properties.latitude}</br>
                <div class="fw-bolder text-uppercase mt-2">Latitude</div>
                ${feature.properties.longitude}</br>
                <div class="fw-bolder text-uppercase mt-2">Area hectares</div>
                ${feature.properties.area_hectares}</br>
                <div class="fw-bolder text-uppercase mt-2">Category</div>
                ${feature.properties.category}</br>
                <div class="fw-bolder text-uppercase mt-2">Country</div>
                ${feature.properties.country_en}</br>
                <div class="fw-bolder text-uppercase mt-2">Continent</div>
                ${feature.properties.continent_en}</br>
            `;
            layer.bindPopup(template);
        }
    }
</script>
</body>
</html>
