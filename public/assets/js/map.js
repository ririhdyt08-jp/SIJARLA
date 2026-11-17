(() => {
  const status = document.getElementById('hotspot-status');
  if (!window.L) {
    status.textContent = 'Peta gagal dimuat. Gunakan tautan NASA Worldview.';
    document.getElementById('hotspot-alert').textContent = 'Deteksi satelit belum dapat ditampilkan.';
    return;
  }

  const reset = document.getElementById('map-reset');
  const focus = document.getElementById('map-focus');
  const months = document.getElementById('hotspot-months');
  const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    maxZoom: 18,
    attribution: '<a href="https://www.arcgis.com/home/item.html?id=10df2279f9684e4a9f6a7f08febac2a9">Esri World Imagery</a>, Maxar, Earthstar Geographics & GIS User Community'
  });
  const streets = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
  });
  const map = L.map('map', { scrollWheelZoom: false, maxZoom: 18, zoomSnap: 0.25 });
  satellite.addTo(map);
  L.control.layers({ Satelit: satellite, 'Peta jalan': streets }).addTo(map);
  const fit = bounds => map.fitBounds(bounds, { padding: [15, 15] });
  const showKalimantan = () => fit([[-4.8, 107.7], [7.8, 121.8]]);
  const showIndonesia = () => fit([[-11, 94], [6.5, 142]]);

  satellite.on('tileerror', () => {
    if (!map.hasLayer(satellite)) return;
    satellite.remove();
    streets.addTo(map);
  });
  showIndonesia();
  focus.addEventListener('click', showKalimantan);
  reset.addEventListener('click', showIndonesia);
  focus.disabled = false;
  reset.disabled = false;

  import('./hotspots.js')
    .then(({ mountHotspots }) => mountHotspots(map, months, status))
    .catch(() => {
      status.textContent = 'Lapisan interaktif gagal dimuat. Gunakan tautan NASA Worldview.';
      document.getElementById('hotspot-alert').textContent = 'Deteksi satelit belum dapat ditampilkan.';
    });
})();
