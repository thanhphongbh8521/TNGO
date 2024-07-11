var map, geojson;
var selected, features, layer_name, layerControl;
var content;
var popup = L.popup();

var map = L.map("map", {
  center: [16.076667, 108.222778],
  zoom: 12,
  zoomControl: true,
});

// Lớp bản đồ vệ tinh
var satellite = L.tileLayer(
  "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
  {
    attribution:
      "Source: Esri, Maxar, Earthstar Geographics, CNES/Airbus DS, USDA FSA, USGS, Getmapping, Aerogrid, IGN, IGP, và cộng đồng GIS",
  }
); // Thêm lớp vệ tinh vào bản đồ ban đầu

// Lớp bản đồ đường đi
var streets = L.tileLayer(
  "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
  {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }
).addTo(map);

var overlays = L.layerGroup();
var base = L.layerGroup();
base.addLayer(streets, "Bản đồ đường đi");
base.addLayer(satellite, "Bản đồ vệ tinh");
// Tạo điều khiển lớp
var layerControl = L.control.layers().addTo(map);

// Thêm các lớp vào điều khiển lớp
layerControl.addBaseLayer(satellite, "Bản đồ vệ tinh");
layerControl.addBaseLayer(streets, "Bản đồ đường đi");

// Zoom bar
var zoom_bar = new L.Control.ZoomBar({
  position: "topleft",
}).addTo(map);
//map.addControl(new L.Control.Zoomslider());

// mouse position
L.control
  .mousePosition({
    position: "bottomleft",
    prefix: "lat : long",
  })
  .addTo(map);

//scale
L.control
  .scale({
    position: "bottomleft",
  })
  .addTo(map);
// full screen
var mapId = document.getElementById("map");

function fullScreenView() {
  mapId.requestFullscreen();
}
// geocoder
L.Control.geocoder({
  position: "topright",
}).addTo(map);

//line mesure
L.control
  .polylineMeasure({
    position: "topleft",
    unit: "kilometres",
    showBearings: true,
    clearMeasurementsOnStop: false,
    showClearControl: true,
    showUnitControl: true,
  })
  .addTo(map);
//area measure
var measureControl = new L.Control.Measure({
  position: "topleft",
});
measureControl.addTo(map);

//tìm kiếm vị trí
// var geocoder = L.Control.geocoder({
//   defaultMarkGeocode: false,
// })
//   .on("markgeocode", function (e) {
//     var bbox = e.geocode.bbox;
//     var poly = L.polygon([
//       bbox.getSouthEast(),
//       bbox.getNorthEast(),
//       bbox.getNorthWest(),
//       bbox.getSouthWest(),
//     ]).addTo(map);
//     map.fitBounds(poly.getBounds());
//   })
//   .addTo(map);

var control = L.Routing.control({
  // waypoints: [
  //   L.latLng(11.94154184041162, 108.43724128528459),
  //   L.latLng(11.853245951517795, 108.55322843599606),
  // ],
  showAlternatives: true,
  altLineOptions: {
    styles: [
      { color: "black", opacity: 0.15, weight: 9 },
      { color: "white", opacity: 0.8, weight: 6 },
      { color: "blue", opacity: 0.5, weight: 2 },
    ],
  },
  geocoder: L.Control.Geocoder.nominatim(),
}).addTo(map);

var browserControl = L.control
  .browserPrint({
    position: "topleft",
    title: "Xuất bản đồ",
    printModes: ["Portrait", "Landscape", "Auto", "Custom"],
  })
  .addTo(map);

//search
// map.addControl(
//   L.control.search({
//     position: "topleft",
//   })
// );

