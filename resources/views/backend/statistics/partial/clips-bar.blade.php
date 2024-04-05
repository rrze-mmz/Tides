<script>
    @php
        $transformedArray = [
          ['name' => 'Bayern', 'count' => $obj['geoLocationStats']['total']['total_bavaria']],
          ['name' => 'Germany', 'count' => $obj['geoLocationStats']['total']['total_germany']],
          ['name' => 'World', 'count' => $obj['geoLocationStats']['total']['total_world']],
      ];
    @endphp
    document.addEventListener('DOMContentLoaded', function() {

        const data = @json($obj['clipsViews']);
        window.generateBarChart(data);
    });
</script>

<div class="flex w-full">
    <div style="position: relative; height:80vh; width:120vw">
        <canvas id="tides-bar"></canvas>
    </div>
</div>

