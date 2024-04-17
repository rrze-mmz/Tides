<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.generateClipViewsLineChart( @json($obj['clipStats']));
    });
</script>

<div style="position: relative; height:80vh; width:120vw">
    <canvas id="tides-clip-views-line"></canvas>
</div>
