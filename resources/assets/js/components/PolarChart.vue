<template>
    <canvas :id="_uid"></canvas>
</template>

<script>
    import Chart from 'chart.js';
    
    export default {
        props: [
            'enabled',
            'backgrounds',
            'labels',
            'max',
            'values'
        ],

        mounted() {
            var vueInstance = this;
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
                        ticks: {
                            max: this.max,
                            min: 0,
                            stepSize: 1,
                            display: false
                        }
                    },

                    tooltips: {
                        callbacks: {
                            title (tooltipItem, data) {
                                return data.labels[tooltipItem[tooltipItem[0].datasetIndex].index];
                            },
                            label (tooltipItem, data) {
                                if (vueInstance.enabled[tooltipItem.index]) {
                                    return ' Rating: ' + data.datasets[0].data[tooltipItem.index] + '/' + vueInstance.max;
                                } else {
                                    return ' Not available in this round';
                                }
                            } 
                        },
                        custom: function(tooltip) {
                        //    console.log(tooltip);
                            // todo: action to display info
                        }
                    }
                }
            });
        }
    }
</script>