//legend
function legend() {
  $("#legend").empty();
  var layers = overlays.getLayers();
  //console.log(no_layers[0].options.layers);
  //console.log(no_layers);
  //var no_layers = overlays.getLayers().get('length');

  var head = document.createElement("h8");

  var txt = document.createTextNode("Legend");

  head.appendChild(txt);
  var element = document.getElementById("legend");
  element.appendChild(head);
  overlays.eachLayer(function (layer) {
    var head = document.createElement("p");

    var txt = document.createTextNode(layer.options.layers);
    //alert(txt[i]);
    head.appendChild(txt);
    var element = document.getElementById("legend");
    element.appendChild(head);
    var img = new Image();
    img.src =
      "http://localhost:8080/geoserver/wms?REQUEST=GetLegendGraphic&VERSION=1.1.0&FORMAT=image/png&WIDTH=20&HEIGHT=20&LAYER=" +
      layer.options.layers;
    var src = document.getElementById("legend");
    src.appendChild(img);
  });
}

legend();

// layer dropdown query
$(document).ready(function () {
  $.ajax({
    type: "GET",
    url: "http://localhost:8080/geoserver/wfs?request=getCapabilities",
    dataType: "xml",
    success: function (xml) {
      var select = $("#layer");
      $(xml)
        .find("FeatureType")
        .each(function () {
          //var title = $(this).find('ows:Operation').attr('name');
          //alert(title);
          var name = $(this).find("Name").text();
          //select.append("<option/><option class='ddheader' value='"+ name +"'>"+title+"</option>");
          $(this)
            .find("Name")
            .each(function () {
              var value = $(this).text();
              select.append(
                "<option class='ddindent' value='" +
                  value +
                  "'>" +
                  value +
                  "</option>"
              );
            });
        });
      //select.children(":first").text("please make a selection").attr("selected",true);
    },
  });
});

// attribute dropdown
$(function () {
  $("#layer").change(function () {
    var attributes = document.getElementById("attributes");
    var length = attributes.options.length;
    for (i = length - 1; i >= 0; i--) {
      attributes.options[i] = null;
    }

    var value_layer = $(this).val();

    attributes.options[0] = new Option("Chọn thuộc tính", "");
    //  alert(url);

    $(document).ready(function () {
      $.ajax({
        type: "GET",
        url:
          "http://localhost:8080/geoserver/wfs?service=WFS&request=DescribeFeatureType&version=1.1.0&typeName=" +
          value_layer,
        dataType: "xml",
        success: function (xml) {
          var select = $("#attributes");
          //var title = $(xml).find('xsd\\:complexType').attr('name');
          //	alert(title);
          $(xml)
            .find("xsd\\:sequence")
            .each(function () {
              $(this)
                .find("xsd\\:element")
                .each(function () {
                  var value = $(this).attr("name");
                  //alert(value);
                  var type = $(this).attr("type");
                  //alert(type);
                  if (value != "geom" && value != "the_geom") {
                    select.append(
                      "<option class='ddindent' value='" +
                        type +
                        "'>" +
                        value +
                        "</option>"
                    );
                  }
                });
            });
        },
      });
    });
  });
});

// operator combo
$("#attributes").change(function () {
  var operator = document.getElementById("operator");
  var length = operator.options.length;
  for (var i = length - 1; i >= 0; i--) {
    operator.options[i] = null;
  }

  var value_type = $(this).val();
  var value_attribute = $("#attributes option:selected").text();
  operator.options[0] = new Option("Chọn toán tử", "");

  if (
    value_type === "xsd:short" ||
    value_type === "xsd:int" ||
    value_type === "xsd:double" ||
    value_type === "xsd:long"
  ) {
    operator.options[1] = new Option("Lớn hơn", ">");
    operator.options[2] = new Option("Nhỏ hơn", "<");
    operator.options[3] = new Option("Tương đương với", "=");
    operator.options[4] = new Option("Giữa", "BETWEEN");
  } else if (value_type === "xsd:string") {
    operator.options[1] = new Option("Like", "ILike");
  }
});

$("#loadLayer").click(function () {
  var layerName = $("#layer").val();
  if (layerName) {
    addWMSLayer(layerName);
  } else {
    alert("Please select a layer first.");
  }
});

