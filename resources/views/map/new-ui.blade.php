@extends('layouts.app')
@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
          integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
            crossorigin=""></script>
    <link href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" rel="stylesheet" crossorigin="">
    <link href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" rel="stylesheet" crossorigin="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/2.9.9/leaflet-search.min.css" integrity="sha512-8zuX58lcEgyZdtfTu5Iu9SDfadAirBVoZrJkmuZJ+/s80QZ/YTNVlEqPtE9iHqNXeCgd122pl+inU1Oxn9KXng==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .search-button {
            background: url({{ asset('assets/img/search.svg') }}) no-repeat 4px 4px #fff !important;
        }
        .search-cancel {
            background: url({{ asset('assets/img/search.svg') }}) no-repeat 0 -46px !important;;
        }
    </style>
@endsection
@section('content')
    @include('components.toast')

    <main class="content">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Map</h5>
                            <div class="spinner-border text-primary position-absolute" style="z-index: 9999; top: 0; right: 0;" role="status" id="spinner">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-12 w-100" id="map" style="height: 70vh;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="card-title mb-0">World Heritage List by County</h5>
                                <span class="badge bg-primary fw-bolder" id="countryCount"></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-country-tab" data-bs-toggle="pill" data-bs-target="#pills-country" type="button" role="tab" aria-controls="pills-country" aria-selected="true">Country</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-category-tab" data-bs-toggle="pill" data-bs-target="#pills-category" type="button" role="tab" aria-controls="pills-category" aria-selected="false">Category</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-continent-tab" data-bs-toggle="pill" data-bs-target="#pills-continent" type="button" role="tab" aria-controls="pills-continent" aria-selected="false">Continent</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-country" role="tabpanel" aria-labelledby="pills-country-tab">
                                    <div class="accordion" id="worldHeritageListAccordion" style="max-height: 70vh; overflow-y: scroll;"></div>
                                </div>
                                <div class="tab-pane fade" id="pills-category" role="tabpanel" aria-labelledby="pills-category-tab">
                                    <div class="accordion" id="categoryListAccordion" style="max-height: 70vh; overflow-y: scroll;"></div>
                                </div>
                                <div class="tab-pane fade" id="pills-continent" role="tabpanel" aria-labelledby="pills-continent-tab">
                                    <div class="accordion" id="continentListAccordion" style="max-height: 70vh; overflow-y: scroll;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js" crossorigin="" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/2.9.9/leaflet-search.min.js" integrity="sha512-lVfVkVDAJcuOZemuK6qheoesoZfB0FRoV5J5FvIsYuIq7aQL+Cj8+8tFZuAX6mvUGO8BODqg5LBzDqvtX15c6A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
            let featuresLayer = L.geoJSON(data, {
                onEachFeature: onEachFeature,
            });
            featuresLayer.addTo(map);

            map.setView([-7.275607639638848, 112.79378788381416], 8);
            let searchControl = new L.Control.Search({
                layer: featuresLayer,
                propertyName: 'name_en',
                marker: false,
                moveToLocation: function(latlng, title, map) {
                    map.flyTo([latlng.lat + 1, latlng.lng], 8);
                }
            });

            searchControl.on('search:locationfound', function(e) {
                if(e.layer._popup)
                    e.layer.openPopup();
            }).on('search:collapsed', function(e) {
                featuresLayer.eachLayer(function(layer) {
                    featuresLayer.resetStyle(layer);
                });
            });
            map.addControl(searchControl);
            document.getElementById('worldHeritageCount').innerHTML = data.features.length + ' data';
            initAccordion(data);
            console.log(data);

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
                    <div class="row m-0">
                        <div class="col-6 p-0">
                            <div class="fw-bolder text-uppercase mt-2">Longitude</div>
                            ${feature.properties.latitude}</br>
                            <div class="fw-bolder text-uppercase mt-2">Latitude</div>
                            ${feature.properties.longitude}</br>
                            <div class="fw-bolder text-uppercase mt-2">Area hectares</div>
                            ${feature.properties.area_hectares}</br>
                        </div>
                        <div class="col-6 p-0">
                            <div class="fw-bolder text-uppercase mt-2">Category</div>
                            ${feature.properties.category}</br>
                            <div class="fw-bolder text-uppercase mt-2">Country</div>
                            ${feature.properties.country_en.split(',')[0]}</br>
                            <div class="fw-bolder text-uppercase mt-2">Continent</div>
                            ${feature.properties.continent_en}</br>
                        </div>
                    </div>
                `;
                layer.bindPopup(template);
            }
        }

        let template = `
            <div class="accordion-item">
                <h2 class="accordion-header" id="{HEADER_ID}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{COLLAPSE_ID}" aria-expanded="false" aria-controls="{COLLAPSE_ID}">
                    </button>
                </h2>
                <div id="{COLLAPSE_ID}" class="accordion-collapse collapsed collapse" aria-labelledby="{HEADER_ID}" data-bs-parent="#worldHeritageListAccordion">
                    <div class="accordion-body">
                    </div>
                </div>
            </div>
        `;

        let buttonTemplate = `
            <button type="button" class="btn btn-light col-12 text-start mb-1"></button>
        `;

        function initAccordion(data) {
            initCountryAccordion(data);
            initCategoryAccordion(data);
        }

        function initCountryAccordion(data) {
            let countries = {};

            for (let i = 0; i < data.features.length; i++) {
                let countryName = data.features[i].properties.country_en.split(',')[0];
                if (countries[countryName] !== undefined) {
                    countries[countryName].push(data.features[i]);
                } else {
                    countries[countryName] = [];
                    countries[countryName].push(data.features[i]);
                }
            }

            document.getElementById('countryCount').innerHTML = Object.keys(countries).length + ' Countries';

            let worldHeritageListAccordion = document.getElementById('worldHeritageListAccordion');
            const countriesKeys = Object.keys(countries).sort();
            for (let i = 0; i < countriesKeys.length; i++) {
                let countryAccordion = createElementFromTemplate(template
                    .replaceAll('{HEADER_ID}', 'key_' + i)
                    .replaceAll('{COLLAPSE_ID}', 'collapse_' + i));

                let accordionBody = countryAccordion.querySelector('#collapse_' + i + ' .accordion-body');
                countryAccordion.querySelector("#key_" + i + " button").innerHTML = countriesKeys[i];

                for (let j = 0; j < countries[countriesKeys[i]].length; j++) {
                    let button = createElementFromTemplate(buttonTemplate);
                    button.innerHTML = countries[countriesKeys[i]][j].properties.name_en;
                    button.addEventListener('click', function () {
                        map.flyTo([countries[countriesKeys[i]][j].properties.latitude + 0.5, countries[countriesKeys[i]][j].properties.longitude], 8);
                    });
                    accordionBody.appendChild(button);
                }

                worldHeritageListAccordion.appendChild(countryAccordion);
            }
        }

        function initCategoryAccordion(data) {
            let categories = {};

            for (let i = 0; i < data.features.length; i++) {
                let categoryName = data.features[i].properties.category;
                if (categories[categoryName] !== undefined) {
                    categories[categoryName].push(data.features[i]);
                } else {
                    categories[categoryName] = [];
                    categories[categoryName].push(data.features[i]);
                }
            }
            console.log(categories);


            let categoryListAccordion = document.getElementById('categoryListAccordion');
            const categoriesKeys = Object.keys(categories).sort();
            for (let i = 0; i < categoriesKeys.length; i++) {
                let countryAccordion = createElementFromTemplate(template
                    .replaceAll('{HEADER_ID}', 'categoryKey_' + i)
                    .replaceAll('{COLLAPSE_ID}', 'categoryCollapse_' + i));

                let accordionBody = countryAccordion.querySelector('#categoryCollapse_' + i + ' .accordion-body');
                countryAccordion.querySelector("#categoryKey_" + i + " button").innerHTML = categoriesKeys[i];

                for (let j = 0; j < categories[categoriesKeys[i]].length; j++) {
                    let button = createElementFromTemplate(buttonTemplate);
                    button.innerHTML = categories[categoriesKeys[i]][j].properties.name_en;
                    button.addEventListener('click', function () {
                        map.flyTo([categories[categoriesKeys[i]][j].properties.latitude + 0.5, categories[categoriesKeys[i]][j].properties.longitude], 8);
                    });
                    accordionBody.appendChild(button);
                }

                categoryListAccordion.appendChild(countryAccordion);
            }
        }

        function createElementFromTemplate(template) {
            let div = document.createElement('div');
            div.innerHTML = template;

            return div.firstElementChild;
        }
    </script>
@endsection
