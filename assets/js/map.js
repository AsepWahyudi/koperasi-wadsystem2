     var m, 
            cm,
            pku_center = new L.LatLng(-6.8994769,109.6720137);

        function showCoordinates(e) {
            alert(e.latlng);
        }

        function centerMap(e) {
            m.panTo(e.latlng);
        }

        function zoomIn(e) {
            m.zoomIn();
        }

        function zoomOut(e) {
            m.zoomOut();
        }

        m = L.map('map', {
            center: pku_center,
            zoom: 12,
            defaultExtentControl: true,
            fullscreenControl: {
                pseudoFullscreen: false
            },
            contextmenu: true,
            contextmenuWidth: 140
        }).setView(pku_center, 12);

        //var mapControlsContainer = document.getElementsByClassName("leaflet-control")[0];
        // var header = document.getElementById("header");
        // mapControlsContainer.appendChild(header);

        //var sidebar = L.control.sidebar({ container: 'sidebar', position: 'right' }).addTo(m);

        var markerClusters = L.markerClusterGroup();

        // var listings = document.getElementById('listings');

        // function setActive(el) {
        //   var siblings = listings.getElementsByTagName('li');

        //   for (var i = 0; i < siblings.length; i++) {
        //     siblings[i].className = siblings[i].className
        //     .replace(/active/, '').replace(/\s\s*$/, '');
        //   }
        //   el.className += ' active';
        // }

        var featuresLayer = L.geoJson(markers, {
            pointToLayer: function(feature, latlng){
            var myIcon = L.icon({
              iconUrl: 'assets/js/Other.png',
              iconSize: [46, 42],
              iconAnchor: [19, 25],
              popupAnchor: [6, -16]
            });
            var marker = L.marker(latlng, {icon: myIcon});
            markerClusters.addLayer(marker);
            var popup = '<h6 class="btn btn-success btn-block"><label style="margin-bottom:5px;"><input type="checkbox" id="common_selector" class="common_selector brand" value="'+ feature.properties.name +'" checked hidden="true" > '+ feature.properties.name +'</label></h6>'+
                                '<table class="table table-responsive-sm" style="margin-bottom:0px;">'+
                                    '<tbody>'+
                                        '<tr>'+
                                            '<th>Telepon</th>'+
                                            '<td>'+ feature.properties.telp +'</td>'+
                                        '</tr>'+
                                        '<tr>'+
                                            '<th>Angsuran</th>'+
                                            '<td>Rp. '+ feature.properties.angsuran +'</td>'+
                                        '</tr>'+
                                    '</tbody>'+
                                '</table>'+
                                '<a class="btn btn-info btn-sm btn-block" href="https://www.google.co.id/maps/dir//'+feature.properties.lat+','+feature.properties.lng+'/@'+feature.properties.lat+','+feature.properties.lng+',19z" target="_blank"><i class="fa fa-street-view"></i> Petunjuk Arah</a>';
            marker.bindPopup(popup);
            label=String(feature.properties.name);
            marker.bindTooltip(label, {permanent: true, opacity: 1, direction: 'bottom', offset: [5, 17]}).openTooltip();
            var listStyle = '<a><h6 class="list-group-item-heading nama">'+feature.properties.name+'</h6>'+
                            '<span class="list-group-item-text jenis">'+ feature.properties.jenis_nama_barang +'</span><br>'+
                            '<small class="alamat">'+feature.properties.alamat+'</small></a>';
            /*var link = listings.appendChild(document.createElement('li'));
            link.className = 'list-group-item';
            link.innerHTML = listStyle;
            link.onclick = function() {
                markerClusters.zoomToShowLayer(marker, function() {
                     marker.openPopup();
                     filter_data();
                     clickZoom();
                });
                return true;
            };*/
            
            return marker;
          }
        }).addTo(m);

        /*//get latlng
        m.on('click', clickZoom);
        m.on('popupopen', clickZoom);
        //m.on('popupclose', filter_data_clear);

        function clickZoom(e) {
            var px = m.project(e.popup._latlng); // find the pixel location on the map where the popup anchor is
            px.y -= e.popup._container.clientHeight/2 // find the height of the popup container, divide by 2, subtract from the Y axis of marker location
            m.panTo(m.unproject(px)); // pan to new center
        }  */     

        //L.control.betterscale().addTo(m);

        var GoogleHybrid = new L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',{
            subdomains:['mt0','mt1','mt2','mt3']
        }).addTo(m);

        var GoogleStreets = new L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
            subdomains:['mt0','mt1','mt2','mt3']
        });
        var GoogleStreets2 = new L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
            subdomains:['mt0','mt1','mt2','mt3']
        });

        //var EsriWorldImagery = new L.tileLayer.provider('Esri.WorldImagery').addTo(m);

        //var EsriWorldStreetMap = new L.tileLayer.provider('Esri.WorldStreetMap');
        //var EsriWorldStreetMap2 = new L.tileLayer.provider('Esri.WorldStreetMap');

        var OpenStreetMap = new L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        });

        var baseMaps =[
                        {
                            groupName: "Base Maps",
                            expanded : false,
                            layers: {
                                        " GoogleHybrid" : GoogleHybrid,
                                        " GoogleStreets" : GoogleStreets,
                                        " OpenStreetMap" : OpenStreetMap
                                    }
                        }
        ];

        var baseMapsMini ={
            "GoogleStreets2": GoogleStreets2
        };