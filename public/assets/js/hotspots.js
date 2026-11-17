import { VectorTile } from 'https://cdn.jsdelivr.net/npm/@mapbox/vector-tile@2.0.4/+esm';
import Pbf from 'https://cdn.jsdelivr.net/npm/pbf@4.0.1/+esm';
import Supercluster from 'https://cdn.jsdelivr.net/npm/supercluster@8.0.1/+esm';

const tileBase = 'https://gibs.earthdata.nasa.gov/wmts/epsg4326/best/VIIRS_NOAA20_Thermal_Anomalies_375m_All/default';
const categories = {
  low: { label: 'Rendah', color: '#4bcaf5' },
  nominal: { label: 'Nominal', color: '#f88426' },
  high: { label: 'Tinggi', color: '#e83b36' }
};

async function readTile(date, row, column, signal) {
  const response = await fetch(tileBase + '/' + date + '/500m/4/' + row + '/' + column + '.mvt', {
    signal: AbortSignal.any([signal, AbortSignal.timeout(12000)])
  });
  if (!response.ok) throw new Error('Tile NASA gagal dimuat');
  let bytes = await response.arrayBuffer();
  const signature = new Uint8Array(bytes, 0, Math.min(2, bytes.byteLength));
  if (signature[0] === 31 && signature[1] === 139) {
    const stream = new Blob([bytes]).stream().pipeThrough(new DecompressionStream('gzip'));
    bytes = await new Response(stream).arrayBuffer();
  }

  const tile = new VectorTile(new Pbf(new Uint8Array(bytes)));
  const points = [];
  for (const layer of Object.values(tile.layers)) {
    for (let i = 0; i < layer.length; i++) {
      const point = layer.feature(i).properties;
      if (String(point.ACQ_DATE).slice(0, 10) === date &&
          point.LATITUDE >= -12 && point.LATITUDE <= 8 && point.LONGITUDE >= 94 && point.LONGITUDE <= 142) {
        points.push(point);
      }
    }
  }
  return points;
}

function addDetail(content, label, value) {
  const line = document.createElement('p');
  const heading = document.createElement('strong');
  heading.textContent = label + ' ';
  const detail = document.createElement('span');
  detail.textContent = value;
  line.append(heading, detail);
  content.append(line);
  return detail;
}

