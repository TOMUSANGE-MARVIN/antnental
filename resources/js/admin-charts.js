import Chart from 'chart.js/auto';

// Initialize all charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Appointment Trends Line Chart
    const trendsCtx = document.getElementById('appointmentTrendsChart');
    if (trendsCtx && window.appointmentTrendsData) {
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: window.appointmentTrendsData.labels,
                datasets: [{
                    label: 'Appointments',
                    data: window.appointmentTrendsData.data,
                    borderColor: '#14B8A6',
                    backgroundColor: 'rgba(20, 184, 166, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#14B8A6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)'
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    }
                }
            }
        });
    }

    // Appointment Status Pie Chart
    const statusCtx = document.getElementById('appointmentStatusChart');
    if (statusCtx && window.appointmentStatusData) {
        const colors = window.appointmentStatusData.labels.map(status => 
            window.appointmentStatusData.colors[status] || '#6B7280'
        );
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: window.appointmentStatusData.labels.map(label => 
                    label.charAt(0).toUpperCase() + label.slice(1)
                ),
                datasets: [{
                    data: window.appointmentStatusData.data,
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8
                    }
                },
                cutout: '60%'
            }
        });
    }

    // Doctor Workload Bar Chart
    const workloadCtx = document.getElementById('doctorWorkloadChart');
    if (workloadCtx && window.doctorWorkloadData) {
        new Chart(workloadCtx, {
            type: 'bar',
            data: {
                labels: window.doctorWorkloadData.labels,
                datasets: [{
                    label: 'Total Appointments',
                    data: window.doctorWorkloadData.data,
                    backgroundColor: '#3B82F6',
                    borderColor: '#3B82F6',
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6B7280',
                            maxRotation: 45
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)'
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    }
                }
            }
        });
    }

    // Patient Demographics Pie Chart
    const demographicsCtx = document.getElementById('patientDemographicsChart');
    if (demographicsCtx && window.patientDemographicsData) {
        const demographicColors = [
            '#F59E0B', '#EF4444', '#8B5CF6', '#10B981', 
            '#3B82F6', '#F97316', '#6B7280'
        ];
        
        new Chart(demographicsCtx, {
            type: 'pie',
            data: {
                labels: window.patientDemographicsData.labels,
                datasets: [{
                    data: window.patientDemographicsData.data,
                    backgroundColor: demographicColors.slice(0, window.patientDemographicsData.labels.length),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.parsed / total) * 100);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});