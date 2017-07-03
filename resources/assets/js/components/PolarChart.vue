<template>
    <canvas id="polar-chart"></canvas>
</template>

<script>
    import Chart from 'chart.js';
    
    export default {
        props: {
            labels: {},
            values: {}
        },

        mounted() {
            new Chart(document.getElementById("polar-chart").getContext('2d'), {
                type: 'polarArea',
                data: {
                    labels: this.labels,
                    datasets: [{
                        data: this.values
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    responsive: false
                }
            });
        }
    }
</script>
