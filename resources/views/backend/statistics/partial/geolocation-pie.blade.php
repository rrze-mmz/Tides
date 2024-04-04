<script>
    @php
        $transformedArray = [
          ['name' => 'Bayern', 'count' => $obj['geoLocationStats']['total']['total_bavaria']],
          ['name' => 'Germany', 'count' => $obj['geoLocationStats']['total']['total_germany']],
          ['name' => 'World', 'count' => $obj['geoLocationStats']['total']['total_world']],
      ];
    @endphp
    document.addEventListener('DOMContentLoaded', function() {
        const data = @json($transformedArray);
        const mainLabel = 'Geolocation Stats';
        const type = 'pie';
        window.generateChart(type, data, mainLabel);
    });
</script>

<div style="width: 800px;">
    <canvas id="tides-pie"></canvas>
</div>