// function for finding row in the table when feature selected on map
function findRowNumber(cn1, v1) {
  var table = document.querySelector("#table");
  var rows = table.querySelectorAll("tr");
  var msg = "No such row exist";
  for (i = 1; i < rows.length; i++) {
    var tableData = rows[i].querySelectorAll("td");
    if (tableData[cn1 - 1].textContent == v1) {
      msg = i;
      break;
    }
  }
  return msg;
}

// function for loading query

function query() {
  $("#table").empty();
  if (geojson) {
    map.removeLayer(geojson);
  }

  //alert('jsbchdb');
  var layer = document.getElementById("layer");
  var value_layer = layer.options[layer.selectedIndex].value;
  //alert(value_layer);

  var attribute = document.getElementById("attributes");
  var value_attribute = attribute.options[attribute.selectedIndex].text;
  //alert(value_attribute);

  var operator = document.getElementById("operator");
  var value_operator = operator.options[operator.selectedIndex].value;
  //alert(value_operator);

  var txt = document.getElementById("value");
  var value_txt = txt.value;

  if (value_operator == "ILike") {
    value_txt = "'" + value_txt + "%25'";
    //alert(value_txt);
    //value_attribute = 'strToLowerCase('+value_attribute+')';
  } else {
    value_txt = value_txt;
    //value_attribute = value_attribute;
  }
  //alert(value_txt);

  var url =
    "http://localhost:8080/geoserver/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=" +
    value_layer +
    "&CQL_FILTER=" +
    value_attribute +
    "%20" +
    value_operator +
    "%20" +
    value_txt +
    "&outputFormat=application/json";
  //console.log(url);
  $.getJSON(url, function (data) {
    geojson = L.geoJson(data, {
      onEachFeature: onEachFeature,
    });
    geojson.addTo(map);
    map.fitBounds(geojson.getBounds());

    var col = [];
    col.push("id");
    for (var i = 0; i < data.features.length; i++) {
      for (var key in data.features[i].properties) {
        if (col.indexOf(key) === -1) {
          col.push(key);
        }
      }
    }

    var table = document.createElement("table");

    //table.setAttribute("class", "table table-bordered");
    table.setAttribute("class", "table table-hover table-striped");
    table.setAttribute("id", "table");

    var caption = document.createElement("caption");
    caption.setAttribute("id", "caption");
    caption.style.captionSide = "top";
    caption.innerHTML =
      value_layer + " (Số thuộc tính: " + data.features.length + " )";
    table.appendChild(caption);
    // CREATE HTML TABLE HEADER ROW USING THE EXTRACTED HEADERS ABOVE.

    var tr = table.insertRow(-1); // TABLE ROW.

    for (var i = 0; i < col.length; i++) {
      var th = document.createElement("th"); // TABLE HEADER.
      th.innerHTML = col[i];
      tr.appendChild(th);
    }

    // ADD JSON DATA TO THE TABLE AS ROWS.
    for (var i = 0; i < data.features.length; i++) {
      tr = table.insertRow(-1);

      for (var j = 0; j < col.length; j++) {
        var tabCell = tr.insertCell(-1);
        if (j == 0) {
          tabCell.innerHTML = data.features[i]["id"];
        } else {
          //alert(data.features[i]['id']);
          tabCell.innerHTML = data.features[i].properties[col[j]];
          //alert(tabCell.innerHTML);
        }
      }
    }

    // FINALLY ADD THE NEWLY CREATED TABLE WITH JSON DATA TO A CONTAINER.
    var divContainer = document.getElementById("table_data");
    divContainer.innerHTML = "";
    divContainer.appendChild(table);

    addRowHandlers();

    document.getElementById("map").style.height = "71%";
    document.getElementById("table_data").style.height = "29%";
    map.invalidateSize();
  });
}

