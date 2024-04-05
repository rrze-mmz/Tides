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
        window.generatePieChart(data);
    });
</script>

<div style="position: relative; height:40vh; width:80vw">
    <canvas id="tides-pie"></canvas>
</div>
