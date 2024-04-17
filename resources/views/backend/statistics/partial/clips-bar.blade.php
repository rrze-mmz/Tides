<script>
    document.addEventListener('DOMContentLoaded', function() {

        const data = @json($obj['clipsViews']);
        console.log(data);
        window.generateBarChart(data);
    });
</script>

<div class="flex w-full">
    <div style="position: relative; height:80vh; width:120vw">
        <canvas id="tides-bar"></canvas>
    </div>
</div>