// đánh dấu đối tượng địa lý trên bản đồ và bảng trên bản đồ nhấp chuột
function onEachFeature(feature, layer) {
  layer.on("click", function (e) {
    // e = event

    // Reset selected to default style
    if (selected) {
      // Reset selected to default style
      geojson.resetStyle(selected);
    }

    selected = e.target;

    selected.setStyle({
      color: "red",
    });

    if (feature) {
      console.log(feature);
      $(function () {
        $("#table td").each(function () {
          $(this).parent("tr").css("background-color", "white");
        });
      });
    }

    var table = document.getElementById("table");
    var cells = table.getElementsByTagName("td");
    var rows = document.getElementById("table").rows;
    var heads = table.getElementsByTagName("th");
    var col_no;
    for (var i = 0; i < heads.length; i++) {
      // Take each cell
      var head = heads[i];
      //alert(head.innerHTML);
      if (head.innerHTML == "id") {
        col_no = i + 1;
        //alert(col_no);
      }
    }
    var row_no = findRowNumber(col_no, feature.id);
    //alert(row_no);

    var rows = document.querySelectorAll("#table tr");

    rows[row_no].scrollIntoView({
      behavior: "smooth",
      block: "center",
    });

    $(document).ready(function () {
      $("#table td:nth-child(" + col_no + ")").each(function () {
        if ($(this).text() == feature.id) {
          $(this).parent("tr").css("background-color", "grey");
        }
      });
    });
  });
}

// tô sáng đối tượng trên bản đồ và bảng trên hàng chọn trong bảng
function addRowHandlers() {
  var rows = document.getElementById("table").rows;
  var heads = table.getElementsByTagName("th");
  var col_no;
  for (var i = 0; i < heads.length; i++) {
    // Take each cell
    var head = heads[i];
    //alert(head.innerHTML);
    if (head.innerHTML == "id") {
      col_no = i + 1;
      //alert(col_no);
    }
  }
  for (i = 0; i < rows.length; i++) {
    rows[i].onclick = (function () {
      return function () {
        //featureOverlay.getSource().clear();
        if (geojson) {
          geojson.resetStyle();
        }
        $(function () {
          $("#table td").each(function () {
            $(this).parent("tr").css("background-color", "white");
          });
        });
        var cell = this.cells[col_no - 1];
        var id = cell.innerHTML;

        $(document).ready(function () {
          $("#table td:nth-child(" + col_no + ")").each(function () {
            if ($(this).text() == id) {
              $(this).parent("tr").css("background-color", "grey");
            }
          });
        });

        features = geojson.getLayers();

        for (i = 0; i < features.length; i++) {
          if (features[i].feature.id == id) {
            //alert(features[i].feature.id);
            //featureOverlay.getSource().addFeature(features[i]);
            selected = features[i];
            selected.setStyle({
              color: "red",
            });
            map.fitBounds(selected.getBounds());
            console.log(selected.getBounds());
          }
        }

        //alert("id:" + id);
      };
    })(rows[i]);
  }
}

//danh sách wms_layers_ trong cửa sổ khi nhấp vào nút

