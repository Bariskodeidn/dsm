<script>
    const chartColors_dashboard = ["#5E72E4", "#34495e", "#e91e63"];
    const colors_dashboard = {
        mutedColor: "#8898aa",
        borderColor: "#e3e3e3",
        chartTheme: "light",
    };
    const base_dashboard = {
        defaultFontFamily: "inherit",
    };

    var areaChartOptions = {
        series: [{
            name: "Pendapatan",
            data: <?= $json_pendapatan ?>
        }, {
            name: "Biaya",
            data: <?= $json_biaya ?>
        }, {
            name: "Profit",
            data: <?= $json_laba_rugi ?>
        }, ],
        chart: {
            type: "area",
            height: 350,
            stacked: false,
            toolbar: {
                show: true
            },
            zoom: {
                enabled: false
            },
            background: "transparent"
        },
        theme: {
            mode: colors_dashboard.chartTheme
        },
        xaxis: {
            categories: <?= $json_categories ?>,
            labels: {
                style: {
                    colors: colors_dashboard.mutedColor,
                    cssClass: "text-muted",
                    fontFamily: base_dashboard.defaultFontFamily,
                }
            },
            axisBorder: {
                show: true
            },
            tooltip: {
                enabled: true
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: colors_dashboard.mutedColor,
                    cssClass: "text-muted",
                    fontFamily: base_dashboard.defaultFontFamily,
                }
            }
        },
        stroke: {
            curve: "smooth",
            lineCap: "round",
            width: 2
        },
        fill: {
            type: "solid",
            opacity: 0.3
        },
        markers: {
            size: 4,
            strokeColors: "#fff",
            strokeWidth: 2,
        },
        colors: chartColors_dashboard,
        dataLabels: {
            enabled: false
        },
        grid: {
            borderColor: colors_dashboard.borderColor,
            yaxis: {
                lines: {
                    show: true
                }
            },
            xaxis: {
                lines: {
                    show: false
                }
            }
        },
        legend: {
            position: "top",
            labels: {
                colors: colors_dashboard.mutedColor
            }
        },
        tooltip: {
            y: {
                formatter: val => new Intl.NumberFormat('id-ID').format(val)
            }
        }
    };

    var areaChart = new ApexCharts(document.querySelector("#areaChart"), areaChartOptions);
    areaChart.render();

    // Get the SVG element with the ID "gauge1"
    var svgg1 = document.getElementById("gauge1");
    var gauge1;

    // Ensure base colors are defined, or provide placeholders for demonstration
    // In a real application, 'base' would typically be defined elsewhere (e.g., a global JS object or CSS variables)
    var base = base || { // Fallback if 'base' is not defined
        primaryColor: '#3f51b5', // Blue
        successColor: '#28a745', // Green
        warningColor: '#ffc107', // Yellow
        dangerColor: '#e91e63' // Red
    };

    // Define the max and value for gauge1 from PHP variables
    var gauge1Max = <?= $perusahaan->kuota_invoice ?>;
    var gauge1Value = <?= $total_invoice ?>;

    // Function to initialize and configure a single gauge
    function initializeGauge(elementId, gaugeValue, gaugeMax) {
        var svgElement = document.getElementById(elementId);
        if (svgElement) {
            return Gauge(svgElement, {
                max: gaugeMax,
                dialStartAngle: -90,
                dialEndAngle: -90.001, // Creates a full circle
                value: gaugeValue,
                showValue: true,
                label: function(value) {
                    // return Math.round(100 * value) / gaugeMax;
                    return Math.round(100 * value) / gaugeMax + '%';
                },
                color: function(value) {
                    // Set color based on percentage of the max value
                    if (value < (0.10 * gaugeMax)) { // Less than 10% of max
                        return base.primaryColor;
                    } else if (value < (0.40 * gaugeMax)) { // Less than 40% of max
                        return base.primaryColor;
                    } else if (value < (0.60 * gaugeMax)) { // Less than 60% of max
                        return base.warningColor;
                    } else { // 60% or more of max
                        return base.dangerColor;
                    }
                }
            });
        }
        return null; // Return null if element not found
    }


    // Initialize gauge1
    gauge1 = initializeGauge("gauge1", gauge1Value, gauge1Max);
    if (gauge1) {
        // A self-calling function to animate gauge1 (if desired, based on your original code)
        (function animateGauge1() {
            gauge1.setValue(gauge1Value);
            gauge1.setValueAnimated(gauge1Value, 1); // Animate to the actual total_invoice value over 1 second
            window.setTimeout(animateGauge1, 6000);
        })();
    }

    // Initialize gauge2 with its own PHP variables
    var gauge2 = initializeGauge("gauge2", <?= $total_memo ?>, <?= $perusahaan->kuota_memo ?>);
    if (gauge2) {
        // A self-calling function to animate gauge1 (if desired, based on your original code)
        (function animateGauge2() {
            gauge1.setValue(<?= $total_memo ?>);
            gauge1.setValueAnimated(<?= $total_memo ?>, 1); // Animate to the actual total_invoice value over 1 second
            window.setTimeout(animateGauge2, 6000);
        })();
    }
    // You can add specific animations or logic for gauge2 here if needed

    // Initialize gauge3 with its own PHP variables
    var gauge3 = initializeGauge("gauge3", <?= $total_pengajuan ?>, <?= $perusahaan->kuota_pengajuan_biaya ?>);
    if (gauge3) {
        // A self-calling function to animate gauge1 (if desired, based on your original code)
        (function animateGauge3() {
            gauge1.setValue(<?= $total_pengajuan ?>);
            gauge1.setValueAnimated(<?= $total_pengajuan ?>, 1); // Animate to the actual total_invoice value over 1 second
            window.setTimeout(animateGauge3, 6000);
        })();
    }

    // Initialize gauge4 with its own PHP variables
    var gauge4 = initializeGauge("gauge4", <?= $total_user ?>, <?= $perusahaan->kuota_user ?>);
    if (gauge4) {
        // A self-calling function to animate gauge1 (if desired, based on your original code)
        (function animateGauge4() {
            gauge1.setValue(<?= $total_user ?>);
            gauge1.setValueAnimated(<?= $total_user ?>, 1); // Animate to the actual total_invoice value over 1 second
            window.setTimeout(animateGauge4, 6000);
        })();
    }

    // Initialize gauge5 with its own PHP variables
    var gauge5 = initializeGauge("gauge5", <?= $total_cabang ?>, <?= $perusahaan->kuota_cabang ?>);
    if (gauge5) {
        // A self-calling function to animate gauge1 (if desired, based on your original code)
        (function animateGauge5() {
            gauge1.setValue(<?= $total_cabang ?>);
            gauge1.setValueAnimated(<?= $total_cabang ?>, 1); // Animate to the actual total_invoice value over 1 second
            window.setTimeout(animateGauge5, 6000);
        })();
    }

    // Function to initialize and configure the premium expiration gauge
    function initializePremiumExpirationGauge(elementId, startDateStr, endDateStr) {
        var svgElement = document.getElementById(elementId);
        var premiumStatusText = document.getElementById('premiumStatusText');
        var premiumDaysRemainingText = document.getElementById('premiumDaysRemainingText');

        if (svgElement && premiumStatusText && premiumDaysRemainingText) {
            const startDate = new Date(startDateStr);
            const endDate = new Date(endDateStr);
            const now = new Date();

            // Calculate total duration of the premium in milliseconds
            const totalDurationMs = endDate.getTime() - startDate.getTime();
            // Calculate remaining duration in milliseconds
            const remainingDurationMs = endDate.getTime() - now.getTime();

            // Convert durations to days
            const msPerDay = 1000 * 60 * 60 * 24;
            const totalDurationDays = totalDurationMs / msPerDay;
            let daysRemaining = remainingDurationMs / msPerDay; // Can be negative if expired

            let percentageRemaining = 0;
            let isExpired = false;

            // Handle cases where premium is already expired or near expiration
            if (daysRemaining <= 0) {
                isExpired = true;
                daysRemaining = 0; // Set to 0 for display purposes if negative
                percentageRemaining = 0; // 0% remaining
                premiumStatusText.innerHTML = '<span class="text-danger">Premium Telah Berakhir!</span>';

                premiumDaysRemainingText.textContent = `<?= tgl_indo(date('Y-m-d', strtotime($perusahaan->expired_day ?? 0))) ?>`;


            } else {
                // Calculate percentage remaining relative to total duration
                percentageRemaining = (daysRemaining / totalDurationDays) * 100;
                // premiumStatusText.textContent = `Expires on: ${endDate.toLocaleDateString()}`;
                premiumStatusText.textContent = `<?= tgl_indo(date('Y-m-d', strtotime($perusahaan->expired_day ?? 0))) ?>`;
                // Use Math.ceil to round up days remaining, as even a fraction of a day means it's still "today"
                premiumDaysRemainingText.textContent = `${Math.ceil(daysRemaining)} hari tersisa`;
            }

            // Ensure percentage is clamped between 0 and 100 for the gauge display
            percentageRemaining = Math.max(0, Math.min(100, percentageRemaining));

            return Gauge(svgElement, {
                max: 100, // Max for this gauge is always 100%
                dialStartAngle: -90,
                dialEndAngle: -90.001,
                value: percentageRemaining, // Gauge value is the calculated percentage
                showValue: true, // Show the percentage value on the gauge itself
                label: function(value) {
                    return `${Math.round(value)}%`; // Display percentage as the label
                },
                color: function(value) {
                    // Color logic based on percentage of time remaining
                    if (isExpired) {
                        return base.dangerColor; // Always red if expired
                    } else if (value < 20) { // Less than 20% remaining
                        return base.dangerColor; // Red for critical
                    } else if (value < 50) { // Less than 50% remaining
                        return base.warningColor; // Yellow for warning
                    } else { // 50% or more remaining
                        return base.primaryColor; // Green for healthy status
                    }
                }
            });
        }
        return null;
    }

    // Initialize the Premium Expiration Gauge
    var premiumGauge = initializePremiumExpirationGauge(
        "premiumGauge",
        "<?= $perusahaan->start_day ?>", // Replace with your actual premium start date PHP variable (e.g., $user_premium_start_date)
        "<?= $perusahaan->expired_day ?>" // Replace with your actual premium end date PHP variable (e.g., $user_premium_end_date)
    );
</script>