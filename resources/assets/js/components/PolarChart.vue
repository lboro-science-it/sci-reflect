<template>
    <canvas :id="_uid"></canvas>
</template>

<script>
    import Chart from 'chart.js';
    
    export default {
        props: [
            'labels',
            'max',
            'values'
        ],

        mounted() {
            new Chart(document.getElementById(this._uid).getContext('2d'), {
                type: "polarArea",
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
                    responsive: true,
                    scale: {
                        ticks: {
                            max: this.max,              // todo get this into the data
                            min: 0,
                            stepSize: 1,
                            display: false
                        }
                    }/*,
                    tooltips: {
                        custom: function(tooltip) {
                            console.log(tooltip);
                            // todo: action to display info
                        },
                        enabled: false
                    }*/
                }
            });
            console.log("Max is " + this.max);
        }
    }
</script>
