
document.addEventListener('DOMContentLoaded', () => {

    const navToggle = document.querySelector('.nav-toggle')
    if (navToggle) {
        navToggle.addEventListener('click', (event) => {
            event.preventDefault()
            const nav = document.querySelector('.navigation')
            if (nav) {
                nav.classList.toggle('active')
            }
        })
    }

    const searchToggle = document.querySelector('.search-toggle')
    if (searchToggle) {
        searchToggle.addEventListener('click', (event) => {
            event.preventDefault()
            const search = document.querySelector('.search-form')
            if (search) {
                search.classList.toggle('active')
            }
        })
    }

    const watchlistToggle = document.querySelectorAll('.watchlists-toggle')
    if (watchlistToggle.length) {
        watchlistToggle.forEach(toggle => {
            toggle.classList.toggle('active', localStorage.getItem('showWatchlists') !== null)
            toggle.addEventListener('click', (event) => {
                event.preventDefault()
                if (toggle.classList.contains('active')) {
                    toggle.classList.remove('active')
                    localStorage.removeItem('showWatchlists')
                } else {
                    toggle.classList.add('active')
                    localStorage.setItem('showWatchlists', ':)')
                }
            })
        })
    }

    const activityDots = document.querySelectorAll('.activity .year [data-date]')
    if (activityDots.length) {
        activityDots.forEach(dot => {
            let date = dot.dataset.date
            if (date) {
                dot.addEventListener('click', () => window.location.href = `/${date.replaceAll('-','/')}`)
            }
        });
    }

    if (960 <= document.body.clientWidth) {
        const timelineItems = document.querySelectorAll('.timeline .post')
        if (timelineItems.length) {
            const timelineElement = timelineItems[0].parentElement;
            timelineItems.forEach(item => {
                if (item.offsetLeft < Math.round(timelineElement.offsetWidth / 2)) {
                    item.classList.add('column-left')
                } else {
                    item.classList.add('column-right')
                }
            })
        }
    }

    document.querySelectorAll('[data-chartist]')?.forEach(chart => {
        if (chart?.dataset?.chartist) {
            let data = localStorage.getItem(chart.dataset.chartist);
            if (data) {
                data = JSON.parse(data);
                new Chartist.LineChart(chart, {
                    labels: data.labels,
                    series: data.series
                }, {
                    axisY: {
                        offset: 20,
                    },
                    chartPadding: {
                        left: 0,
                    },
                    height: '200px',
                    low: 0,
                    showArea: true,
                    // reverseData: true,
                });
            }
        }
    });
});