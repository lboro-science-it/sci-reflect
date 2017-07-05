<template>
    <canvas :id="_uid"></canvas>
</template>

<script>
    import Chart from 'chart.js';
    
    export default {
        props: {
            labels: {},
            values: {}
        },

        mounted() {
            new Chart(document.getElementById(this._uid).getContext('2d'), {
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