function wms_layers() {
  $("#wms_layers_window").modal({ backdrop: false });
  //$("#wms_layers_window").draggable();
  $("#wms_layers_window").modal("show");

  $(document).ready(function () {
    $.ajax({
      type: "GET",
      url: "http://localhost:8080/geoserver/wms?request=getCapabilities",
      dataType: "xml",
      success: function (xml) {
        $("#table_wms_layers").empty();
        // console.log("here");
        $("<tr></tr>")
          .html("<th>Name</th><th>Title</th><th>Abstract</th>")
          .appendTo("#table_wms_layers");
        $(xml)
          .find("Layer")
          .find("Layer")
          .each(function () {
            var name = $(this).children("Name").text();
            // alert(name);
            //var name1 = name.find('Name').text();
            //alert(name);
            var title = $(this).children("Title").text();

            var abst = $(this).children("Abstract").text();
            //   alert(abst);

            //   alert('test');
            $("<tr></tr>")
              .html(
                "<td>" +
                  name +
                  "</td><td>" +
                  title +
                  "</td><td>" +
                  abst +
                  "</td>"
              )
              .appendTo("#table_wms_layers");
            //document.getElementById("table_wms_layers").setAttribute("class", "table-success");
          });
        addRowHandlers1();
      },
    });
  });

  function addRowHandlers1() {
    //alert('knd');
    var rows = document.getElementById("table_wms_layers").rows;
    var table = document.getElementById("table_wms_layers");
    var heads = table.getElementsByTagName("th");
    var col_no;
    for (var i = 0; i < heads.length; i++) {
      // Take each cell
      var head = heads[i];
      //alert(head.innerHTML);
      if (head.innerHTML == "Name") {
        col_no = i + 1;
        //alert(col_no);
      }
    }
    for (i = 0; i < rows.length; i++) {
      rows[i].onclick = (function () {
        return function () {
          $(function () {
            $("#table_wms_layers td").each(function () {
              $(this).parent("tr").css("background-color", "white");
            });
          });
          var cell = this.cells[col_no - 1];
          layer_name = cell.innerHTML;
          // alert(layer_name);

          $(document).ready(function () {
            $("#table_wms_layers td:nth-child(" + col_no + ")").each(
              function () {
                if ($(this).text() == layer_name) {
                  $(this).parent("tr").css("background-color", "grey");
                }
              }
            );
          });

          //alert("id:" + id);
        };
      })(rows[i]);
    }
  }
}
// thêm lớp wms vào bản đồ khi nhấp vào nút
function add_layer() {
  //	alert("jd");

  //alert(layer_name);
  //map.removeControl(layerSwitcher);

  var name = layer_name.split(":");
  //alert(layer_name);
  var layer_wms = L.tileLayer
    .wms("http://localhost:8080/geoserver/wms?", {
      layers: layer_name,
      transparent: "true",
      format: "image/png",
    })
    .addTo(map);
  //layerControl.addOverlay(india_district,"india_district");

  layerControl.addOverlay(layer_wms, layer_name);
  overlays.addLayer(layer_wms, layer_name);

  $(document).ready(function () {
    $.ajax({
      type: "GET",
      url: "http://localhost:8080/geoserver/wms?request=getCapabilities",
      dataType: "xml",
      success: function (xml) {
        $(xml)
          .find("Layer")
          .find("Layer")
          .each(function () {
            var name = $(this).children("Name").text();
            // alert(name);
            if (name == layer_name) {
              // use this for getting the lat long of the extent
              var bbox1 = $(this)
                .children("EX_GeographicBoundingBox")
                .children("southBoundLatitude")
                .text();
              var bbox2 = $(this)
                .children("EX_GeographicBoundingBox")
                .children("westBoundLongitude")
                .text();
              var bbox3 = $(this)
                .children("EX_GeographicBoundingBox")
                .children("northBoundLatitude")
                .text();
              var bbox4 = $(this)
                .children("EX_GeographicBoundingBox")
                .children("eastBoundLongitude")
                .text();
              var southWest = L.latLng(bbox1, bbox2);
              var northEast = L.latLng(bbox3, bbox4);
              var bounds = L.latLngBounds(southWest, northEast);
              map.fitBounds(bounds);

              // use below code for getting the extent in the projection defined in geoserver

              /* $(this).find('BoundingBox').each(function(){
                         if ($(this).attr('CRS') != "CRS:84" ){
                         var bbox1 = $(this).attr('minx');
                         var bbox2 = $(this).attr('miny');
                         var bbox3 = $(this).attr('maxx');
                         var bbox4 = $(this).attr('maxy');
                         var southWest = L.latLng(bbox1, bbox2);
                         var northEast = L.latLng(bbox3, bbox4);
                         var bounds = L.latLngBounds(southWest, northEast);
                          map.fitBounds(bounds);
                         }
                         });*/

              //  alert($(this).children('EX_GeographicBoundingBox').text());
              if (bounds != undefined) {
                alert(layer_name + " đã được thêm vào bản đồ");
              }
            }
          });
      },
    });
  });

  legend();
}

