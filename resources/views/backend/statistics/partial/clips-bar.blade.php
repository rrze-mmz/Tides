<script>
    @php
        $transformedArray = [
          ['name' => 'Bayern', 'count' => $obj['geoLocationStats']['total']['total_bavaria']],
          ['name' => 'Germany', 'count' => $obj['geoLocationStats']['total']['total_germany']],
          ['name' => 'World', 'count' => $obj['geoLocationStats']['total']['total_world']],
      ];
    @endphp
    document.addEventListener('DOMContentLoaded', function() {
        const id = 'clips-bar';
        const data = @json($transformedArray);
        const mainLabel = 'Geolocation Stats';
        const type = 'bar';
        window.generateChart(id, type, data, mainLabel);
    });
</script>

<div style="width: 800px;">
    <canvas id="clips-bar"></canvas>
</div>
