<template>
    <canvas :id="_uid"></canvas>
</template>

<script>
    import Chart from 'chart.js';
    
    export default {
        props: [
            'backgrounds',
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
                        data: this.values,
                        backgroundColor: this.backgrounds
                    }]
                },
                options: {
                    animation: {
                        animateRotate: false
                    },
                    layout: {
                        padding: 5
                    },
                    legend: {
                        display: false
                    },
                    responsive: true,
                    scale: {
                        angleLines: {
                            display: true
                        },
                        ticks: {
                            max: this.max,
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
        }
    }
</script>