function close_wms_window() {
  layer_name = undefined;
}
// hàm khi click vào getinfo
function info() {
  if (document.getElementById("info_btn").innerHTML == "☰ Kích hoạt GetInfo") {
    document.getElementById("info_btn").innerHTML = "☰ Ẩn kích hoạt GetInfo";
    document
      .getElementById("info_btn")
      .setAttribute("class", "btn btn-danger btn-sm");
    map.on("click", getinfo);
  } else {
    map.off("click", getinfo);
    document.getElementById("info_btn").innerHTML = "☰ Kích hoạt GetInfo";
    document
      .getElementById("info_btn")
      .setAttribute("class", "btn btn-success btn-sm");
  }
}

// getinfo function
function getinfo(e) {
  //var url1 = test.getFeatureInfoUrl(e.latlng);
  //console.log(url1);

  var point = map.latLngToContainerPoint(e.latlng, map.getZoom());

  var bbox = map.getBounds().toBBoxString();
  var size = map.getSize();
  var height = size.y;
  var width = size.x;
  var x = point.x;
  var y = point.y;

  if (content) {
    content = "";
  }

  overlays.eachLayer(function (layer) {
    var url =
      "http://localhost:8080/geoserver/wms?SERVICE=WMS&VERSION=1.1.0&REQUEST=GetFeatureInfo&FORMAT=image%2Fpng&TRANSPARENT=true&QUERY_LAYERS=" +
      layer.options.layers +
      "&LAYERS=" +
      layer.options.layers +
      "&INFO_FORMAT=text%2Fhtml&X=" +
      x +
      "&Y=" +
      y +
      "&CRS=EPSG%3A4326&STYLES=&WIDTH=" +
      width +
      "&HEIGHT=" +
      height +
      "&BBOX=" +
      bbox;
    console.log(url);
    $.get(url, function (data) {
      //content.push(data);

      content += data;
      //console.log(content);

      popup.setContent(content);
      popup.setLatLng(e.latlng);
      map.openPopup(popup);
    });
  });
}

// clear function
function clear_all() {
  document.getElementById("map").style.height = "100%";
  document.getElementById("table_data").style.height = "0%";
  map.invalidateSize();
  $("#table").empty();
  $("#legend").empty();
  //$('#table1').empty();
  if (geojson) {
    map.removeLayer(geojson);
  }
  map.flyTo([23.0, 82.0], 4);

  document.getElementById("query_panel_btn").innerHTML = "☰ Mở bảng truy vấn";
  document
    .getElementById("query_panel_btn")
    .setAttribute("class", "btn btn-success btn-sm");

  document.getElementById("query_tab").style.width = "0%";
  document.getElementById("map").style.width = "100%";
  document.getElementById("map").style.left = "0%";
  document.getElementById("query_tab").style.visibility = "hidden";
  document.getElementById("table_data").style.left = "0%";

  document.getElementById("legend_btn").innerHTML = "☰ Hiển thị chú giải";
  document.getElementById("legend").style.width = "0%";
  document.getElementById("legend").style.visibility = "hidden";
  document.getElementById("legend").style.height = "0%";

  map.off("click", getinfo);
  document.getElementById("info_btn").innerHTML = "☰ Kích hoạt GetInfo";
  document
    .getElementById("info_btn")
    .setAttribute("class", "btn btn-success btn-sm");

  overlays.eachLayer(function (layer) {
    map.removeLayer(layer);
    layerControl.removeLayer(layer);
    overlays.removeLayer(layer);
  });
  overlays.clearLayers();

  map.invalidateSize();
}

