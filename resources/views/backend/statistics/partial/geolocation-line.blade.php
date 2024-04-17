<script>
    document.addEventListener('DOMContentLoaded', function() {
        const data = @json($obj['geoLocationStats']['monthlyData']);
        window.generateGeolocationLineChart(data);
    });
</script>

<div style="position: relative; height:40vh; width:80vw">
    <canvas id="tides-geolocation-line"></canvas>
</div>
