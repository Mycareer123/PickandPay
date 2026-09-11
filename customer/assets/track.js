const stages = ['Pending', 'Processing', 'Delivered'];

const stageIcons = {
    'Pending':    'ti-clock',
    'Processing': 'ti-settings',
    'Delivered':  'ti-circle-check'
};

function buildTimeline(currentStatus) {
    const timeline = document.getElementById('timeline');
    timeline.innerHTML = '';

    const currentIndex = stages.indexOf(currentStatus);

    stages.forEach((stage, index) => {
        const isCompleted = index < currentIndex;
        const isActive    = index === currentIndex;
        const isFuture    = index > currentIndex;
        const isLast      = index === stages.length - 1;

        let dotClass  = isCompleted ? 'dot-completed' : isActive ? 'dot-active' : 'dot-future';
        let iconClass = isCompleted ? 'ti-check' : stageIcons[stage];
        let lineClass = isCompleted ? 'timeline-line completed' : 'timeline-line';
        let stageClass = isActive ? 'timeline-stage active' : isFuture ? 'timeline-stage future' : 'timeline-stage';

        const item = document.createElement('div');
        item.className = 'timeline-item';
        item.innerHTML = `
            <div class="timeline-left">
                <div class="timeline-dot ${dotClass}">
                    ${!isFuture ? `<i class="ti ${iconClass}"></i>` : ''}
                </div>
                ${!isLast ? `<div class="${lineClass}"></div>` : ''}
            </div>
            <div class="timeline-content">
                <div class="${stageClass}">${stage}</div>
                <div class="timeline-date">${isActive || isCompleted ? 'Updated' : '—'}</div>
            </div>
        `;
        timeline.appendChild(item);
    });
}

document.getElementById('trackBtn').addEventListener('click', async () => {
    const ref = document.getElementById('refInput').value.trim();
    const searchError = document.getElementById('searchError');
    const loading     = document.getElementById('loading');
    const notFound    = document.getElementById('notFound');
    const resultArea  = document.getElementById('resultArea');

    // Reset
    searchError.classList.remove('show');
    notFound.classList.remove('show');
    resultArea.classList.remove('show');

    if (!ref) {
        searchError.classList.add('show');
        return;
    }

    // Show loading
    loading.classList.add('show');

    try {
        const response = await fetch(`../controllers/track_order.php?ref=${encodeURIComponent(ref)}`);
        const result   = await response.json();

        loading.classList.remove('show');

        if (result.status === 'success') {
            const order = result.order;

            // Fill result area
            document.getElementById('resultRef').textContent    = order.order_ref;
            document.getElementById('resultDate').textContent   = order.order_date;
            document.getElementById('resultName').textContent   = order.customer_name;
            document.getElementById('resultPhone').textContent  = order.customer_phone;
            document.getElementById('resultAddress').textContent = order.delivery_address + ', ' + order.city;
            document.getElementById('resultRegion').textContent = order.region;
            document.getElementById('resultTotal').textContent  = 'GH₵' + Number(order.total_price).toLocaleString();

            // Build timeline
            buildTimeline(order.status);

            resultArea.classList.add('show');

        } else {
            notFound.classList.add('show');
        }

    } catch (error) {
        loading.classList.remove('show');
        notFound.classList.add('show');
    }
});

// Allow pressing Enter to track
document.getElementById('refInput').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        document.getElementById('trackBtn').click();
    }
});

// Auto track if ref is in URL
const urlParams = new URLSearchParams(window.location.search);
const refFromUrl = urlParams.get('ref');
if (refFromUrl) {
    document.getElementById('refInput').value = refFromUrl;
    document.getElementById('trackBtn').click();
}