export function mountHotspots(map, picker, status) {
  const alert = document.getElementById('hotspot-alert');
  const highOnly = document.getElementById('hotspot-high-only');
  map.attributionControl.addAttribution('<a href="https://firms.modaps.eosdis.nasa.gov/content/tutorials/gibs/">NASA FIRMS / GIBS</a>');
  map.createPane('hotspots').style.zIndex = 390;
  const renderer = L.canvas({ pane: 'hotspots', tolerance: 3 });
  const layer = L.layerGroup().addTo(map);
  const dateFormat = new Intl.DateTimeFormat('id-ID', { dateStyle: 'long', timeZone: 'UTC' });
  const monthFormat = new Intl.DateTimeFormat('id-ID', { month: 'long', year: 'numeric', timeZone: 'UTC' });
  const places = new Map();
  let lastLookup = 0;
  let lookupPending = false;
  let request;
  let current;
  let cachedMonth;

  async function findPlace(lat, lon, detail) {
    const key = lat.toFixed(3) + ',' + lon.toFixed(3);
    if (places.has(key)) {
      detail.textContent = places.get(key);
      return;
    }
    if (lookupPending || Date.now() - lastLookup < 1000) {
      detail.textContent = 'Coba klik lagi sebentar.';
      return;
    }

    lookupPending = true;
    lastLookup = Date.now();
    try {
      const query = new URLSearchParams({
        lat, lon, format: 'jsonv2', zoom: '14', addressdetails: '1', 'accept-language': 'id'
      });
      const response = await fetch('https://nominatim.openstreetmap.org/reverse?' + query, {
        signal: AbortSignal.timeout(7000)
      });
      if (!response.ok) throw new Error('Wilayah gagal dimuat');
      const result = await response.json();
      if (!result.display_name) throw new Error('Wilayah tidak ditemukan');
      const address = result.address || {};
      const locality = address.village || address.town || address.city_district || address.municipality || address.city;
      const region = [...new Set([locality, address.county, address.state, address.country].filter(Boolean))].join(', ');
      const place = region || result.display_name;
      places.set(key, place);
      detail.textContent = place;
    } catch {
      detail.textContent = 'Nama wilayah belum tersedia.';
    } finally {
      lookupPending = false;
    }
  }

  function renderPoint(point) {
    const latitude = Number(point.LATITUDE);
    const longitude = Number(point.LONGITUDE);
    const category = categories[String(point.CONFIDENCE).toLowerCase()] || { label: 'Tanpa kategori', color: '#a7aeb7' };
    const marker = L.circleMarker([latitude, longitude], {
      renderer, radius: category === categories.high ? 6 : 4, weight: 1, color: '#28211b', fillColor: category.color, fillOpacity: 1
    }).addTo(layer);
    marker.bindPopup(() => {
      const content = document.createElement('div');
      content.className = 'hotspot-popup';
      const title = document.createElement('strong');
      title.textContent = 'Titik panas VIIRS NOAA-20';
      content.append(title);

      const detectionDate = String(point.ACQ_DATE).slice(0, 10);
      const time = String(point.ACQ_TIME ?? '').replace(':', '').padStart(4, '0');
      const hasTime = point.ACQ_TIME != null && /^(?:[01]\d|2[0-3])[0-5]\d$/.test(time);
      const hour = hasTime ? ' pukul ' + time.slice(0, 2) + '.' + time.slice(2) : ' (jam tidak tersedia)';
      addDetail(content, 'Waktu deteksi', dateFormat.format(new Date(detectionDate)) + hour + ' UTC');
      addDetail(content, 'Koordinat', latitude.toFixed(5) + ', ' + longitude.toFixed(5));
      addDetail(content, 'Keyakinan', category.label);
      const link = document.createElement('a');
      link.href = 'https://www.openstreetmap.org/?mlat=' + latitude + '&mlon=' + longitude + '#map=13/' + latitude + '/' + longitude;
      link.textContent = 'Lihat lokasi di OpenStreetMap ↗';
      const place = addDetail(content, 'Wilayah terdekat', 'sedang dicari…');
      findPlace(latitude, longitude, place);
      content.append(link);
      return content;
    });
  }

  function drawMap() {
    layer.clearLayers();
    if (!current) return;
    if (highOnly.checked && !current.highIndex) {
      current.highIndex = buildIndex(current.points.filter(point => isHigh(point.properties)));
    }
    const index = highOnly.checked ? current.highIndex : current.index;
    const bounds = map.getBounds();
    const bbox = [bounds.getWest(), bounds.getSouth(), bounds.getEast(), bounds.getNorth()];
    for (const { geometry, properties } of index.getClusters(bbox, Math.floor(map.getZoom()))) {
      if (!properties.cluster) {
        renderPoint(properties);
        continue;
      }
      const [longitude, latitude] = geometry.coordinates;
      const label = properties.point_count.toLocaleString('id-ID') + ' deteksi, ' +
        properties.highCount.toLocaleString('id-ID') + ' berkeyakinan tinggi. Klik untuk memperbesar.';
      const icon = L.divIcon({
        className: properties.highCount ? 'hotspot-cluster has-high-confidence' : 'hotspot-cluster',
        html: String(properties.point_count_abbreviated),
        iconSize: [42, 42]
      });
      L.marker([latitude, longitude], { icon, title: label, alt: label }).addTo(layer).on('click', () => {
        map.setView([latitude, longitude], index.getClusterExpansionZoom(properties.cluster_id));
      });
    }
  }

  function isHigh(properties) {
    return String(properties.CONFIDENCE).toLowerCase() === 'high';
  }

  function buildIndex(points) {
    return new Supercluster({
      radius: 56, maxZoom: 17, extent: 256,
      map: properties => ({ highCount: isHigh(properties) ? 1 : 0 }),
      reduce: (total, point) => { total.highCount += point.highCount; }
    }).load(points);
  }

  function displayPoints(result, partial = false) {
    current = result;
    highOnly.disabled = false;
    drawMap();
    alert.textContent = result.high.toLocaleString('id-ID') + ' deteksi berkeyakinan tinggi' +
      (partial ? ' pada data yang sudah dimuat.' : ' pada periode terpilih.') +
      ' Pemeriksaan lapangan diperlukan untuk memastikan kebakaran.';
  }

  function showResult(month, result) {
    displayPoints(result);
    const label = monthFormat.format(new Date(month + '-01'));
    const period = picker.dataset.cutoff.startsWith(month) ? '1–' + picker.dataset.cutoff.slice(-2) + ' ' + label : label;
    status.textContent = result.total.toLocaleString('id-ID') + ' deteksi · ' + period + ' (UTC).' +
      (result.failures ? ' Sebagian data gagal dimuat. Muat ulang halaman untuk mencoba lagi.' : '');
  }

  async function loadMonth() {
    const month = picker.value;
    request?.abort();
    current = null;
    highOnly.disabled = true;
    drawMap();
    if (cachedMonth?.month === month) {
      showResult(month, cachedMonth);
      return;
    }

    request = new AbortController();
    const { signal } = request;
    const [year, monthNumber] = month.split('-').map(Number);
    const label = monthFormat.format(new Date(month + '-01'));
    const days = new Date(Date.UTC(year, monthNumber, 0)).getUTCDate();
    const lastDay = picker.dataset.cutoff.startsWith(month) ? Number(picker.dataset.cutoff.slice(-2)) : days;
    const seen = new Set();
    const points = [];
    let failures = 0;
    let high = 0;
    alert.textContent = 'Memeriksa deteksi untuk ' + label + '…';

    // Start with the latest day and show an initial preview while the rest of the month loads.
    for (let day = lastDay; day >= 1; day--) {
      const date = month + '-' + String(day).padStart(2, '0');
      status.textContent = 'Memuat ' + label + ' · ' + (lastDay - day) + ' dari ' + lastDay + ' hari diperiksa.';
      const tasks = [4, 5].flatMap(row => [15, 16, 17].map(column => readTile(date, row, column, signal)));
      const tiles = await Promise.allSettled(tasks);
      if (signal.aborted) return;

      for (const tile of tiles) {
        if (tile.status === 'rejected') {
          failures++;
          continue;
        }
        for (const point of tile.value) {
          const key = date + ':' + (point.UID ?? [point.LATITUDE, point.LONGITUDE, point.ACQ_TIME].join(','));
          if (seen.has(key)) continue;
          seen.add(key);
          const { LATITUDE, LONGITUDE, ACQ_DATE, ACQ_TIME, CONFIDENCE } = point;
          const properties = { LATITUDE, LONGITUDE, ACQ_DATE, ACQ_TIME, CONFIDENCE };
          if (isHigh(properties)) high++;
          points.push({
            type: 'Feature',
            geometry: { type: 'Point', coordinates: [Number(LONGITUDE), Number(LATITUDE)] },
            properties
          });
        }
      }
      if (!current && points.length && day > 1) {
        const preview = points.slice();
        displayPoints({ points: preview, index: buildIndex(preview), high }, true);
      }
    }

    if (failures === lastDay * 6) {
      status.textContent = 'Data ' + label + ' gagal dimuat. Muat ulang halaman untuk mencoba lagi.';
      alert.textContent = 'Data belum tersedia untuk periode ini.';
      return;
    }
    const result = {
      month,
      points,
      index: buildIndex(points),
      total: points.length,
      high,
      failures
    };
    if (!failures) cachedMonth = result;
    showResult(month, result);
  }

  map.on('moveend', drawMap);
  highOnly.addEventListener('change', drawMap);
  const url = new URL(location.href);
  const requested = url.searchParams.get('bulan');
  picker.value = [...picker.options].some(option => option.value === requested) ? requested : picker.dataset.cutoff.slice(0, 7);
  picker.disabled = false;
  picker.addEventListener('change', () => {
    url.searchParams.set('bulan', picker.value);
    history.replaceState(null, '', url);
    loadMonth();
  });
  loadMonth();
}