function show_hide_querypanel() {
  if (document.getElementById("query_tab").style.visibility == "hidden") {
    document.getElementById("query_panel_btn").innerHTML = "☰ Ẩn bảng truy vấn";
    document
      .getElementById("query_panel_btn")
      .setAttribute("class", "btn btn-danger btn-sm");
    document.getElementById("query_tab").style.visibility = "visible";
    document.getElementById("query_tab").style.width = "20%";
    document.getElementById("map").style.width = "79%";
    document.getElementById("map").style.left = "20%";

    document.getElementById("table_data").style.left = "20%";
    map.invalidateSize();
  } else {
    document.getElementById("query_panel_btn").innerHTML = "☰ Mở bảng truy vấn";
    document
      .getElementById("query_panel_btn")
      .setAttribute("class", "btn btn-success btn-sm");
    document.getElementById("query_tab").style.width = "0%";
    document.getElementById("map").style.width = "100%";
    document.getElementById("map").style.left = "0%";
    document.getElementById("query_tab").style.visibility = "hidden";
    document.getElementById("table_data").style.left = "0%";

    map.invalidateSize();
  }
}

function show_hide_legend() {
  if (document.getElementById("legend").style.visibility == "hidden") {
    document.getElementById("legend_btn").innerHTML = "☰ Hide Legend";
    document.getElementById("legend").style.visibility = "visible";
    document.getElementById("legend").style.width = "15%";

    document.getElementById("legend").style.height = "38%";
    map.invalidateSize();
  } else {
    document.getElementById("legend_btn").innerHTML = "☰ Show Legend";
    document.getElementById("legend").style.width = "0%";
    document.getElementById("legend").style.visibility = "hidden";
    document.getElementById("legend").style.height = "0%";

    map.invalidateSize();
  }
}

// định vị người dung=
let marker, circle, zoomed;

navigator.geolocation.watchPosition(success, error);

function success(pos) {
  const lat = pos.coords.latitude;
  const lng = pos.coords.longitude;
  const accuracy = pos.coords.accuracy;

  if (marker) {
    map.removeLayer(marker);
    map.removeLayer(circle);
  }
  // Removes any existing marker and circule (new ones about to be set)

  marker = L.marker([lat, lng]).addTo(map);
  circle = L.circle([lat, lng], { radius: accuracy }).addTo(map);
  // Adds marker to the map and a circle for accuracy

  if (!zoomed) {
    zoomed = map.fitBounds(circle.getBounds());
  }
  // Set zoom to boundaries of accuracy circle

  map.setView([lat, lng]);
  // Set map focus to current user position
}

function error(err) {
  if (err.code === 1) {
    alert("Vui lòng cho phép truy cập vị trí địa lý");
  } else {
    alert("Không thể có được vị trí hiện tại");
  }
}

// URL của GeoServer WFS
var geoserverURL =
  "http://your-geoserver-url/geoserver/your-workspace/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=your-workspace:your-layer&outputFormat=application/json";

// Hàm tải dữ liệu từ GeoServer
function loadGeoServerData(url, callback) {
  fetch(url)
    .then((response) => response.json())
    .then((data) => callback(data))
    .catch((error) =>
      console.error("Error loading data from GeoServer:", error)
    );
}

// Thêm layer group cho các trạm thu phí
var tollStationLayer = L.geoJSON(null, {
  onEachFeature: function (feature, layer) {
    layer.bindPopup(feature.properties.name);
  },
}).addTo(map);

// Hàm lọc các trạm thu phí theo loại đường
function filterTollStations(roadType) {
  loadGeoServerData(geoserverURL, function (data) {
    tollStationLayer.clearLayers();
    var filtered = {
      type: "FeatureCollection",
      features: data.features.filter(function (feature) {
        return feature.properties.roadType === roadType;
      }),
    };
    tollStationLayer.addData(filtered);
  });
}

// Tải và hiển thị tất cả các trạm thu phí ban đầu
loadGeoServerData(geoserverURL, function (data) {
  tollStationLayer.addData(data);
});

// Hàm hiển thị tất cả các trạm
function showAllTollStations() {
  loadGeoServerData(geoserverURL, function (data) {
    tollStationLayer.clearLayers();
    tollStationLayer.addData(data);
  });
}

// Gán hàm showAllTollStations cho nút 'Tất cả'
document.querySelector('button[onclick="showAllTollStations()"]').onclick =
  showAllTollStations;
